<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\Audio;
use Datetime;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\FileSystem;


class FyleSistemServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }
   
    public function test_getShortUrl()
    {
        $fileSystem = new FileSystem();
        $audios = Audio::factory()->count(100)->create();
        for($i=0; $i<100; $i++)
        {
            $shortUrl = $fileSystem->getShortUrl();
            //Log::channel('stderr')->info('Result:'.$shortUrl);
            $this->assertFalse(Audio::where('ShortUrl',$shortUrl)->exists());
            $audionew = Audio::factory()->create(['ShortUrl' => $shortUrl]);
            $audios->add($audionew);
        }
   
    }
    public function test_encryptFile()
    {
        $fileSystem = new FileSystem();
        $key  = 'dfjdiogjfpdjgpfdjgpfdijgpifdhgoi';// Str::substr(Hash::make('Audio_'.DateTime::createFromFormat('U.u',number_format(microtime(true), 6, '.', ''))->format('Y_m_d_H_i_s_u'), [
        // 'rounds' => 8,
        //]),0,32);
   
        //Log::channel('stderr')->info('keyResult:'.$key);
        
        if(Storage::disk('local')->exists('/test/encriptedAudio.mp3'))
        {
            Storage::disk('local')->delete('/test/encriptedAudio.mp3'); 
        }
        $file =  Storage::disk('local')->get('/test/testAudio.mp3');
        $encrypted = $fileSystem->encryptFile($file,$key);
        $this->assertTrue(Storage::disk('local')->put('/test/encriptedAudio.mp3', $encrypted));
        
    }
    public function test_decriptFile()
    {
        $fileSystem = new FileSystem();
        $key = 'dfjdiogjfpdjgpfdjgpfdijgpifdhgoi';
   
        //Log::channel('stderr')->info('keyResult:'.$key);
        
        if(Storage::disk('local')->exists('/test/decriptedAudio.mp3'))
        {
            Storage::disk('local')->delete('/test/decriptedAudio.mp3'); 
        }
        $file =  Storage::disk('local')->get('/test/encriptedAudio.mp3');
        $decripted = $fileSystem->decryptFile($file,$key);
        $this->assertTrue(Storage::disk('local')->put('/test/decriptedAudio.mp3', $decripted));
        
    }
    public function test_saveFile_getFile()
    {
        if(Storage::disk('local')->exists('/test/decriptedAudio.mp3'))
        {
            Storage::disk('local')->delete('/test/decriptedAudio.mp3'); 
        }
        $fileSystem = new FileSystem();
        $file =  Storage::disk('local')->get('/test/testAudio.mp3');
        $audio = $fileSystem->saveFile($file,1);
          
        $savedfile =$fileSystem->getFile($audio);
        $this->assertTrue($savedfile != null);
        $this->assertTrue(Storage::disk('local')->put('/test/decriptedAudio.mp3', $savedfile));
          
    }
   
    /* protected function getShortUrl($retry = 0, $randInt = 1)
       {
    
        
       $shortUrl = Str::random($randInt).'-'.Str::random($randInt).'-'.Str::random($randInt);
        
       if(Audio::where('ShortUrl', $shortUrl)->exists())
       {
       Log::channel('stderr')->info('Duplicate Found! :'.$randInt. " Retry:".$retry);
       if($retry >= 3)
       {
       Log::channel('stderr')->info('Retry is more than 3');
       $retry = 0;
       $biggest_randint_raw = Audio::whereNotNull('ShortUrl')->orderByRaw('CHAR_LENGTH("ShortUrl") DESC')->limit(1)->value('ShortUrl');
       Log::channel('stderr')->info("Biggest_randint:". $biggest_randint_raw);
       $biggest_randint = (strlen($biggest_randint_raw) -2) / 3;
       if(strlen($biggest_randint) <= $randInt){
             
       $retry = -1;
                    
       }
       Log::channel('stderr')->info('The new lenght url is'.$randInt);
       $randInt = $randInt + 1;
       }
       $retry = $retry +1;

            
       return $this->getShortUrl($retry,$randInt);
       }
        
       return $shortUrl;
       }/*/
    

    public function test_getServerUrl()
    {
        $fileSystem = new FileSystem();
        // Log::channel('stderr')->info($fileSystem->getServerUrl());
        $this->assertStringContainsString('/audios', $fileSystem->getServerUrl());
        
    }
}

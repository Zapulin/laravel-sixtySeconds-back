<?php
namespace App\Services;
use App\Models\Audio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;
use Datetime;
use Config;
use Illuminate\Support\Facades\Hash;
class FileSystem
{

    public function getFile(Audio $audio)
    {
        $file = null;
        if($audio->server == null || $audio->server == 'local')
        {
            if(!Storage::disk('local')->exists($audio->Url))
            {
                die('El archivo no existe');
            }
            else
            {
                $file = Storage::disk('local')->get($audio->Url);
                $decryptedFile = $this->decryptFile($file,$audio->ClaveDesbloqueo);
            }
                
        }else if($audio->server == 'sftp'){
            return null;
        }
        return $decryptedFile;
    }
    //Saves the file into the server and returns audio file or false
    public function saveFile($file, $idVisibilidad)
    {
        $audio = $this->createAudioFromFile($file);
        $audio->idVisibilidad = $idVisibilidad;

        //TODO Encriptar!
        $encryptedFile = $this->encryptFile($file,$audio->ClaveDesbloqueo);
        if (! Storage::disk('local')->put($audio->Url, $encryptedFile)) {
            // The file could not be written to disk...
            return false;
        }
        $audio->save();
        return $audio;
        

    }
    public function createAudioFromFile($file)
    {
        //https://stackoverflow.com/questions/40033879/handling-file-upload-in-laravels-controller
        $audio = new Audio();
        //Get file size, created date
        $audio->Tamano = ini_get('mbstring.func_overload') ? mb_strlen($file , '8bit') : strlen($file);
        //filesize($file);
        //Set file url
        $audioName= 'Audio_'.DateTime::createFromFormat('U.u',number_format(microtime(true), 6, '.', ''))->format('Y_m_d_H_i_s_u');
        $audio->Url = $this->getServerUrl().'/'.$audioName;
        //Set file hashkey
        $audio->ClaveDesbloqueo = Str::substr(Hash::make($audioName, [
            'rounds' => 12,
        ]),0,32);
        //Get Shorturl
        $audio->ShortUrl = $this->getShortUrl();
        $audio->FechaCreacion = now();
        return $audio;
    }
    
    public function getServerUrl()
    {
        $directories = "/audios";//Storage::disk('local')->path('/audios');
        // TODO Establecer un sistema mejor de guardado para los archivos, por ejemplo almacenar en distintas carpetas
        /*unset($directories[0], $directories[1]);
        for($directories as $directory)
        {
          
            
        }*/
        return $directories;
        
    }
  
    //Gets shortUrl for database, if it already exists gets a new one
    //If there are more than 15 possible maches, the shorturl lenght is increased
    public function getShortUrl($retry = 0, $randInt = 5)
    {
        $shortUrl = Str::random($randInt).'-'.Str::random($randInt).'-'.Str::random($randInt);
        
        if(Audio::where('ShortUrl', $shortUrl)->exists())
        {
            
            if($retry >= 3)
            {
            
                $retry = 0;
                $biggest_randint_raw = Audio::whereNotNull('ShortUrl')->orderByRaw('CHAR_LENGTH("ShortUrl") DESC')->limit(1)->value('ShortUrl');
            
                $biggest_randint = (strlen($biggest_randint_raw) -2) / 3;
                if(strlen($biggest_randint) <= $randInt){
             
                    $retry = -1;
                }
                $randInt = $randInt + 1;
            }
            $retry = $retry +1;

            
            return $this->getShortUrl($retry,$randInt);
        }
        
        return $shortUrl;
    
    }
    public function encryptFile($file , $key)
    {
        $cipher = Config::get('app.cipher');
        $encrypter = new Encrypter($key, $cipher);
        return $encrypter->encryptString($file);
        /*
        $newEncrypter = new \Illuminate\Encryption\Encrypter($key, Config::get( 'app.cipher' ) );
        return  $newEncrypter->encrypt( $file );//This is not plain text, it may fail.
        */
    }
    public function decryptFile($file , $key)
    {
        $cipher = Config::get('app.cipher');
        $encrypter = new Encrypter($key, $cipher);
        return $encrypter->decryptString($file);
    }
}

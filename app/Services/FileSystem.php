<?php
namespace App\Services;
use App\Models\Audio;
use Illuminate\Support\Facades\Storage;
class FileSystem
{
   
    public function getFile(Audio $audio)
    {
        $file = null;
        if($audio->server == null || $audio->Server == 'local')
        {
            if(!Storage::disk('local')->exists($audio->Url))
            {
                die('El archivo no existe');
            }
            else
            {
                $file = Storage::disk('local')->get($audio->Url);
            }
                
        }else if($audio->server == 'sftp'){
            return null;
        }
        return $file;
    }
    
}

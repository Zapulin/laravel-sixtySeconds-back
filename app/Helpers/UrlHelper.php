<?php
namespace App\Helpers;

class UrlHelper
{
    public static function getAudioFullUrl($shortUrl)
    {
        return env('APP_URL').'/api/audio/'.$shortUrl;
        
    }
    public static function getImageUrl($url)
    {
         return env('APP_URL').'/api/image/'.$url;
    }

}

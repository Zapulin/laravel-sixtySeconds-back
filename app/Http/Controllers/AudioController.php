<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAudioRequest;
use App\Http\Requests\UpdateAudioRequest;
use App\Models\Audio;
use App\Services\FileSystem;
use Illuminate\Support\Facades\Response;

class AudioController extends Controller
{
    protected $fileSystemService;
    public function __construct(FileSystem $fileSystemService)
    {
        $this->fileSystemService = $fileSystemService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $response = Response::make('OK', 200);
        
        if($request->hasFile('audio'))
        {
            if ($request->file('audio')->isValid()) {
                
                $audio=$this->fileSystemService->saveFile($request->file('audio'),$request->visibility);
            }
            else
            {
                $response = Response::make('FileNotFoundInRequest',400);
            }
        }
        else
        {
            $response = Response::make('FileNotFoundInRequest',400);
        }
        if($audio)
        {
            $audio->save();
        }

        
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAudioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAudioRequest $request)
    {
        //
    }

    
    public function getAudio(string $shortUrl)
    {
        $audio = Audio::where('ShortUrl',$shortUrl)->firstOrFail();
        $file = $this->fileSystemService->getFile($audio);
        $response = Response::make($file,200);
        $mime_type = "audio/mpeg";
       
        //$response->header('Accept-Ranges',' 0-' .$audio->Tamano );
        //$response->header('Content-Length',$audio->Tamano );
        $response->header('Content-Type', $mime_type );
        $response->header('Content-Disposition','inline');
        
        return $response; 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function edit(Audio $audio)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAudioRequest  $request
     * @param  \App\Models\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAudioRequest $request, Audio $audio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Audio  $audio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Audio $audio)
    {
        //
    }
}

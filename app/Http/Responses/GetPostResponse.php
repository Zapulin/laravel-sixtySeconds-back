<?php
namespace App\Http\Responses;
use \Illuminate\Http\FormResponse;
use App\Services\CommentChainService;
use stdClass;
use App\Helpers\UrlHelper;
use Illuminate\Support\Facades\Log;
class GetPostResponse
{
    protected CommentChainService $commentChainService;
    public function __construct(CommentChainService $_commentChainService)
    {
        $this->commentChainService = $_commentChainService;
    }
    public function getResponseFromMultiplePost($posts)
    {

        $response = [];
        if($posts instanceof \Illuminate\Database\Eloquent\Model)
        {
            $response[] = $this->getResponseFromPost($posts);
        }
        else{
            if(count($posts) > 0)
            {
                foreach ($posts as $post)
                {
                    $response[] = $this->getResponseFromPost($post);
                }
            }
        }


    return $response;
}
public function getResponseFromPost($post)
{
    /* {
       "idPost": 3,
       "idVisibilidad": 1,
       "Visualizaciones": 30,
       "idUsuario": 4,
       "idAudio": 887687,
       "FechaCreacion": "1996-08-18 00:00:00+02",
       "Dislikes": 323,
       "Likes": 333
       }
    */
    $response = new stdClass();
    $response->id = $post->idPost;
    if(isset($post->Usuario))
    {
        $response->author = $post->Usuario->Nombre;
        $response->userId = $post->Usuario->idUsuario;
    }
    $response->title = $post->Titulo;
    if(isset($post->Audio))
        $response->audioURL = UrlHelper::getAudioFullUrl($post->Audio->ShortUrl);
    //Log::channel('stderr')->info('Post:'.$post->idPost);
    //Log::channel('stderr')->info('Tematica:'.$post->Tematica);
    //Log::channel('stderr')->info('Post:'.$post->Tematica()->first());
    if($post->Tematica()->exists())
    {
        $response->category[] = $post->Tematica()->pluck('Nombre');
        /*foreach( $post->Tematica() as  $tematica)
          $response->category[] = $tematica->Nombre;*/
    }
    $response->creationDate = $post->FechaCreacion;
    $response->likes = $post->Likes;
    $response->dislikes = $post->Dislikes;
    //Log::channel('stderr')->info('Comentarios:'.json_encode($post->Comentarios));
    if(isset($post->Comentarios))
        $response->comments =$this->commentChainService->parseCommentsToResponse($post->Comentarios);
    return $response;
}

}

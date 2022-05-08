<?php
namespace App\Services;
use App\Models\Audio;
use App\Models\Comentario;
use App\Models\Post;
use App\Models\Usuario;
use App\Helpers\UrlHelper;
use Illuminate\Support\Facades\Log;
//use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use stdClass;

class CommentChainService
{
    
    public function parseCommentsToResponse( $postComentarios, $excluirHijos = true )
   {
       if(empty($postComentarios)) return [];
       if($excluirHijos)
           $comentarios = $postComentarios->whereNull('idComentarioPadre');
       else $comentarios = $postComentarios;
           
       $comments = [];//new CommentResponse;
        foreach( $comentarios as $comentario)
        {
            $newComment = new stdClass();
            $newComment->commentId = $comentario->idComentario;
            $newComment->creationDate = $comentario->FechaCreacion;
            $newComment->likes = $comentario->Likes;
            $newComment->dislikes = $comentario->Dislikes;
            //Datos de usuario
            $usuario = $comentario->Usuario;//Usuario::where('idUsuario',$comentario->idUsuario)->first();
            $newComment->userId = $usuario->idUsuario; 
            $newComment->author = $usuario->Nombre;
            //obtener audio
            $audio = Audio::where('idAudio',$comentario->idAudio)->first();
            $newComment->audioUrl= UrlHelper::getAudioFullUrl($audio->ShortUrl);
            
            // Obtener hijos
            $newComment->children = $this->parseCommentsToResponse($comentario->Comentarios,false);
            $comments[] = $newComment;
        }
        return $comments;
    }

}
/*
class CommentResponse implements SerializesCastableAttributes, JsonSerializable //implements Illuminate\Contracts\Support\Arrayable 
{
  
    public function jsonSerialize() {
        return $this;
    }
    public function serialize($model, string $key, $value, array $attributes)
    {
    return (string) $value;
    }
    }*/
/*

SELECT "idComentario", "idUsuario", "idComentarioPadre", "idPost", "idAudio", "FechaCreacion", "Likes", "Dislikes"
	FROM "default"."Comentarios";

SELECT "idUsuario", "Nombre", "FechaNacimiento", "Email", "idAudioPresentacion", "Password", "ImagenPerfilUrl", "ImagenFondoUrl"
	FROM "default"."Usuarios";
*/

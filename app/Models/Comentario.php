<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Audio;
use App\Models\Post;

class Comentario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'idComentario';
    protected $table = "Comentarios";

    public function Usuario()
    {
        return $this->belongsTo(Usuario::class,'idUsuario');
    }
    public function Comentarios()
    {
        return $this->hasMany(Comentario::class,'idComentarioPadre','idComentario');
    }
     public function Padre()
    {
        return $this->belongsTo(Comentario::class,'idComentarioPadre','idComentario');
    }
    public function Audio()
    {
        return $this->belongsTo(Audio::class,'idAudio','idAudio');
        
    }
    public function Post()
    {
        return $this->belongsTo(Post::class,'idPost','idPost');
    }

}

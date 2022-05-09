<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Comentario;
use App\Models\Audio;
use App\Models\Tematica;
use App\Models\TematicaPost;
use App\Models\Visibilidad;
class Post extends Model
{
    use HasFactory;

    protected $table= 'Post';
    protected $primaryKey='idPost';
    public $timestamps = false;
    use HasFactory;

    public function Usuario()
    {
        return $this->belongsTo(Usuario::class,'idUsuario','idUsuario');
    }
    public function Comentarios()
    {
        return $this->hasMany(Comentario::class,'idPost');//->where('idComentarioPadre',null);
    }
    public function Audio()
    {
        return $this->belongsTo(Audio::class,'idAudio');
    }
    public function Visibilidad()
    {
           return $this->belongsTo(Visibilidad::class,'idVisibilidad');
    }
    public function Tematica()
    {

        return $this->belongsToMany(Tematica::class,'TematicaPost','idPost','idTematica')->using(TematicaPost::class);
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        /* return $this->hasManyThrough(
            Tematica::class,//Destino
            TematicaPost::class,//Intermediaria
            'idPost', // Foreign key on the Intermediaria table...
            'idTematica', // Foreign key on the destino table...
            'idPost', // Local key on the Actual table...
            'idTematica' // Local key on the Intermediaria table...
            );*/
    }
}

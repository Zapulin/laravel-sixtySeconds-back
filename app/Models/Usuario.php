<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasFactory, HasApiTokens;
    public $timestamps = false;
    protected $primaryKey = 'idUsuario';
    protected $table= 'Usuarios';

    public function Audio()
    {
        return $this->belongsTo(Audio::class,'idAudio','idAudio');

    }

//    public function UsuariosSeguidores()
//    {
//        return $this->belongsToMany(UsuariosSeguidores::class,'idUsuariosSeguidores','idUsuario_Usuarios');
//    }
}

<?php

namespace App\Models;
use App\Models\Tematica;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TematicaPost extends Pivot
{
    protected $table= 'TematicaPost';
    protected $primaryKey='idTematicaPost';
    public $timestamps = false;
    public $incrementing = true;
    
    use HasFactory;

    public function Post()
    {
        return $this->belongsTo(Post::class,'idPost','idPost');
    }
    public function Tematica()
    {
        return $this->belongsTo(Tematica::class,'idTematica','idTematica');
    }
    
}

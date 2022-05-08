<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visibilidad extends Model
{
    use HasFactory;
    protected $table= 'Visibilidad';
    protected $primaryKey='idVisibilidad';
    public $timestamps = false;

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tematica extends Model
{
    protected $table= 'Tematica';
    protected $primaryKey='idTematica';
    public $timestamps = false;
    use HasFactory;
}

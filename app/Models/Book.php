<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage; // Ajout de la classe Storage pour gérer les fichiers

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'description',
        'cover_path',
        'file_path',
        'file_type',
        'user_id',
    ];


    public function user()
{
    return $this->belongsTo(User::class);
}

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = ['url', 'name'];

    public function mangas()
    {
        return $this->belongsToMany(Manga::class);
    }

    public function manga_sources()
    {
        return $this->hasMany(MangaSource::class);
    }
}

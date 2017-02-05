<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    protected $fillable = ['name'];

    public function sources()
    {
        return $this->belongsToMany(Source::class);
    }

    public function manga_sources()
    {
        return $this->hasMany(MangaSource::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = ['name', 'source_id'];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}

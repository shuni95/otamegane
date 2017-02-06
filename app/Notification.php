<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['manga', 'chapter', 'title', 'status', 'source_id'];

    public function scopePrevious($query, $manga, $chapter, $source_id)
    {
        return $query->where('manga', $manga)
                     ->where('chapter', $chapter)
                     ->where('source_id', $source_id);
    }

    public static function last($manga, $source_id)
    {
        return (new static())::where('manga', $manga)
                     ->where('source_id', $source_id)
                     ->orderBy('created_at', 'DESC')
                     ->first();
    }
}

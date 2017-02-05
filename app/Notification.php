<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['manga', 'chapter', 'title', 'status', 'source_id'];

    public static function dontExistsPrevious($manga, $chapter, $source_id)
    {
        $notification = Notification::where('manga', $manga)
                                    ->where('chapter', $chapter)
                                    ->where('source_id', $source_id)
                                    ->first();

        return is_null($notification);
    }
}

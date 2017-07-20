<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    const NOTE_TITLE_MARKDOWN_TAG = '#';
    const NOTE_AUTHOR_MARKDOWN_TAG = '*';
    const NOTE_DATETIME_MARKDOWN_TAG = '**';

    protected $fillable = [
        'title', 'author', 'dateTime', 'text'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function coverToMarkdown()
    {
        $noteMarkdown = [];
        $noteMarkdown['title'] = self::NOTE_TITLE_MARKDOWN_TAG.' '.$this->title;
        $noteMarkdown['author'] = self::NOTE_AUTHOR_MARKDOWN_TAG.$this->author.self::NOTE_AUTHOR_MARKDOWN_TAG;
        $noteMarkdown['dateTime'] = self::NOTE_DATETIME_MARKDOWN_TAG.$this->dateTime.self::NOTE_DATETIME_MARKDOWN_TAG;
        $markdown = $noteMarkdown['title']."\n".$noteMarkdown['author'].' '.$noteMarkdown['dateTime']."\n   ".$this->text."   \n";
        return $markdown;
    }
}

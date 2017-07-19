<?php

namespace App;

class Clipping
{
    const NOTE_SECTION_SEPARATOR = '==========';
    const NOTE_AUTHOR_SEPAPATOR = '(';
    const NOTE_DATETIME_SEPARATOR = '|';
    const NOTE_TITLE_MARKDOWN_TAG = '#';
    const NOTE_AUTHOR_MARKDOWN_TAG = '*';
    const NOTE_DATETIME_MARKDOWN_TAG = '**';
    const SEPARATOR_LENGTH = 10;
    const NOTE_TITLE_LINE = 1;
    const NOTE_DATETIME_LINE = 2;
    const NOTE_TEXT_LINE = 4;
    const NOTE_DEFAULT_TITLE = 'Default title';
    const NOTE_DEFAULT_AUTHOR = 'Unknown';
    const NOTE_DEFAULT_DATETIME = '1970-01-01 00:00:00';

    private $filename = '';
    private $notes = [];

    function __construct($filename)
    {
        $this->filename = $filename;
    }

    private function parseForSection()
    {
        $resourceClippingFile = null;
        if (file_exists($this->filename)){
            $resourceClippingFile = fopen($this->filename, 'r');
        }else {
            throw new \Exception("Clipping file does not exits", 1);
        }

        $sectionsRaw = [];
        $sectionRawTemp = '';
        while (($line = fgets($resourceClippingFile)) !== false)
        {
            if (mb_substr($line, 0, self::SEPARATOR_LENGTH) === self::NOTE_SECTION_SEPARATOR) {
                array_push($sectionsRaw, htmlentities(trim($sectionRawTemp)));
                $sectionRawTemp = '';
            }
            else {
                $sectionRawTemp .= $line;
            }
        }
        unset($sectionsRaw[0]);

        $sectionsParsed = [];
        $delimiter = "\r";
        $limit =  3;
        foreach ($sectionsRaw as $index=>$section){
            $sectionInfo = explode($delimiter, $section, $limit);
            if (count($sectionInfo)!==$limit){
                continue;
            }

            $extractedTitle = mb_strstr($sectionInfo[self::NOTE_TITLE_LINE - 1], self::NOTE_AUTHOR_SEPAPATOR, true);
            $extractedAuthor = mb_strstr($sectionInfo[self::NOTE_TITLE_LINE - 1], self::NOTE_AUTHOR_SEPAPATOR, false);
            $title = $extractedTitle ? $extractedTitle : self::NOTE_DEFAULT_TITLE;
            $author = $extractedAuthor ? $extractedAuthor : self::NOTE_DEFAULT_AUTHOR;
            $dateTime = mb_strstr($sectionInfo[self::NOTE_DATETIME_LINE-1], self::NOTE_DATETIME_SEPARATOR, false);
            $text = $sectionInfo[$limit-1];

            $sectionsParsedTemp = ['title'=>$title, 'author'=>$author, 'dateTime'=>$dateTime, 'text'=>$text];
            array_push($sectionsParsed, $sectionsParsedTemp);
        }

        return $sectionsParsed;
    }

    private function mergeSection($sections)
    {
        foreach ($sections as $index=>$section){
            if (array_key_exists($section['title'], $this->notes)){
                $this->notes[$section['title']]['text'] .= $section['text'];
            }else {
                $this->notes[$section['title']] = $section;
            }
        }

        return $this->notes;
    }

    public function exportToMarkdown($filename)
    {
        file_put_contents($filename, "[TOC]\n", FILE_APPEND);
        foreach ($this->notes as $key=>$note){
            $note['title'] = self::NOTE_TITLE_MARKDOWN_TAG.' '.$note['title'];
            $note['author'] = self::NOTE_AUTHOR_MARKDOWN_TAG.$note['author'].self::NOTE_AUTHOR_MARKDOWN_TAG;
            $note['dateTime'] = self::NOTE_DATETIME_MARKDOWN_TAG.$note['dateTime'].self::NOTE_DATETIME_MARKDOWN_TAG;
            $markdown = $note['title']."\n".$note['author'].' '.$note['dateTime']."\n   ".$note['text']."   \n";
            file_put_contents($filename, $markdown, FILE_APPEND);
        }
    }

    public function parse()
    {
        $secions = $this->parseForSection();

        $this->notes = $this->mergeSection($secions);
        return $this->notes;
    }

    public function getNotes()
    {
        return $this->notes;
    }



}


<?php
namespace App;
use Evernote\Client;
use EDAM\Types\Note;
use EDAM\Error\Types\EDAMUserException;
/**
* 
*/
class Evernote
{
	private $client;
	
	function __construct($token)
	{
		try {
			$this->client = new Client($token, true, null, null, false);
		} catch (EDAMUserException $edue) {
			echo '授权已过期，请重新授权';
		}
	}

	public function create($title, $content)
	{
		try {
			$noteStore = $this->client->getUserNotestore();
    		$note = new Note();
    		$nBody = '<?xml version="1.0" encoding="UTF-8"?>';
    		$nBody .= '<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd">';
    		$nBody .= '<en-note><div>' . $content . '</div></en-note>';
    		$note->title = $title;
    		$note->content = $nBody;
    		$note = $noteStore->createNote($note);
    		return $note->guid;
    	} catch (EDAMUserException $edue) {
			echo '授权已过期，请重新授权';
		}
	}
	
		
}
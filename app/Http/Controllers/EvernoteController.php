<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Evernote;
use App\Note;
use EDAM\Error\Types\EDAMUserException;

class EvernoteController extends Controller
{
    public function store(Request $request)
    {
    	$token = $request->session()->get('token');
    	try {
    			$note = Note::find($request->id);
    			$title = html_entity_decode($note->title.$note->author);
    			$content = html_entity_decode(sprintf("%s\n%s",$note->dateTime, $note->text));
    			$evernote = new Evernote($token);
				echo $evernote->create($title, $content);
    	} catch (EDAMUserException $edue) {
    		echo '授权已过期，请重新授权';
    	}


    }
}

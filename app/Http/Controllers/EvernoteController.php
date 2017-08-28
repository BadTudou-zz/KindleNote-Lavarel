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

				if ($evernote->create($title, $content)) {
                    Auth::user()->notify(new SystemNotification('success', "笔记已导出到EverNote"));
                } else{
                    Auth::user()->notify(new SystemNotification('error', "笔记导出到EverNote失败"));
                }
                return  redirect()->action('NoteController@index');

    	} catch (\Exception $edue) {
            Auth::user()->notify(new SystemNotification('error', "重新申请EverNote授权"));
            return redirect()->action('Oauth\EverNoteController@oauth');
    	}


    }
}

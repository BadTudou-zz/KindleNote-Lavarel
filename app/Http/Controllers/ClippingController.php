<?php

namespace App\Http\Controllers;

use App\Clipping;
use Illuminate\Http\Request;
use App\Note;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class ClippingController extends Controller
{
    /**
     * Store a new clipping file
     * @param Request $request
     */
    public function store(Request $request)
    {
        $uploadFilePath = $request->file('uploadFile')->store('clippings');
        $clippingFilePhysicsPath = storage_path().'/app/'.$uploadFilePath;
        $markdownFilePhysicsPath = storage_path().'/app/markdowns/'.basename($uploadFilePath).'.markdown';

        $clipping = new Clipping($clippingFilePhysicsPath);
        if ($clipping->parse()){
            $notes = $clipping->getNotes();
            foreach ($notes as $index=>$note){
                $noteModel = new Note;
                $noteModel->user_id = Auth::id( );
                $noteModel->title = $note['title'];
                $noteModel->author = $note['author'];
                $noteModel->dateTime = $note['dateTime'];
                $noteModel->text = $note['text'];
                if (!$noteModel->save()){
                    // TODO:错误处理
                }
            }

            if ($request->isDownloadMarkdown){
                Session::put('isDownloadMarkdown', 'true');
                Session::put('markdown',basename($uploadFilePath).'.markdown');
                $clipping->exportToMarkdown($markdownFilePhysicsPath);
            }

            return Redirect::to(action('NoteController@index'));
        }

        // TODO：错误处理
    }

    public function download()
    {
        if(!Session::has('isDownloadMarkdown')){
            return Redirect::to(action('NoteController@index'));
        }
        Session::forget('isDownloadMarkdown');
        $markdownFilePhysicsPath = storage_path().'/app/markdowns/'.    session('markdown');
        return response()->download($markdownFilePhysicsPath, 'kindle.markdown')->deleteFileAfterSend(true);
    }
}

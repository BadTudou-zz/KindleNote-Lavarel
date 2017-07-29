<?php

namespace App\Http\Controllers;

use App\Clipping;
use Illuminate\Http\Request;
use App\Note;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Jobs\ParseClippingToNote;
use App\Notifications\SystemNotification;

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
        dispatch(new ParseClippingToNote(Auth::id(), $clippingFilePhysicsPath));

        if ($request->isDownloadMarkdown){
            Session::put('isDownloadMarkdown', 'true');
            Session::put('markdown',basename($uploadFilePath).'.markdown');
            $clipping->exportToMarkdown($markdownFilePhysicsPath);
        }
        Auth::user()->notify(new SystemNotification('info', "笔记正在解析"));
        return Redirect::to(action('NoteController@index'));
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

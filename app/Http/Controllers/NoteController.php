<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO:验证要跳转的页码超过总页码
        $notesTotal = ceil(Auth::user()->notes->count()/9);
        //($request->page > $notesCount)
        if(!$request->has('page') || ($request->page < 1) ){
            $request->page = 1;
        }

        $notes = Auth::user()->notes->forPage($request->page, 9);
        $pagination = (object)['previous'=> $request->page-1,
            'current'=> $request->page,
            'next'=> $request->page+1,
            'total'=> $notesTotal];

        return view('note.index', ['notes'=>$notes, 'pagination'=>$pagination]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('note.create', ['note'=>new Note()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'bail|required|max:100',
            'author'=>'bail|required|max:100',
            'dateTime'=>'bail|required|date',
            'text'=>'required'
        ]);

        if ($validator->fails()) {
            var_dump($request->all());
            // TODO: withInput() not work
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $note = new Note;
        $note->user_id = Auth::id();
        $note->title = $request->title;
        $note->author = $request->author;
        $note->dateTime = $request->dateTime;
        $note->text = $request->text;
        if($note->save()){
            return Redirect::to(action('NoteController@index'));
        }
        else{
            return Redirect::to(action('NoteController@store'))->withErrors('error')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('note.show', ['note'=>Note::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return  view('note.edit', ['note'=>Note::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'bail|required|max:100',
            'author'=>'bail|required|max:100',
            'dateTime'=>'bail|required|date',
            'text'=>'required'
        ]);

        if ($validator->fails()) {
            var_dump($request->all());
            // TODO: withInput() not work
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $note = Note::find($id);
        $note->user_id = Auth::id();
        $note->title = $request->title;
        $note->author = $request->author;
        $note->dateTime = $request->dateTime;
        $note->text = $request->text;
        if($note->save()){
            return Redirect::to(action('NoteController@index'));
        }
        else{
            return Redirect::to(action('NoteController@store'))->withErrors('save note has error')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->notes->find($id)){
            Note::find($id)->delete();
            return Redirect::back();
        }
    }

    public function markdown($id)
    {
        $note = Note::find($id);
        $markdown =  $note->coverToMarkdown();
        $name = str_random(16).'.markdown';
        Storage::put('markdowns/'.$name, $markdown);
        $markdownFilePhysicsPath = storage_path().'/app/markdowns/'.$name;
        return response()->download($markdownFilePhysicsPath, trim($note->title).'.markdown')->deleteFileAfterSend(true);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Auth;
use DB;
use URL;
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
        $notesNumberPerPage = 9;
        $notes = Note::where('user_id', Auth::id())->paginate($notesNumberPerPage);
        $pagesTotal = ceil(Note::where('user_id', Auth::id())->count()/$notesNumberPerPage);
        if(!$request->has('page') || ($request->page < 1) ){
            $request->page = 1;
        }

        $pagination = (object)['previous'=> $request->page-1,
            'current'=> $request->page,
            'next'=> $request->page+1,
            'total'=> $pagesTotal];

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

    public function search(Request $request)
    {
        if($request->has('searchInput')){
            $notes = DB::table('notes')
                ->where('user_id', Auth::id())
                ->where('title', 'LIKE', '%'.$request->searchInput.'%')
                ->get();
        }else {
            return Redirect::to(action('NoteController@index'));
        }

        $pagination = (object)['previous'=> 1,
            'current'=> 1,
            'next'=> 1,
            'total'=> 1];

        return view('note.index', ['notes'=>$notes,
            'pagination'=>$pagination,
            'searchInput'=>$request->searchInput,
            'searchResultNumber'=>$notes->count()]);
    }

    public function batch(Request $request)
    {
        $ids = explode(',', $request->ids);
        if(!$request->has('ids') || !$request->has('action') || !$ids){
            echo json_encode(['sate'=>false, 'action'=>$request['action'], 'ids'=>$ids]);
            return ;
        }

        switch($request->action){
            case 'download':
                $this->batchDownload($ids);
                break;

            case 'delete':
                $this->batchDelete($ids);
                break;
        }
    }

    private function batchDownload($ids)
    {
        $markdown = '[TOC]';
        foreach ($ids as $id){
            $note = Note::find($id);
            $noteMarkdown = $note->coverToMarkdown();
            $markdown .= $noteMarkdown;
        }

        $name = str_random(16).'.markdown';
        $storeFileName = 'KindleNote共'.count($ids).'个笔记';
        Storage::put('markdowns/'.$name, $markdown);
        echo json_encode(['state'=>true, 'data'=> URL::action('NoteController@download', ['filename'=>$name, 'storeFilename'=>$storeFileName])]);
    }

    private function batchDelete($ids)
    {
        foreach ($ids as $id){
            if(Auth::user()->notes->find($id)){
                Note::find($id)->delete();
            }
        }
        echo json_encode(['state'=>true, 'data'=>URL::action('NoteController@index')]);
    }

    public function download(Request $request)
    {
       if (!Auth::user()){
           return Redirect::back();
       }
       $filename = $request->filename;
       $storeFilename = $request->storeFilename;
       $markdownFilePhysicsPath = storage_path().'/app/markdowns/'.$filename;
       return response()->download($markdownFilePhysicsPath, trim($storeFilename).'.markdown')->deleteFileAfterSend(true);
    }

}

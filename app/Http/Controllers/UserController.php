<?php

namespace App\Http\Controllers;

use App\User;
use App\Note;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Facades\Redirect;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.show', ['user'=>Auth::user()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('user.show', ['user'=>User::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        echo 'update'.$id;
        $validator = Validator::make($request->all(), [
            'name'=>'bail|required',
            'email'=>'bail|required|email',
            //'password'=>'bail|required|min:6'
        ]);

        if ($validator->fails()) {
            var_dump($request->all());
            // TODO: withInput() not work
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        //$note->password = $request->password;
        if($user->save()){
            return Redirect::to(action('UserController@show', Auth::id()));
        }
        else{
            return Redirect::to(action('UserController@store', Auth::id()))->withErrors('save user has error')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::id() == $id){
            echo 'equal';
            Note::where('user_id', $id)->delete();
            User::find($id)->delete();
            return Redirect::to(action('Auth\LoginController@login'));
        }
    }
}

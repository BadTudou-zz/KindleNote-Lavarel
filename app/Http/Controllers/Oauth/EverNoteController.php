<?php
namespace App\Http\Controllers\Oauth;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Evernote;
use Cookie;
use Evernote\Client;

class EverNoteController extends Controller
{
	public $token;
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function oauth(Request $request)
    {
    	if (!$request->session()->get('token', '')) {
    		return	Evernote::authorize();

    	} else {
    		echo '已经认证，token是'.$request->session()->get('token');
    	}
	}

	public function result(Request $request)
	{
		if ($request->has('oauth_verifier')) {
			echo 'Evernote授权成功'.$request->session()->get('token');
			if ($request->session()->get('token') == '') {
				$token = Evernote::authorize();
				$request->session()->put('token', $token);
			} else {
				$token = $request->session()->get('token');
				$client = new Client($token, true, null, null, false);
				$noteStore = $client->getUserNotestore();
				$notebooks = $noteStore->listNotebooks();

				foreach ($notebooks as $notebook) {
    				echo "\n\nName : " . $notebook->name;
    			}
    		}
		}
	}
}
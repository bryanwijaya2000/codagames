<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\Users;

class ViewController extends Controller
{
    function home() {
        $user = session('loggedIn');
        if ($user && $user['username'] == env('ADMIN_USERNAME')) {
            return back();
        }
        return view('home');
    }

    function about() {
        $user = session('loggedIn');
        if ($user && $user['username'] == env('ADMIN_USERNAME')) {
            return back();
        }
        return view('about');
    }

    function contact() {
        $user = session('loggedIn');
        if ($user && $user['username'] == env('ADMIN_USERNAME')) {
            return back();
        }
        return view('contact');
    }

    function games() {
        $user = session('loggedIn');
        if ($user && $user['username'] == env('ADMIN_USERNAME')) {
            return back();
        }
        $approvedGames = Games::where('status', '=', 1)->get();
        return view('games', ['approvedGames' => $approvedGames]);
    }

    function register() {
        if (session('loggedIn')) {
            return back();
        }
        return view('register');
    }

    function verify() {
        if (session('loggedIn')) {
            return back();
        }
        return view('verify');
    }

    function login() {
        if (session('loggedIn')) {
            return back();
        }
        return view('login');
    }

    function forgotPassword() {
        if (session('loggedIn')) {
            return back();
        }
        return view('forgotPassword');
    }

    function changePassword() {
        if (session('loggedIn')) {
            return back();
        }
        return view('changePassword');
    }

    function account() {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] == env('ADMIN_USERNAME'))) {
            return back();
        }
        return view('account');
    }

    function uploadedGames() {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] == env('ADMIN_USERNAME'))) {
            return back();
        }
        $userGames = Games::where('user_id', '=', $user->id)->get();
        return view('uploadedGames', ['userGames' => $userGames]);
    }

    function uploadGame() {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] == env('ADMIN_USERNAME'))) {
            return back();
        }
        return view('uploadGame');
    }

    function admin() {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] != env('ADMIN_USERNAME'))) {
            return back();
        }
        $games = Games::join('users', 'users.id', '=', 'games.user_id')->get(['games.id', 'games.name', 'games.image', 'games.file', 'games.user_id', 'users.username', 'games.status']);
        return view('admin', ['games' => $games]);
    }

    function users() {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] != env('ADMIN_USERNAME'))) {
            return back();
        }
        $users = Users::all();
        return view('users', ['users' => $users]);
    }

    function userGames($id) {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] != env('ADMIN_USERNAME'))) {
            return back();
        }
        $userGames = Games::where('user_id', '=', $id)->get();
        $byUser = Users::find($id);
        if ($byUser == null) {
            return redirect('/notFound');
        }
        return view('userGames', ['userGames' => $userGames, 'byUser' => $byUser]);
    }
}

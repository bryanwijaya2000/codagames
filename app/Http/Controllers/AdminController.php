<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\Users;

class AdminController extends Controller
{
    function searchGames() {
        $gameName = '';
        $gameStatus = 2;
        if (isset($_GET['name'])) {
            $gameName = $_GET['name'];
        }
        if (isset($_GET['status'])) {
            $gameStatus = intval($_GET['status']);
        }
        $games = null;
        if ($gameStatus >= -1 && $gameStatus <= 1) {
            $games = Games::join('users', 'users.id', '=', 'games.user_id')->where('games.name', 'like', '%'.$gameName.'%')->where('games.status', '=', $gameStatus)->get(['games.id', 'games.name', 'games.image', 'games.file', 'users.username', 'games.status']);
        }
        else {
            $games = Games::join('users', 'users.id', '=', 'games.user_id')->where('games.name', 'like', '%'.$gameName.'%')->get(['games.id', 'games.name', 'games.image', 'games.file', 'users.username', 'games.status']);
        }
        $result = '';
        $ctr = 1;
        foreach ($games as $g) {
            $result .= '<tr>'.'<br>';
            $result .= '<td>'.$ctr.'</td>'.'<br>'
                        .'<td>'.$g['name'].'</td>'.'<br>'
                        .'<td>'.'<img src="/storage/images/'.$g['image'].'"></td>'.'<br>'
                        .'<td>'.'<a href="/storage/games/'.$g['file'].'">'.$g['file'].'</a></td>'.'<br>'
                        .'<td><a href="/admin/userGames/'.$g['user_id'].'">'.$g['username'].'</a></td>'.'<br>';
            if ($g['status'] == -1) {
                $result .= '<td>Rejected</td>'.'<br>';
            }
            else if ($g['status'] == 1) {
                $result .= '<td>Approved</td>'.'<br>';
            }
            else if ($g['status'] == 0) {
                $result .= '<td id="game-status-'.$g['id'].'">'
                                .'<button class="approve-btn" onclick="PopUp(1, '.$g['id'].')">Approve</button>'
                                .'<button class="reject-btn" onclick="PopUp(-1, '.$g['id'].')">Reject</button>'
                            .'</td>'.'<br>';
            }
            $result .= '</tr>';
            $ctr++;
        }
        return $result;
    }

    function approveGame($id) {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] != env('ADMIN_USERNAME'))) {
            return back();
        }
        $approvedGame = Games::find($id);
        if ($approvedGame) {
            if ($approvedGame->status == 0) {
                $approvedGame->status = 1;
                $approvedGame->save();
            }
        }
        return redirect('/admin');
    }

    function rejectGame($id) {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] != env('ADMIN_USERNAME'))) {
            return back();
        }
        $rejectedGame = Games::find($id);
        if ($rejectedGame) {
            if ($rejectedGame->status == 0) {
                $rejectedGame->status = -1;
                $rejectedGame->save();
            }
        }
        return redirect('/admin');
    }

    function searchUsers() {
        $userIdentity = '';
        $userStatus = 2;
        if (isset($_GET['identity'])) {
            $userIdentity = $_GET['identity'];
        }
        if (isset($_GET['status'])) {
            $userStatus = intval($_GET['status']);
        }
        $users = null;
        if ($userStatus == 0 || $userStatus == 1) {
            $users = Users::where(function ($query) use ($userIdentity) {
                $query->where('name', 'like', '%'.$userIdentity.'%')
                      ->orWhere('username', 'like', '%'.$userIdentity.'%')
                      ->orWhere('email', 'like', '%'.$userIdentity.'%');
            })->where('blocked', '=', $userStatus)
            ->get();
        }
        else {
            $users = Users::where('name', 'like', '%'.$userIdentity.'%')->orWhere('username', 'like', '%'.$userIdentity.'%')->orWhere('email', 'like', '%'.$userIdentity.'%')->get();
        }
        $result = '';
        $ctr = 1;
        foreach ($users as $u) {
            $result .= '<tr>'.'<br>';
            $result .= '<td>'.$ctr.'</td>'.'<br>'
                        .'<td>'.$u['name'].'</td>'.'<br>'
                        .'<td><a href="/admin/userGames/'.$u['id'].'">'.$u['username'].'</a></td>'.'<br>'
                        .'<td><a href="/admin/userGames/'.$u['id'].'">'.$u['email'].'</a></td>'.'<br>';
            if ($u['blocked'] == 0) {
                $result .= '<td id="user-status-'.$u['id'].'">Active</td>'.'<br>'
                            .'<td>'
                                .'<button id="toggle-user-status-btn-'.$u['id'].'" class="toggle-user-status-btn" onclick="PopUp('.$u['id'].')">Block</button>'
                            .'</td>';
            }
            else if ($u['blocked'] == 1) {
                $result .= '<td id="user-status-'.$u['id'].'">Blocked</td>'.'<br>'
                            .'<td>'
                                .'<button id="toggle-user-status-btn-'.$u['id'].'" class="toggle-user-status-btn" onclick="PopUp('.$u['id'].')">Unblock</button>'
                            .'</td>';
            }
            $result .= '</tr>';
            $ctr++;
        }
        return $result;
    }

    function unblockUser($id) {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] != env('ADMIN_USERNAME'))) {
            return back();
        }
        $unblockedUser = Users::find($id);
        if ($unblockedUser) {
            if ($unblockedUser->blocked == 1) {
                $unblockedUser->blocked = 0;
                $unblockedUser->save();
            }
        }
        return redirect('/admin/users');
    }

    function blockUser($id) {
        $user = session('loggedIn');
        if ($user == null || ($user && $user['username'] != env('ADMIN_USERNAME'))) {
            return back();
        }
        $blockedUser = Users::find($id);
        if ($blockedUser) {
            if ($blockedUser->blocked == 0) {
                $blockedUser->blocked = 1;
                $blockedUser->save();
            }
        }
        return redirect('/admin/users');
    }

    function userGamesSearch($id) {
        $gameName = '';
        $gameStatus = 2;
        if (isset($_GET['name'])) {
            $gameName = $_GET['name'];
        }
        if (isset($_GET['status'])) {
            $gameStatus = intval($_GET['status']);
        }
        if ($gameStatus >= -1 && $gameStatus <= 1) {
            $games = Games::where('user_id', '=', $id)->where('name', 'like', '%'.$gameName.'%')->where('status', '=', $gameStatus)->get();
        }
        else {
            $games = Games::where('user_id', '=', $id)->where('name', 'like', '%'.$gameName.'%')->get();
        }
        $result = '';
        $ctr = 1;
        foreach ($games as $g) {
            $result .= '<tr>'.'<br>';
            $result .= '<td>'.$ctr.'</td>'.'<br>'
                        .'<td>'.$g['name'].'</td>'.'<br>'
                        .'<td>'.'<img src="/storage/images/'.$g['image'].'"></td>'.'<br>'
                        .'<td>'.'<a href="/storage/games/'.$g['file'].'">'.$g['file'].'</a></td>'.'<br>';
            if ($g['status'] == -1) {
                $result .= '<td>Rejected</td>'.'<br>';
            }
            else if ($g['status'] == 1) {
                $result .= '<td>Approved</td>'.'<br>';
            }
            else {
                $result .= '<td id="game-status-'.$g['id'].'">'
                                .'<button class="approve-btn" onclick="PopUp(1, '.$g['id'].')">Approve</button>'
                                .'<button class="reject-btn" onclick="PopUp(-1, '.$g['id'].')">Reject</button>'
                            .'</td>'.'<br>';
            }
            $result .= '</tr>';
            $ctr++;
        }
        return $result;
    }
}

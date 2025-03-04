<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\Users;
use App\Rules\FileNameMaxLength;
use App\Rules\NoSpaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    function changePassword(Request $request) {
        $rules = [
            'new_password' => ['required', new NoSpaces('New password'), 'min:8', 'max:40']
        ];
        $messages = [
            'new_password.required' => 'New password is required',
            'new_password.min' => 'New password must be at least 8 characters long',
            'new_password.max' => 'New password must be at most 40 characters long'
        ];
        $request->validate($rules, $messages);
        $loggedIn = session('loggedIn');
        $loggedIn->password = bcrypt($request->new_password);
        $user = Users::where('username', '=', $loggedIn->username)->first();
        $user->password = bcrypt($request->new_password);
        $user->save();
        return redirect('/account');
    }

    function searchGames() {
        $gameName = '';
        if (isset($_GET['name'])) {
            $gameName = $_GET['name'];
        }
        $approvedGames = Games::where('name', 'like', '%'.$gameName.'%')->where('status', 1)->get();
        $gamesCnt = count($approvedGames);
        $rowCnt = ceil($gamesCnt / 4);
        $result = '';
        for ($i = 0; $i < $rowCnt; $i++) {
            $result .= '<tr>';
            for ($j = 0; $j < 4; $j++) {
                $index = $i * 4 + $j;
                if ($index <= $gamesCnt - 1) {
                    $ag = $approvedGames[$index];
                    $result .= '<td>'
                                    .'<div>'
                                        .'<img src="/storage/images/'.$ag['image'].'"/>'
                                        .'<div><a href="/storage/games/'.$ag['file'].'">'.$ag['name'].'</a></div>'
                                    .'</div>'
                                .'</td>';
                }
                else {
                    $result .= '<td></td>';
                }
            }
            $result .= '</tr>';
        }
        return $result;
    }

    function searchUserGames() {
        $loggedIn = session('loggedIn');
        $gameName = '';
        $gameStatus = 2;
        if (isset($_GET['name'])) {
            $gameName = $_GET['name'];
        }
        if (isset($_GET['status'])) {
            $gameStatus = intval($_GET['status']);
        }
        if ($gameStatus >= -1 && $gameStatus <= 1) {
            $games = Games::where('user_id', '=', $loggedIn->id)->where('name', 'like', '%'.$gameName.'%')->where('status', '=', $gameStatus)->get();
        }
        else {
            $games = Games::where('user_id', '=', $loggedIn->id)->where('name', 'like', '%'.$gameName.'%')->get();
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
                $result .= '<td>Pending</td>'.'<br>';
            }
            $result .= '</tr>';
            $ctr++;
        }
        return $result;
    }

    function deleteAccount(Request $request) {
        $loggedIn = session('loggedIn');
        Users::where('username', '=', $loggedIn->username)->delete();
        $request->session()->forget('loggedIn');
        return redirect('/');
    }

    function doUploadGame(Request $request) {
        $rules = [
            'name' => 'required|max:20',
            'image' => ['required', 'image', 'max:1024', new FileNameMaxLength('Image', 30)],
            'file' => ['required', 'mimetypes:text/html', 'max:10240', new FileNameMaxLength('File', 30)]
        ];
        $messages = [
            'name.required' => 'Name is required',
            'name.max' => 'Name must be at most 20 characters long',
            'image.required' => 'Image is required',
            'image.image' => 'Image must be an image file',
            'image.max' => 'Image size must be at most 1 MB',
            'file.required' => 'File is required',
            'file.mimetypes' => 'File must be an HTML file',
            'file.max' => 'File size must be at most 10 MB'
        ];
        $request->validate($rules, $messages);
        $name = $request->name;
        $image = $request->file('image')->getClientOriginalName();
        $file = $request->file('file')->getClientOriginalName();
        $loggedIn = session('loggedIn');
        $userId = Users::where('username', '=', $loggedIn->username)->first()->id;
        Games::create([
            'name' => $name,
            'image' => $image,
            'file' => $file,
            'status' => 0,
            'user_id' => $userId
        ]);
        Storage::disk('public')->putFileAs('/images', $request->file('image'), $image);
        Storage::disk('public')->putFileAs('/games', $request->file('file'), $file);
        session()->flash('success', 'Game uploaded successfully !');
        return redirect('/uploadGame');
    }
}

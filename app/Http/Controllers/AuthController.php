<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\Users;
use App\Rules\AccountActive;
use App\Rules\CodeMatch;
use App\Rules\EmailExists;
use App\Rules\EmailNotExists;
use App\Rules\NoSpaces;
use App\Rules\PasswordCorrect;
use App\Rules\UserExists;
use App\Rules\UserNotExists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    function doRegister(Request $request) {
        $rules = [
            'name' => 'required|max:30',
            'username' => ['required', new NoSpaces('Username'), 'max:20', new UserNotExists()],
            'email' => ['required', 'email:rfc,dns', 'max:35', new EmailNotExists()],
            'password' => ['required', new NoSpaces('Password'), 'min:8', 'max:40']
        ];
        $messages = [
            'name.required' => 'Name is required',
            'name.max' => 'Name must be at most 30 characters long',
            'username.required' => 'Username is required',
            'username.max' => 'Username must be at most 20 characters long',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email',
            'email.max' => 'Email must  be at most 35 characters long',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'password.max' => 'Password must be at most 40 characters long'
        ];
        $request->validate($rules, $messages);
        $user = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'blocked' => 0
        ];
        $request->session()->put('user', $user);
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $digit = rand(0, 9);
            $code .= $digit;
        }
        Mail::to($request->email)->send(new VerificationEmail($code));
        $request->session()->put('code', $code);
        return redirect('/verify');
    }

    function doVerify(Request $request) {
        $code = session('code');
        $rules = [
            'code' => ['required', 'numeric', 'digits:4', new CodeMatch($code)]
        ];
        $messages = [
            'code.required' => 'Code is required',
            'code.digits' => 'Invalid code'
        ];
        $request->validate($rules, $messages);
        $user = session('user');
        $tempUser = Users::where('username', '=', $user['username'])->first();
        if ($tempUser == null) {
            $loggedIn = Users::create([
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password'],
                'blocked' => 0
            ]);
            $request->session()->put('loggedIn', $loggedIn);
            $request->session()->forget('user');
            $request->session()->forget('code');
            return '/';
        }
        else {
            $request->session()->forget('code');
            return '/changePassword';
        }
        return '';
    }

    function doLogin(Request $request) {
        $identifier = $request->identifier;
        $password = $request->password;
        $admin_name = env('ADMIN_NAME');
        $admin_username = env('ADMIN_USERNAME');
        $admin_email = env('ADMIN_EMAIL');
        $admin_password = env('ADMIN_PASSWORD');
        if (($identifier == $admin_username|| $identifier == $admin_email) && $password == $admin_password) {
            $admin = [
                'name' => $admin_name,
                'username' => $admin_username,
                'email' => $admin_email
            ];
            $request->session()->put('loggedIn', $admin);
            return '/admin';
        }
        $rules = [
            'identifier' => ['required', new UserExists(), new AccountActive()],
            'password' => ['required', new PasswordCorrect($identifier)]
        ];
        $messages = [
            'identifier.required' => 'Email/Username is required',
            'password.required' => 'Password is required'
        ];
        $request->validate($rules, $messages);
        $user = Users::where('username', '=', $identifier)->orWhere('email', '=', $identifier)->first();
        $request->session()->put('loggedIn', $user);
        return '/';
    }

    function checkEmail(Request $request) {
        $rules = [
            'email' => ['required', 'email', new EmailExists()],
        ];
        $messages = [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email'
        ];
        $request->validate($rules, $messages);
        $user = Users::where('email', '=', $request->email)->first();
        $request->session()->put('user', $user);
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $digit = rand(0, 9);
            $code .= $digit;
        }
        Mail::to($request->email)->send(new VerificationEmail($code));
        $request->session()->put('code', $code);
        return redirect('/verify');
    }

    function doChangePassword(Request $request) {
        $rules = [
            'new_password' => ['required', new NoSpaces('New password'), 'min:8', 'max:40']
        ];
        $messages = [
            'new_password.required' => 'New password is required',
            'new_password.min' => 'New password must be at least 8 characters long',
            'new_password.max' => 'New password must be at most 40 characters long'
        ];
        $request->validate($rules, $messages);
        $user = session('user');
        $updatedUser = Users::find($user->id);
        $updatedUser->password = bcrypt($request->new_password);
        $updatedUser->save();
        $request->session()->forget('user');
        return redirect('/login');
    }

    function doLogout(Request $request) {
        $request->session()->forget('loggedIn');
        return redirect('/');
    }
}

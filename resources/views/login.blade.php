@extends('main')
@section('title', 'CodaGames - Login')
@section('back')
    <div class="return">
        <a href="/" class="back">Back</a>
    </div>
@endsection
@section('content')
    @if (session('succcess'))
        <script>alert('Password successfully changed !')</script>
    @endif
    <div class="container" style="height:410px;margin-top:7.9%">
        <div class="form-outer" style="margin-top:6%">
            <div class="login-title">Login</div>
            <br>
            <form class="login-form" method="post" action="/doLogin">
                @csrf
                Email/Username: <input type="text" id="identifier" name="identifier" value="" placeholder="Email/Username">
                <div id="identifier-error"></div>
                Password: <input type="password" id="password" name="password" placeholder="Password">
                <div id="password-error"></div>
                <a class="forgot-password" href="/forgotPassword">Forgot password</a>
                <br><br>
                <input type="submit" id="login-btn" value="Login">
            </form>
        </div>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        $('.login-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '/doLogin',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(url) {
                    window.location = url;
                },
                error: function(error) {
                    $('#password').val('');
                    $('#identifier-error').text('');
                    $('#password-error').text('');
                    let response = $.parseJSON(error.responseText);
                    for (let key in response.errors) {
                        $(`#${key}-error`).text(response.errors[key][0]);
                    }
                }
            });
        });
    </script>
@endsection
@section('footer')
    <div class="footer" style="margin-top:40%">
        <div class="copyright">&copy; CodaGames</div>
    </div>
@endsection

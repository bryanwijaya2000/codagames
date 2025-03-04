@extends('main')
@section('title', 'CodaGames - Register')
@section('back')
    <div class="return">
        <a href="/" class="back">Back</a>
    </div>
@endsection
@section('content')
    <div class="container" style="height:410px;margin-top:7.9%">
        <div class="form-outer" style="margin-top:2%">
            <div class="register-title">Register</div>
            <br>
            <form class="register-form" method="post" action="/doRegister">
                @csrf
                Name: <input type="text" id="name" name="name" value="" placeholder="Name">
                <div id="name-error"></div>
                Username: <input type="text" id="username" name="username" value="" placeholder="Username">
                <div id="username-error"></div>
                Email: <input type="text" id="email" name="email" value="" placeholder="Email">
                <div id="email-error"></div>
                Password: <input type="password" id="password" name="password" placeholder="Password">
                <div id="password-error"></div>
                <br>
                <input type="submit" id="register-btn" value="Register">
            </form>
        </div>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        $('.register-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '/doRegister',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function() {
                    window.location = '/verify';
                },
                error: function(error) {
                    $('#password').val('');
                    $('#name-error').text('');
                    $('#username-error').text('');
                    $('#email-error').text('');
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

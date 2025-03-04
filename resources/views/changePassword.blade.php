@extends('main')
@section('title', 'CodaGames - Change Password')
@section('back')
    <div class="return">
        <a href="/" class="back">Back</a>
    </div>
@endsection
@section('content')
    <div class="container" style="height:410px;margin-top:7.9%">
        <div class="form-outer" style="height:16%;margin-top:9%">
            <div class="change-password-title">Change Password</div>
            <br>
            <form class="change-password-form" method="post" action="/doChangePassword">
                @csrf
                Enter new password: <input type="password" id="new_password" name="new_password" placeholder="New password" autofocus>
                <div id="new_password-error"></div>
                <br>
                <input type="submit" id="change-password-btn" value="Change Password">
            </form>
        </div>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        $('.change-password-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '/doChangePassword',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(url) {
                    window.location = '/login';
                },
                error: function(error) {
                    $('#new_password').val('');
                    $('#new_password-error').text('');
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

@extends('template')
@section('title', 'CodaGames - Account')
@section('back')
    <div class="return">
        <a href="/" class="back">Back</a>
    </div>
@endsection
@section('content')
    <?php
        $loggedIn = session('loggedIn');
        $user = App\Models\Users::find($loggedIn['id']);
    ?>
    <div class="blocked" style="display:none">
        <input id="blocked" type="hidden" value="{{ $user->blocked }}">
    </div>
    <div class="container" style="height:530px;margin-top:9.7%;overflow-y:scroll">
        <div class="credentials-title" style="height:1%">Credentials</div>
        <div class="credentials-name">Name: {{ $loggedIn['name'] }}</div>
        <div class="credentials-username">Username: {{ $loggedIn['username']}}</div>
        <div class="credentials-email">Email: {{ $loggedIn['email'] }}</div>
        <br>
        <div class="form-outer" style="height:12%;margin-top:2%;margin-left:41%">
            <div class="change-password-title" style="height:1%;text-align:left">Change Password</div>
            <br>
            <form class="change-password-form" method="post" action="/changePassword">
                @csrf
                New password: <input type="password" id="new_password" name="new_password" placeholder="New password">
                <div id="new_password-error"></div>
                <br>
                <input type="submit" id="change-password-btn" value="Change Password">
            </form>
        </div>
        <br>
        <div class="delete-account-title" style="height:3%;margin-top:7%">Delete Account</div>
        <br>
        <button class="delete-account-btn" onclick="PopUp()">Delete Account</button>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        let blocked = $('.blocked');
        if (blocked) {
            setInterval(() => {
                $('.blocked').load(`${window.location.href} .blocked > *`);
                let blockedVal = parseInt($('#blocked').val());
                if (blockedVal == 1) {
                    window.location.href = '/doLogout';
                    alert('You have been blocked');
                }
            }, 2000);
        }
        function PopUp() {
            if (confirm('Are you sure you want to delete your account?')) {
                window.location = '/deleteAccount';
            }
        }
        $('.change-password-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '/changePassword',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function() {
                    $('#new_password').val('');
                    $('#new_password-error').text('');
                    alert('Password changed successfully !');
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
    <div class="footer" style="margin-top:51%">
        <div class="copyright">&copy; CodaGames</div>
    </div>
@endsection

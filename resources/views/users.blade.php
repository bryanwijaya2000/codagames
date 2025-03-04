@extends('template')
@section('title', 'CodaGames - Users')
@section('content')
    <div class="container" style="height:952px;margin-top:9.7%;overflow-y:scroll">
        <?php
            $loggedIn = session('loggedIn');
        ?>
        <div class="welcome-admin" style="height:1%;text-align:left">Welcome, {{ $loggedIn['name'] }} !</div>
        <div class="users-title" style="height:5%">Users</div>
        <br>
        <div class="form-outer" style="width:100%;height:5%;margin-top:0%;margin-left:0%">
            <form class="search-users-form" method="get" action="/admin/searchUsers">
                <input type="text" id="user-identity" value="" placeholder="Search users...">
                <select id="user-status">
                    @if (isset($_GET['status']))
                        @if ($_GET['status'] == '0')
                            <option value="2">All</option>
                            <option value="1">Blocked</option>
                            <option value="0" selected>Active</option>
                        @elseif ($_GET['status'] == '1')
                            <option value="2">All</option>
                            <option value="1" selected>Blocked</option>
                            <option value="0">Active</option>
                        @endif
                    @else
                        <option value="2" selected>All</option>
                        <option value="1">Blocked</option>
                        <option value="0">Active</option>
                    @endif
                </select>
                <input type="submit" id="search-users-btn" value="Search">
            </form>
        </div>
        <table class="users-list">
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Block/Unblock</th>
            </tr>
            <tbody id="users">
                <?php
                    $ctr = 1;
                ?>
                @foreach ($users as $u)
                    <tr>
                        <td>{{ $ctr }}</td>
                        <td>{{ $u['name'] }}</td>
                        <td><a id="username-{{ $u['id'] }}" href="/admin/userGames/{{ $u['id'] }}">{{ $u['username'] }}</a></td>
                        <td><a href="/admin/userGames/{{ $u['id'] }}">{{ $u['email'] }}</a></td>
                        @if ($u['blocked'] == 0)
                            <td id="user-status-{{ $u['id'] }}">Active</td>
                            <td>
                                <button id="toggle-user-status-btn-{{ $u['id'] }}" class="toggle-user-status-btn" onclick="PopUp({{ $u['id'] }})">Block</button>
                            </td>
                        @elseif ($u['blocked'] == 1)
                            <td id="user-status-{{ $u['id'] }}">Blocked</td>
                            <td>
                                <button id="toggle-user-status-btn-{{ $u['id'] }}" class="toggle-user-status-btn" onclick="PopUp({{ $u['id'] }})">Unblock</button>
                            </td>
                        @endif
                    </tr>
                    <?php
                        $ctr++;
                    ?>
                @endforeach
            </div>
        </table>
        <br>
    </div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        function PopUp(id) {
            let text = $(`#toggle-user-status-btn-${id}`).text();
            if (text == 'Unblock') {
                let username = $(`#username-${id}`).text();
                if (confirm(`Are you sure you want to unblock user ${username}?`)) {
                    $.ajax({
                        url: `/admin/unblockUser/${id}`,
                        success: function () {
                            $(`#user-status-${id}`).text('Active');
                            $(`#toggle-user-status-btn-${id}`).text('Block');
                        }
                    });
                }
            }
            else if (text == 'Block') {
                let username = $(`#username-${id}`).text();
                if (confirm(`Are you sure you want to block user ${username}?`)) {
                    $.ajax({
                        url: `/admin/blockUser/${id}`,
                        success: function () {
                            $(`#user-status-${id}`).text('Blocked');
                            $(`#toggle-user-status-btn-${id}`).text('Unblock');
                        }
                    });
                }
            }
        }
        $('.search-users-form').submit(function(e) {
            e.preventDefault();
            let userIdentity = $('#user-identity').val();
            let userStatus = parseInt($('#user-status').val());
            let url = `/admin/searchUsers?identity=${userIdentity}`;
            if (userStatus == 0 || userStatus == 1) {
                url += `&status=${userStatus}`;
            }
            $.ajax({
                url: url,
                success: function (result) {
                    $('#users').html(result);
                }
            });
        });
    </script>
@endsection
@section('footer')
    <div class="footer" style="margin-top:85%">
        <div class="copyright">&copy; CodaGames</div>
    </div>
@endsection

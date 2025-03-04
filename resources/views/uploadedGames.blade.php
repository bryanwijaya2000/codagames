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
    <div class="container" style="height:975px;margin-top:9.7%;overflow-y:scroll">
        <div class="uploaded-games-title" style="height:5%">Uploaded Games</div>
        <div class="form-outer" style="width:100%;height:5%;margin-top:0%;margin-left:0%">
            <form class="search-games-form" method="get" action="/account/searchGames">
                <input type="text" id="game-name" value="" placeholder="Search games...">
                <select id="game-status">
                    @if (isset($_GET['status']))
                        @if ($_GET['status'] == '0')
                            <option value="2">All</option>
                            <option value="0" selected>Pending</option>
                            <option value="-1">Rejected</option>
                            <option value="1">Approved</option>
                        @elseif ($_GET['status'] == '-1')
                            <option value="2">All</option>
                            <option value="0">Pending</option>
                            <option value="-1" selected>Rejected</option>
                            <option value="1">Approved</option>
                        @elseif ($_GET['status'] == '1')
                            <option value="2">All</option>
                            <option value="0">Pending</option>
                            <option value="-1">Rejected</option>
                            <option value="1" selected>Approved</option>
                        @endif
                    @else
                        <option value="2" selected>All</option>
                        <option value="0">Pending</option>
                        <option value="-1">Rejected</option>
                        <option value="1">Approved</option>
                    @endif
                </select>
                <input type="submit" id="search-game-btn" value="Search">
            </form>
        </div>
        <table class="uploaded-games-list">
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Image</th>
                <th>File</th>
                <th>Status</th>
            </tr>
            <tbody id="uploaded-games">
                <?php
                    $ctr = 1;
                ?>
                @foreach ($userGames as $ug)
                    <tr>
                        <td>{{ $ctr }}</td>
                        <td>{{ $ug['name'] }}</td>
                        <td>
                            <img src="{{ asset('storage/images') }}/{{ $ug['image'] }}">
                        </td>
                        <td>
                            <a href="{{ asset('storage/games') }}/{{ $ug['file'] }}">{{ $ug['file'] }}</a>
                        </td>
                        <?php
                            $status = 'Pending';
                            if ($ug['status'] == -1) {
                                $status = "Rejected";
                            }
                            else if ($ug['status'] == 1) {
                                $status = "Approved";
                            }
                        ?>
                        <td>{{ $status }}</td>
                    </tr>
                    <?php
                        $ctr++;
                    ?>
                @endforeach
            </tbody>
        </table>
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
        let currentUrl = '/uploadedGames/searchGames';
        setInterval(() => {
            $.ajax({
                url: currentUrl,
                success: function (result) {
                    $('#uploaded-games').html(result);
                }
            });
        }, 1000);
        $('.search-games-form').submit(function(e) {
            e.preventDefault();
            let gameName = $('#game-name').val();
            let gameStatus = $('#game-status').val();
            let url = `/uploadedGames/searchGames?name=${gameName}`;
            if (gameStatus >= -1 && gameStatus <= 1) {
                url += `&status=${gameStatus}`;
            }
            $.ajax({
                url: url,
                success: function (result) {
                    currentUrl = url;
                    $('#uploaded-games').html(result);
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

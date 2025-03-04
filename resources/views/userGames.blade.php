@extends('main')
@section('title', 'CodaGames - User Games')
@section('back')
    <div class="return">
        <a href="/admin" class="back">Back</a>
    </div>
@endsection
@section('content')
    <div class="container" style="height:975px;margin-top:7.9%;overflow-y:scroll">
        <div class="uploaded-games-title" style="height:5%">Uploaded Games By {{ $byUser['username'] }}</div>
        <input type="hidden" id="user-id" value="{{ $byUser['id'] }}">
        <br>
        <div class="form-outer" style="width:100%;height:5%;margin-top:0%;margin-left:0%">
            <form class="search-games-form" method="get" action="/admin/userGames/{{ $byUser['id'] }}/searchGames">
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
                        <td id="game-name-{{ $ug['id'] }}">{{ $ug['name'] }}</td>
                        <td>
                            <img src="{{ asset('storage/images') }}/{{ $ug['image'] }}">
                        </td>
                        <td>
                            <a href="{{ asset('storage/games') }}/{{ $ug['file'] }}">{{ $ug['file'] }}</a>
                        </td>
                        @if ($ug['status'] == -1)
                            <td>Rejected</td>
                        @elseif ($ug['status'] == 1)
                            <td>Approved</td>
                        @else
                            <td id="game-status-{{ $ug['id'] }}">
                                <button class="approve-btn" onclick="PopUp(1, {{ $ug['id'] }})">Approve</button>
                                <button class="reject-btn" onclick="PopUp(-1, {{ $ug['id'] }})">Reject</button>
                            </td>
                        @endif
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
        function PopUp(p, id) {
            if (p == 1) {
                let gameName = $(`#game-name-${id}`).text();
                if (confirm(`Are you sure you want to approve game ${gameName}?`)) {
                    $.ajax({
                        url: `/admin/approveGame/${id}`,
                        success: function () {
                            $(`#game-status-${id}`).text('Approved');
                            $(`#game-status-${id}`).removeAttr('id');
                        }
                    });
                }
            }
            else if (p == -1) {
                let gameName = $(`#game-name-${id}`).text();
                if (confirm(`Are you sure you want to reject game ${gameName}?`)) {
                    $.ajax({
                        url: `/admin/rejectGame/${id}`,
                        success: function () {
                            $(`#game-status-${id}`).text('Rejected');
                            $(`#game-status-${id}`).removeAttr('id');
                        }
                    });
                }
            }
        }
        $('.search-games-form').submit(function(e) {
            e.preventDefault();
            let gameName = $('#game-name').val();
            let gameStatus = $('#game-status').val();
            let userId = $('#user-id').val();
            let url = `/admin/userGames/${userId}/searchGames?name=${gameName}`;
            if (gameStatus >= -1 && gameStatus <= 1) {
                url += `&status=${gameStatus}`;
            }
            $.ajax({
                url: url,
                success: function (result) {
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

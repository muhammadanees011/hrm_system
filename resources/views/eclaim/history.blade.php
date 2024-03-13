<div class="modal-body">
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Time</th>
                    <th>Message</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($history))
                    @foreach($history as $row)
                        <tr>
                            <td>{{$row['username']}}</td>
                            <td>{{ !empty($row['time']) ? \Auth::user()->dateFormat($row['time']):'' }}</td>
                            <td>{{ $row['message'] ?? '' }}</td>
                            <td>{{ $row['comment'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4"><strong>No Record Found!</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

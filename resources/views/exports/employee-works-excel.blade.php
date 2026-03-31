<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Work</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Start Date</th>
            <th>Completed Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($works as $ws)
            <tr>
                <td>{{ $ws['title'] }}</td>
                <td>{{ $ws['work'] }}</td>
                <td>{{ $ws['status'] }}</td>
                <td>{{ $ws['remarks'] }}</td>
                <td>{{ $ws['start_date'] }}</td>
                <td>{{ $ws['completed_on'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table border="1">
    <thead>
        <tr>
            @foreach ($selectedColumns as $col)
                <th>{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($worksheets as $ws)
            <tr>
                @foreach ($selectedColumns as $col)
                    <td>{{ $ws[$col] ?? '' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

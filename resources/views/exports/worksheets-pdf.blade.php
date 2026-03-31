<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worksheet Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Worksheet Report</h2>
    <table>
        <thead>
            <tr>
                @foreach($selectedColumns as $col)
                    <th>{{ $availableColumns[$col] ?? ucfirst($col) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($worksheets as $ws)
                <tr>
                    @foreach($selectedColumns as $col)
                        <td>{!! nl2br(e($ws[$col] ?? '')) !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

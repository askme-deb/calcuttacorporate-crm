<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Title</th>
            <th>Client</th>
            <th>Work</th>
            <th>Priority</th>
            <th>Start Date</th>
            <th>Completed Tasks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
        <tr>
            <td>{{ $project->title }}</td>
            <td>{{ $project->client->name ?? '-' }}</td>
            <td>{{ $project->work->name ?? '-' }}</td>
            <td>{{ $project->priorty->name ?? '-' }}</td>
            <td>{{ $project->start_date }}</td>
            <td>{{ $project->completed_tasks_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="table-wrap">
    <table>
        <thead><tr><th>Reference</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th><th>Updated</th></tr></thead>
        <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td><a class="table-link" href="{{ route('tickets.show', $ticket) }}">{{ $ticket->reference }}</a></td>
                <td>{{ $ticket->subject }}</td>
                <td>{{ $ticket->category->name }}</td>
                <td><span class="badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span></td>
                <td><span class="badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span></td>
                <td>{{ $ticket->updated_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

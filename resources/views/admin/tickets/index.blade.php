@extends('layouts.app')

@section('title', 'Ticket queue | Campus IT Help Desk')

@section('content')
<section class="container page">
    <div class="page-heading"><p class="eyebrow">Administration</p><h1>Ticket queue</h1><p>Search, filter, assign, and resolve support requests.</p></div>
    <form method="GET" class="filter-bar admin-filter">
        <label class="grow"><span class="sr-only">Search</span><input type="search" name="search" value="{{ request('search') }}" placeholder="Reference, subject, or description"></label>
        <select name="status" aria-label="Status"><option value="">All statuses</option>@foreach(\App\Models\Ticket::STATUSES as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>@endforeach</select>
        <select name="priority" aria-label="Priority"><option value="">All priorities</option>@foreach(\App\Models\Ticket::PRIORITIES as $priority)<option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select>
        <select name="category" aria-label="Category"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string)request('category') === (string)$category->id)>{{ $category->name }}</option>@endforeach</select>
        <label class="checkbox-row"><input type="checkbox" name="unassigned" value="1" @checked(request()->boolean('unassigned'))><span>Unassigned</span></label>
        <button class="button button-secondary" type="submit">Apply</button>
        @if(request()->query())<a class="text-button" href="{{ route('admin.tickets.index') }}">Clear</a>@endif
    </form>
    <div class="panel">
        @if($tickets->isEmpty())
            <div class="empty-state"><h2>No matching tickets</h2><p>Change the filters to see more of the queue.</p></div>
        @else
            <div class="table-wrap"><table>
                <thead><tr><th>Reference</th><th>Submitted by</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th><th>Assigned</th></tr></thead>
                <tbody>@foreach($tickets as $ticket)<tr>
                    <td><a class="table-link" href="{{ route('admin.tickets.show', $ticket) }}">{{ $ticket->reference }}</a></td>
                    <td>{{ $ticket->user->name }}</td><td>{{ $ticket->subject }}</td><td>{{ $ticket->category->name }}</td>
                    <td><span class="badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span></td>
                    <td><span class="badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span></td>
                    <td>{{ $ticket->assignee?->name ?? '—' }}</td>
                </tr>@endforeach</tbody>
            </table></div>
            <div class="pagination">{{ $tickets->links() }}</div>
        @endif
    </div>
</section>
@endsection

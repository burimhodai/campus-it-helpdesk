@extends('layouts.app')

@section('title', 'Admin overview | Campus IT Help Desk')

@section('content')
<section class="container page">
    <div class="page-heading split-heading">
        <div><p class="eyebrow">Administration</p><h1>Support overview</h1><p>Monitor the queue and focus on requests that need attention.</p></div>
        <a class="button" href="{{ route('admin.tickets.index', ['unassigned' => 1]) }}">Review unassigned</a>
    </div>
    <div class="stat-grid admin-stats">
        <div class="stat"><span>New tickets</span><strong>{{ $counts['open'] }}</strong></div>
        <div class="stat"><span>Active</span><strong>{{ $counts['active'] }}</strong></div>
        <div class="stat"><span>Urgent</span><strong>{{ $counts['urgent'] }}</strong></div>
        <div class="stat"><span>Unassigned</span><strong>{{ $counts['unassigned'] }}</strong></div>
        <div class="stat"><span>Registered users</span><strong>{{ $counts['users'] }}</strong></div>
        <div class="stat"><span>Active categories</span><strong>{{ $counts['categories'] }}</strong></div>
    </div>
    <div class="panel">
        <div class="panel-heading"><div><h2>Newest tickets</h2><p>The latest requests across all users.</p></div><a href="{{ route('admin.tickets.index') }}">Open queue</a></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Reference</th><th>User</th><th>Subject</th><th>Priority</th><th>Status</th><th>Assigned to</th></tr></thead>
                <tbody>@foreach($recentTickets as $ticket)<tr>
                    <td><a class="table-link" href="{{ route('admin.tickets.show', $ticket) }}">{{ $ticket->reference }}</a></td>
                    <td>{{ $ticket->user->name }}</td><td>{{ $ticket->subject }}</td>
                    <td><span class="badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span></td>
                    <td><span class="badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span></td>
                    <td>{{ $ticket->assignee?->name ?? 'Unassigned' }}</td>
                </tr>@endforeach</tbody>
            </table>
        </div>
    </div>
</section>
@endsection

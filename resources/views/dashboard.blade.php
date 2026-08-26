@extends('layouts.app')

@section('title', 'Dashboard | Campus IT Help Desk')

@section('content')
<section class="container page">
    <div class="page-heading split-heading">
        <div><p class="eyebrow">User dashboard</p><h1>Welcome, {{ auth()->user()->name }}</h1><p>Review your support requests or report a new issue.</p></div>
        <a class="button" href="{{ route('tickets.create') }}">Create new ticket</a>
    </div>
    <div class="stat-grid">
        <div class="stat"><span>Total tickets</span><strong>{{ $counts['total'] }}</strong></div>
        <div class="stat"><span>New</span><strong>{{ $counts['open'] }}</strong></div>
        <div class="stat"><span>In progress</span><strong>{{ $counts['active'] }}</strong></div>
        <div class="stat"><span>Completed</span><strong>{{ $counts['resolved'] }}</strong></div>
    </div>
    <div class="panel">
        <div class="panel-heading"><div><h2>Recent tickets</h2><p>Your latest support activity.</p></div><a href="{{ route('tickets.index') }}">View all</a></div>
        @if($recentTickets->isEmpty())
            <div class="empty-state"><h3>No tickets yet</h3><p>When something goes wrong, create a ticket and IT support will pick it up.</p></div>
        @else
            @include('tickets.partials.table', ['tickets' => $recentTickets])
        @endif
    </div>
</section>
@endsection

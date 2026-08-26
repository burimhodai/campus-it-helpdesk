@extends('layouts.app')

@section('title', 'My tickets | Campus IT Help Desk')

@section('content')
<section class="container page">
    <div class="page-heading split-heading">
        <div><p class="eyebrow">Support requests</p><h1>My tickets</h1><p>Search your requests and check their current status.</p></div>
        <a class="button" href="{{ route('tickets.create') }}">Create new ticket</a>
    </div>
    <form method="GET" class="filter-bar">
        <label class="grow"><span class="sr-only">Search tickets</span><input type="search" name="search" value="{{ request('search') }}" placeholder="Search reference, subject, or description"></label>
        <label><span class="sr-only">Status</span><select name="status"><option value="">All statuses</option>@foreach(\App\Models\Ticket::STATUSES as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>@endforeach</select></label>
        <button class="button button-secondary" type="submit">Filter</button>
        @if(request()->hasAny(['search', 'status']))<a class="text-button" href="{{ route('tickets.index') }}">Clear</a>@endif
    </form>
    <div class="panel">
        @if($tickets->isEmpty())
            <div class="empty-state"><h2>No matching tickets</h2><p>Create a new request or change the filters.</p></div>
        @else
            @include('tickets.partials.table', ['tickets' => $tickets])
            <div class="pagination">{{ $tickets->links() }}</div>
        @endif
    </div>
</section>
@endsection

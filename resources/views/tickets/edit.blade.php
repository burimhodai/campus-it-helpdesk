@extends('layouts.app')

@section('title', 'Edit '.$ticket->reference.' | Campus IT Help Desk')

@section('content')
<section class="container page narrow-content">
    <div class="page-heading"><p class="eyebrow">{{ $ticket->reference }}</p><h1>Edit ticket</h1><p>You can edit a ticket only while it is new and unassigned.</p></div>
    <div class="panel form-panel">
        <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="stack-form">
            @csrf
            @method('PUT')
            @include('tickets.partials.form')
            <div class="form-actions"><a class="button button-secondary" href="{{ route('tickets.show', $ticket) }}">Cancel</a><button class="button" type="submit">Save changes</button></div>
        </form>
    </div>
</section>
@endsection

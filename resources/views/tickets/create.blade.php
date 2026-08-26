@extends('layouts.app')

@section('title', 'New ticket | Campus IT Help Desk')

@section('content')
<section class="container page narrow-content">
    <div class="page-heading"><p class="eyebrow">New support request</p><h1>Describe the problem</h1><p>Provide enough detail for IT support to understand and reproduce the issue.</p></div>
    <div class="panel form-panel">
        <form method="POST" action="{{ route('tickets.store') }}" class="stack-form">
            @csrf
            @include('tickets.partials.form')
            <div class="form-actions"><a class="button button-secondary" href="{{ route('tickets.index') }}">Cancel</a><button class="button" type="submit">Submit ticket</button></div>
        </form>
    </div>
</section>
@endsection

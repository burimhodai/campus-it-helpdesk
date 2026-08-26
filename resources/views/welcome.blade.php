@extends('layouts.app')

@section('title', 'Campus IT Help Desk')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow">Technical support, properly organised</p>
            <h1>Report an IT issue and follow it through to resolution.</h1>
            <p class="hero-copy">The help desk gives students and employees one place to submit problems, track progress, and communicate with IT support.</p>
            <div class="hero-actions">
                @auth
                    <a class="button" href="{{ route('dashboard') }}">Open dashboard</a>
                @else
                    <a class="button" href="{{ route('register') }}">Create an account</a>
                    <a class="button button-secondary" href="{{ route('login') }}">Sign in</a>
                @endauth
            </div>
        </div>
        <div class="hero-panel" aria-label="Support process">
            <div class="process-step"><span>1</span><div><strong>Describe the issue</strong><small>Add a category, priority, and clear details.</small></div></div>
            <div class="process-step"><span>2</span><div><strong>IT reviews the ticket</strong><small>An administrator assigns and updates the request.</small></div></div>
            <div class="process-step"><span>3</span><div><strong>Track the resolution</strong><small>Follow replies and status changes from your dashboard.</small></div></div>
        </div>
    </div>
</section>

<section class="container section">
    <div class="section-heading">
        <p class="eyebrow">What the system provides</p>
        <h2>A simple workflow for users and support staff</h2>
    </div>
    <div class="feature-grid">
        <article><h3>One support record</h3><p>Issue details, replies, assignment, priority, and status stay together instead of being scattered across email.</p></article>
        <article><h3>Clear ownership</h3><p>Users can only access their own tickets, while administrators can manage the complete support queue.</p></article>
        <article><h3>Useful filtering</h3><p>Support staff can search and filter tickets by status, priority, category, and assignment.</p></article>
    </div>
</section>
@endsection

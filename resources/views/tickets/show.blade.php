@extends('layouts.app')

@section('title', $ticket->reference.' | Campus IT Help Desk')

@section('content')
<section class="container page">
    <div class="page-heading split-heading">
        <div><p class="eyebrow">{{ $ticket->reference }}</p><h1>{{ $ticket->subject }}</h1><p>Opened {{ $ticket->created_at->format('d M Y, H:i') }} in {{ $ticket->category->name }}.</p></div>
        @if(!auth()->user()->isAdmin() && $ticket->isEditableByUser())<a class="button button-secondary" href="{{ route('tickets.edit', $ticket) }}">Edit details</a>@endif
    </div>
    <div class="detail-grid">
        <div class="main-column">
            <article class="panel ticket-description"><h2>Issue description</h2><p>{!! nl2br(e($ticket->description)) !!}</p></article>
            <div class="panel">
                <div class="panel-heading"><div><h2>Activity</h2><p>Messages and status changes for this ticket.</p></div></div>
                <div class="timeline">
                    @forelse($updates as $update)
                        <article class="timeline-item {{ $update->is_internal ? 'internal' : '' }}">
                            <div class="timeline-meta"><strong>{{ $update->user->name }}</strong><span>{{ $update->created_at->format('d M Y, H:i') }}</span></div>
                            @if($update->old_status && $update->new_status)<p class="status-change">Status changed from {{ str($update->old_status)->replace('_', ' ') }} to {{ str($update->new_status)->replace('_', ' ') }}.</p>@endif
                            @if($update->message)<p>{!! nl2br(e($update->message)) !!}</p>@endif
                            @if($update->is_internal)<span class="badge">Internal note</span>@endif
                        </article>
                    @empty
                        <p class="muted">No updates have been added.</p>
                    @endforelse
                </div>
                @if($ticket->status !== 'closed')
                    <form method="POST" action="{{ route('tickets.updates.store', $ticket) }}" class="reply-form">
                        @csrf
                        <label><span>Add a reply</span><textarea name="message" rows="4" maxlength="3000" required placeholder="Add useful information or answer a support question.">{{ old('message') }}</textarea></label>
                        <button class="button" type="submit">Post reply</button>
                    </form>
                @endif
            </div>
        </div>
        <aside class="panel ticket-sidebar">
            <h2>Ticket details</h2>
            <dl>
                <div><dt>Status</dt><dd><span class="badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span></dd></div>
                <div><dt>Priority</dt><dd><span class="badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span></dd></div>
                <div><dt>Category</dt><dd>{{ $ticket->category->name }}</dd></div>
                <div><dt>Assigned to</dt><dd>{{ $ticket->assignee?->name ?? 'Not assigned' }}</dd></div>
                <div><dt>Last updated</dt><dd>{{ $ticket->updated_at->format('d M Y, H:i') }}</dd></div>
            </dl>
        </aside>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('title', 'Manage '.$ticket->reference.' | Campus IT Help Desk')

@section('content')
<section class="container page">
    <div class="page-heading split-heading">
        <div><p class="eyebrow">{{ $ticket->reference }}</p><h1>{{ $ticket->subject }}</h1><p>Submitted by {{ $ticket->user->name }} on {{ $ticket->created_at->format('d M Y, H:i') }}.</p></div>
        <a class="button button-secondary" href="{{ route('admin.tickets.index') }}">Back to queue</a>
    </div>
    <div class="detail-grid admin-detail-grid">
        <div class="main-column">
            <article class="panel ticket-description"><h2>Issue description</h2><p>{!! nl2br(e($ticket->description)) !!}</p></article>
            <div class="panel">
                <div class="panel-heading"><div><h2>Complete activity</h2><p>User messages, internal notes, and status changes.</p></div></div>
                <div class="timeline">
                    @foreach($updates as $update)
                        <article class="timeline-item {{ $update->is_internal ? 'internal' : '' }}">
                            <div class="timeline-meta"><strong>{{ $update->user->name }}</strong><span>{{ $update->created_at->format('d M Y, H:i') }}</span></div>
                            @if($update->old_status && $update->new_status)<p class="status-change">Status changed from {{ str($update->old_status)->replace('_', ' ') }} to {{ str($update->new_status)->replace('_', ' ') }}.</p>@endif
                            @if($update->message)<p>{!! nl2br(e($update->message)) !!}</p>@endif
                            @if($update->is_internal)<span class="badge">Internal note</span>@endif
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
        <aside class="panel management-panel">
            <h2>Manage ticket</h2>
            <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="stack-form">
                @csrf @method('PUT')
                <label><span>Status</span><select name="status">@foreach(\App\Models\Ticket::STATUSES as $status)<option value="{{ $status }}" @selected(old('status', $ticket->status) === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>@endforeach</select></label>
                <label><span>Priority</span><select name="priority">@foreach(\App\Models\Ticket::PRIORITIES as $priority)<option value="{{ $priority }}" @selected(old('priority', $ticket->priority) === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select></label>
                <label><span>Assigned administrator</span><select name="assigned_to"><option value="">Unassigned</option>@foreach($admins as $admin)<option value="{{ $admin->id }}" @selected((string)old('assigned_to', $ticket->assigned_to) === (string)$admin->id)>{{ $admin->name }}</option>@endforeach</select></label>
                <label><span>Message or work note</span><textarea name="message" rows="5" maxlength="3000" placeholder="Optional update for this ticket">{{ old('message') }}</textarea></label>
                <label class="checkbox-row"><input type="checkbox" name="is_internal" value="1" @checked(old('is_internal'))><span>Internal note (hidden from user)</span></label>
                <button class="button" type="submit">Save ticket</button>
            </form>
            <dl class="sidebar-facts"><div><dt>Category</dt><dd>{{ $ticket->category->name }}</dd></div><div><dt>User email</dt><dd>{{ $ticket->user->email }}</dd></div><div><dt>Last updated</dt><dd>{{ $ticket->updated_at->format('d M Y, H:i') }}</dd></div></dl>
        </aside>
    </div>
</section>
@endsection

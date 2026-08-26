@extends('layouts.app')

@section('title', 'Categories | Campus IT Help Desk')

@section('content')
<section class="container page">
    <div class="page-heading"><p class="eyebrow">Administration</p><h1>Ticket categories</h1><p>Keep the list short and useful so users can classify issues correctly.</p></div>
    <div class="category-layout">
        <div class="panel form-panel compact-form">
            <h2>Add category</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="stack-form">@csrf
                <label><span>Name</span><input type="text" name="name" maxlength="80" value="{{ old('name') }}" required></label>
                <label><span>Description</span><textarea name="description" rows="4" maxlength="255">{{ old('description') }}</textarea></label>
                <button class="button" type="submit">Add category</button>
            </form>
        </div>
        <div class="category-list">
            @foreach($categories as $category)
                <article class="panel category-item">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="stack-form">@csrf @method('PUT')
                        <div class="category-title"><strong>{{ $category->name }}</strong><span>{{ $category->tickets_count }} tickets</span></div>
                        <label><span>Name</span><input type="text" name="name" maxlength="80" value="{{ $category->name }}" required></label>
                        <label><span>Description</span><input type="text" name="description" maxlength="255" value="{{ $category->description }}"></label>
                        <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" @checked($category->is_active)><span>Available for new tickets</span></label>
                        <button class="button button-secondary" type="submit">Save</button>
                    </form>
                    @if($category->tickets_count === 0)
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm="Delete this unused category?">@csrf @method('DELETE')<button class="danger-link" type="submit">Delete</button></form>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection

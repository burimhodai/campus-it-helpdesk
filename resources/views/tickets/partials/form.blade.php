<div class="form-grid">
    <label class="field-wide">
        <span>Subject</span>
        <input type="text" name="subject" maxlength="120" value="{{ old('subject', $ticket->subject ?? '') }}" placeholder="Example: Wi-Fi disconnects in the library" required>
    </label>
    <label>
        <span>Category</span>
        <select name="category_id" required>
            <option value="">Choose a category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $ticket->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </label>
    <label>
        <span>Priority</span>
        <select name="priority" required>
            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $value => $label)
                <option value="{{ $value }}" @selected(old('priority', $ticket->priority ?? 'medium') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <small>Use urgent only when work or study is completely blocked.</small>
    </label>
    <label class="field-wide">
        <span>Description</span>
        <textarea name="description" rows="8" maxlength="5000" placeholder="Explain what happened, where it happened, and what you already tried." required>{{ old('description', $ticket->description ?? '') }}</textarea>
    </label>
</div>

<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class StoreReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');

        return $ticket instanceof Ticket && $this->user()?->can('reply', $ticket);
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'min:2', 'max:3000'],
            'is_internal' => ['sometimes', 'boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Ticket::STATUSES)],
            'priority' => ['required', Rule::in(Ticket::PRIORITIES)],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('role', 'admin')],
            'message' => ['nullable', 'string', 'max:3000'],
            'is_internal' => ['sometimes', 'boolean'],
        ];
    }
}

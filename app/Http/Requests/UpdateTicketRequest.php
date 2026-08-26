<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');

        return $ticket instanceof Ticket && $this->user()?->can('update', $ticket);
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', Rule::exists('categories', 'id')->where('is_active', true)],
            'subject' => ['required', 'string', 'min:5', 'max:120'],
            'description' => ['required', 'string', 'min:15', 'max:5000'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
        ];
    }
}

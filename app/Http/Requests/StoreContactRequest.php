<?php

namespace App\Http\Requests;

use App\Enums\ContactStatus;
use App\Enums\ContactType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contact_type' => ['nullable', Rule::in(ContactType::getKeys())],
            'title' => ['required', 'max:255'],
            'content' => ['required', 'max:1023'],
            'link' => ['nullable'],
            'seq' => ['nullable', 'numeric'],
            'contact_status' => ['required', Rule::in(ContactStatus::getKeys())],
        ];
    }
}

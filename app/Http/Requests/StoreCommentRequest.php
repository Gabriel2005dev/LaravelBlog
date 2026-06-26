<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
       protected function prepareForValidation(): void
    {
        if (! is_string($this->body)) {
            return;
        }

        $body = str_replace(["\r\n", "\r"], "\n", $this->body);
        $body = preg_replace('/[^\S\n]+/u', ' ', $body);
        $body = preg_replace('/ *\n */u', "\n", $body);
        $body = preg_replace('/\n{2,}/u', "\n", $body);

        $this->merge([
            'body' => trim($body),
        ]);
    }
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:3', 'max:400'],
        ];
    }
}
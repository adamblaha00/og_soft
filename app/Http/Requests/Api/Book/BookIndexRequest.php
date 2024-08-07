<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Controllers\Api\Book\BookIndexController;
use App\Http\Requests\Api\BaseApiRequest;

class BookIndexRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filter.search' => ['nullable', 'string'],
            'filter.id' => ['nullable', 'array'],
            'filter.id.*' => ['nullable', 'string'],
            'filter.status' => ['nullable', 'integer', 'in:1,2'],
            'take' => ['nullable', 'integer', 'max:' . BookIndexController::TAKE],
            'sort' => ['nullable', 'array'],
            'sort.*' => ['nullable', 'string', 'in:' . implode(',', BookIndexController::SORT)],
        ];
    }
}

<?php

namespace App\Http\Requests\Api\TaskEstimator;

use App\Enums\CountryCodeEnum;
use App\Http\Requests\Api\BaseApiRequest;

class TaskEstimatorRequest extends BaseApiRequest
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
            'country' => ['required', 'string', 'in:'.implode(',', CountryCodeEnum::values())],
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'work_start_time' => 'required|date_format:H:i:s',
            'work_end_time' => 'required|date_format:H:i:s',
            'duration_minutes' => 'required|int|min:0',
            'consider_work_days' => 'required|boolean',
        ];
    }
}

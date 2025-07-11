<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreCategoryRequest extends FormRequest
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
            "name" => "required|string|max:50|unique:categories,name",
            "status" => "required|string|in:active,inactive"
        ];
    }

    // validate message
    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists.',
            'status.in' => 'Status must be either active or inactive.',
        ];
    }


    // custom attributes
    public function attributes(): array
    {
        return [
            'name' => 'category name',
            'status' => 'category status',
        ];
    }
    // custom response
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}

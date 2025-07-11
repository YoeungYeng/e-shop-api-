<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductsRequest extends FormRequest
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
            "name" => "sometimes|required|string|max:50",
            "price" => "sometimes|required|numeric",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "stock" => "required|integer",
            "status" => "sometimes|in:active,inactive",
            "category_id" => "required|unique:categories,catgory_id,except,id"
        ];
    }

    public function uploadImage($image)
    {
        if ($image) {
            $imagePath = $image->store('images', 'public'); // Store in storage/app/public/images
            return asset('storage/' . $imagePath); // Convert to URL
        }
        return null; // Return null if no image is provided
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}

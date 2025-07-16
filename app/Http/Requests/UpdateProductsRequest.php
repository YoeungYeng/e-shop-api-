<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

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

    public function uploadImage($image): ?string
    {
        if ($image && $image->isValid()) {
            // Generate a unique filename
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // Read and crop/resize the image using Intervention
            $processedImage = Image::read($image)
                ->resize(300, 300) // Resize to 300x300 pixels
                // ->crop(300, 300, 50, 50) // Or optionally crop
                ->encodeByExtension($image->getClientOriginalExtension(), quality: 80);

            // Save to public disk
            Storage::disk('public')->put("images/{$filename}", $processedImage);

            // Return the public URL
            return asset("storage/images/{$filename}");
        }

        return null;
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

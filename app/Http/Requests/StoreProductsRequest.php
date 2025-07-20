<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\Facades\Image;

class StoreProductsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

    /**
     * Upload image without processing.
     *
     * @param \Illuminate\Http\UploadedFile|null $image
     * @return string|null Public URL of saved image or null if no image.
     */
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

}

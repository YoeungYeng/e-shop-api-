<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

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
            "status" => "required|string|in:active,inactive",
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'image' => 'category image',
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
                ->cover(90, 90) // Resize to 300x300 pixels
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

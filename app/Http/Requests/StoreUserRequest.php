<?php

namespace App\Http\Requests;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class StoreUserRequest extends FormRequest
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
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ];
    }

    // attributes
    public function attributes(): array
    {
        return [
            'phone' => 'Phone Number',
            'address' => 'Address',
            'district' => 'District',
            'province' => 'Province',
            'country' => 'Country',
            'image' => 'Profile Image',
        ];
    }

    // upload image circle averta 
    public function uploadImage($image): ?string
    {
        if ($image && $image->isValid()) {
            // Generate a unique filename
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // Read and crop/resize the image using Intervention
            $processedImage = Image::read($image)
                ->cover(90, 90) // Resize to 90 x90 pixels
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

<?php

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

if (!function_exists('generateLocalAvatarPath')) {
    /**
     * Generate a local avatar path.
     *
     * @return string
     */
    function generateLocalAvatarPath()
    {
        return "https://picsum.photos/128";
    }
}
if (!function_exists('profileImagePath')) {

    function profileImagePath(Request $request)
    {
        if ($request->hasFile('photo')) {
            // Store the uploaded photo in the 'profile_photos' folder
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
        } else {
            // Optionally provide a default photo if no file is uploaded
            $photoPath = 'profile_photos/default/defautUserImage.png';  // Or use a default photo path, e.g., 'profile_photos/default.png'
        }
        return $photoPath;
    }
}
if (!function_exists('getPaginate')) {

    function getPaginate(Request $request)
    {

        $paginate = $request->query('paginate') ?? 10; // Default to 10 if 'paginate' is not provided
        return $paginate;
    }
}
if (!function_exists('ImagePath')) {

    // function ImagePath(Request $request, $ImageReplacePath, $photoKey = 'photo',)
    // {

    //     if (filter_var($photoKey, FILTER_VALIDATE_URL)) {
    //         // Download the image from the URL
    //         $tempImagePath = tempnam(sys_get_temp_dir(), 'photo_');
    //         file_put_contents($tempImagePath, file_get_contents($photoKey));

    //         // Upload the downloaded image to Imgur
    //         $link = uploadToImgurFromPath($tempImagePath);

    //         // Clean up the temporary file
    //         unlink($tempImagePath);

    //         return $link;
    //     }

    //     return uploadToImgur(request: $request, photoKey: $photoKey, defaultImagePath: $ImageReplacePath);
    // }
    function ImagePath(Request $request, $ImageReplacePath, $photoKey = 'photo')
    {
        if (filter_var($photoKey, FILTER_VALIDATE_URL)) {
            // Download the image from the URL
            $tempImagePath = tempnam(sys_get_temp_dir(), 'photo_');
            file_put_contents($tempImagePath, file_get_contents($photoKey));

            // Upload the downloaded image to Imgur
            $link = uploadToImgurFromPath($tempImagePath);

            // Clean up the temporary file
            unlink($tempImagePath);

            return $link;
        }

        return uploadToImgur($request, $ImageReplacePath, $photoKey);
    }
}
if (!function_exists('uploadToImgurFromPath')) {

    function uploadToImgurFromPath($filePath)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID'),
        ])
            ->attach('image', fopen($filePath, 'r'), basename($filePath))
            ->post('https://api.imgur.com/3/image');

        if ($response->successful()) {
            return $response->json()['data']['link']; // Return the image link
        }

        // Instead of returning a response, throw an exception
        throw new \Exception('Failed to upload image: ' . $response->body());
    }
}
if (!function_exists('uploadToImgur')) {

    // function uploadToImgur(Request $request, $defaultImagePath = null, $photoKey = 'photo')
    // {
    //     if ($request->hasFile($photoKey)) {
    //         $file = $request->file($photoKey);

    //         // Upload the file
    //         return uploadToImgurFromPath($file->getRealPath());
    //     }

    //     if ($defaultImagePath) {
    //         $defaultImagePath = public_path($defaultImagePath);

    //         // Upload the default image
    //         return uploadToImgurFromPath($defaultImagePath);
    //     }

    //     return response()->json(['error' => 'No image uploaded and no default image provided'], 400);
    // }
    function uploadToImgur(Request $request, $defaultImagePath = null, $photoKey = 'photo')
    {
        if ($request->hasFile($photoKey)) {
            $file = $request->file($photoKey);
            return uploadToImgurFromPath($file->getRealPath());
        }

        if ($defaultImagePath) {
            $defaultImagePath = public_path($defaultImagePath);
            return uploadToImgurFromPath($defaultImagePath);
        }

        // Instead of returning a response, throw an exception
        throw new \Exception('No image uploaded and no default image provided');
    }
}
    // function ImagePath(Request $request, $photoKey = 'photo', $ImageReplacePath)
    // {
    //     return uploadToImgur(request: $request, photoKey: $photoKey, defaultImagePath: $ImageReplacePath);
    //     // $photoData = null;

    //     // // If the photo exists in the request
    //     // if ($request->hasFile($photoKey)) {
    //     //     // Get the file content
    //     //     $file = $request->file($photoKey);
    //     //     $photoData = base64_encode(file_get_contents($file));
    //     // }

    //     // // If no photo is uploaded, use the default image and encode it
    //     // if ($photoData === null) {
    //     //     $defaultImagePath = public_path($ImageReplacePath);
    //     //     $photoData = base64_encode(file_get_contents($defaultImagePath));
    //     // }

    //     // return $photoData;
    // }
    // function uploadToImgur(Request $request, $defaultImagePath = null, $photoKey = 'photo',)
    // {
    //     // Check if an image is uploaded in the request
    //     if ($request->hasFile($photoKey)) {
    //         $file = $request->file($photoKey);

    //         // Upload the image to Imgur
    //         $response = Http::withHeaders([
    //             'Authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID'),
    //         ])
    //             ->attach('image', fopen($file->getRealPath(), 'r'), $file->getClientOriginalName())
    //             ->post('https://api.imgur.com/3/image');

    //         if ($response->successful()) {
    //             return $response->json()['data']['link']; // Return the image link
    //         }

    //         return response()->json(['error' => 'Failed to upload image'], 400);
    //     }

    //     // Handle default image upload
    //     if ($defaultImagePath) {
    //         $defaultImagePath = public_path($defaultImagePath);
    //         $response = Http::withHeaders([
    //             'Authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID'),
    //         ])
    //             ->attach('image', fopen($defaultImagePath, 'r'), basename($defaultImagePath))
    //             ->post('https://api.imgur.com/3/image');

    //         if ($response->successful()) {
    //             return $response->json()['data']['link']; // Return the image link
    //         }

    //         return response()->json(['error' => 'Failed to upload default image'], 400);
    //     }

    //     return response()->json(['error' => 'No image uploaded and no default image provided'], 400);
    // }

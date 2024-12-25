<?php

use Illuminate\Http\Request;

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

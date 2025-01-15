<?php
// Route::post('/upload-image', function (Request $request) {
//     // Validate the incoming request


//     // Use the ImagePath function to handle the upload
//     $imagePath = ImagePath($request, 'defualts/images/defaultUserImage.jpg');

//     // Return the response
//     return response()->json([
//         'success' => true,
//         'message' => 'Image uploaded successfully',
//         'image_url' => $imagePath,
//     ]);
// });
// Route::post('/upload-image-link', function (Request $request) {
//     // Validate the incoming request


//     // Check if the input is a URL or a file
//     if (filter_var($request->input('photo'), FILTER_VALIDATE_URL)) {
//         $imagePath = ImagePath($request, 'defualts/images/defaultUserImage.jpg', $request->input('photo'));
//     } else {
//         $imagePath = ImagePath($request, 'defualts/images/defaultUserImage.jpg');
//     }

//     // Return the response
//     return response()->json([
//         'success' => true,
//         'message' => 'Image uploaded successfully',
//         'image_url' => $imagePath,
//     ]);
// });
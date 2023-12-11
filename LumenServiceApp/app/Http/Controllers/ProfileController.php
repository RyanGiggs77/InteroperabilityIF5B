<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validationRules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'summary' => 'required|min:10',
        ];

        // Validation
        $validator = Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check if a profile with the user_id exists
        $profile = Profile::where('user_id', Auth::user()->id)->first();

        if (!$profile) {
            $profile = new Profile;
            $profile->user_id = Auth::user()->id;
        }

        // Update profile data
        $profile->first_name = $input['first_name'];
        $profile->last_name = $input['last_name'];
        $profile->summary = $input['summary'];

        // Handle uploaded image
        if ($request->hasFile('image')) {
            $firName = str_replace(' ', '_', $input['first_name']);
            $lasName = str_replace(' ', '_', $input['last_name']);

            $imgName = Auth::user()->id . '_' . $firName . '_' . $lasName;
            $request->file('image')->move(storage_path('uploads/image_profile'), $imgName);

            // Delete the previous image
            $current_image_path = storage_path('avatar') . '/' . $profile->image;
            if (file_exists($current_image_path)) {
                unlink($current_image_path);
            }

            $profile->image = $imgName;
        }

        // Save profile
        $profile->save();

        return response()->json($profile, 200);
    }

    public function show($userId)
    {
        $profile = Profile::where('user_id', $userId)->first();

        if (!$profile) {
            abort(404);
        }

        return response()->json($profile, 200);
    }

    public function image($imageName)
    {
        $imagePath = storage_path('uploads/image_profile') . '/' . $imageName;
        if (file_exists($imagePath)) {
            $file = file_get_contents($imagePath);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
        }

        return response()->json(array('message' => 'Image not found'), 401);
    }
}

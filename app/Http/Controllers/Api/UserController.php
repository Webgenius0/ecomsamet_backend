<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        // Get the authenticated user
        $user = Auth::guard('api')->user();

        // Check if the user is authorized
        if (!$user || $user->id != $id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate input data
        $validator = Validator::make($request->all(), [
            'fullname' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|unique:api_users,phone,' . $id,
            'email' => 'sometimes|email|unique:api_users,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        dd($request->all());
        //image path saved
        $imagePaths = [];

        // Handle each uploaded image
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('profile_images', 'public');
                $imagePaths[] = $path;
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 422);
        }
        //

        try {
            // Update fields only if provided
            if ($request->has('fullname')) $user->fullname = $request->fullname;
            if ($request->has('phone')) $user->phone = $request->phone;
            if ($request->has('email')) $user->email = $request->email;
            if ($request->has('password')) $user->password = Hash::make($request->password);
            if ($request->has('image')) $user->image = $request->image;

            // Save updated user
            $user->save();

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong!',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

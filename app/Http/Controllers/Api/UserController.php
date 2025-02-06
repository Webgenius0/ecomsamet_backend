<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(){
       // here show token base user details from user table
       $user = Auth::guard('api')->user();
       return ApiResponse::format(true,201, 'User details retrieved successfully', $user);

    }

    public function update(Request $request, $id)
{
    // Get the authenticated user
    $user = Auth::guard('api')->user();

    // Check if the user is authorized
    if (!$user || $user->id != $id) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|string|max:150',
        'phone' => 'sometimes|string|unique:users,phone,' . $id,
        'email' => 'sometimes|email|unique:users,email,' . $id,
        'password' => 'sometimes|string|min:6',
        'about_you' => 'sometimes|string|nullable',
        'bussiness_name' => 'sometimes|string|nullable',
        'bussiness_address' => 'sometimes|string|nullable',
        'professional_title' => 'sometimes|string|nullable',
        'professional_certificate' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048|nullable',
        'provider_id' => 'sometimes|string|nullable',
        'mobile_verfi_otp' => 'sometimes|string|nullable',
        'terms_and_policy' => 'sometimes|boolean',
        'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
        'google_id' => 'sometimes|string|unique:users,google_id,' . $id . '|nullable',
        'apple_id' => 'sometimes|string|unique:users,apple_id,' . $id . '|nullable',
        'role' => 'sometimes|in:admin,user,hairdresser',
        'status' => 'sometimes|in:active,inactive',
    ]);

    // dd($request->all());

    // If validation fails, return errors
    if ($validator->fails()) {
        return response()->json([
            'error' => 'Validation failed',
            'details' => $validator->errors()
        ], 422);
    }

    try {
        // Update fields only if provided
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = Hash::make($request->password);
        if ($request->has('about_you')) $user->about_you = $request->about_you;
        if ($request->has('bussiness_name')) $user->bussiness_name = $request->bussiness_name;
        if ($request->has('bussiness_address')) $user->bussiness_address = $request->bussiness_address;
        if ($request->has('professional_title')) $user->professional_title = $request->professional_title;
        if ($request->has('provider_id')) $user->provider_id = $request->provider_id;
        if ($request->has('mobile_verfi_otp')) $user->mobile_verfi_otp = $request->mobile_verfi_otp;
        if ($request->has('terms_and_policy')) $user->terms_and_policy = $request->terms_and_policy;
        if ($request->has('google_id')) $user->google_id = $request->google_id;
        if ($request->has('apple_id')) $user->apple_id = $request->apple_id;
        if ($request->has('role')) $user->role = $request->role;
        if ($request->has('status')) $user->status = $request->status;

        // Handle Image Upload Properly (Avatar)
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }


        // Handle File Upload for Professional Certificate
        if ($request->hasFile('professional_certificate')) {
            $certificatePath = $request->file('professional_certificate')->store('certificates', 'public');
            $user->professional_certificate = $certificatePath;
        }

        // dd($user);

        // Save updated user
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong', 'details' => $e->getMessage()], 500);
    }
}

    public function destroy($id){
        try {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'details' => $e->getMessage()], 500);
        }
    }
}

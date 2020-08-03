<?php

namespace App\Http\Controllers\Author;

use App\Helpers\StoreImage;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        return view('author.settings.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'image' => 'mimes:jpg,jpeg,png'
        ]);

        $user = User::findOrFail(Auth::id());

        $image = $request->file('image');
        $user_name = $request->name;

        if (isset($image)) {
            $storeProfileImage = new StoreImage(
                'profile', $image, 500, 500, $user_name, $user->image
            );
            $unique_image_name = $storeProfileImage->storeImage();
            $user->image = $unique_image_name;
        }

        $user->name = $user_name;
        $user->email = $request->email;
        $user->about = $request->about;
        $user->save();

        Toastr::success('Profile Updated Successfully', 'Profile Updated');

        return redirect()->back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $plain_old_password = $request->old_password;
        $plain_new_password = $request->password;
        $hashed_password = Auth::user()->password;

        if (Hash::check($plain_old_password, $hashed_password)) {
            if (!Hash::check($plain_new_password, $hashed_password)) {
                $user = User::findOrFail(Auth::id());
                $user->password = Hash::make($plain_new_password);
                $user->save();
                Auth::logout();

                Toastr::success('Password Updated Successfully', 'Password Updated');
                return redirect()->back();
            } else {
                Toastr::error("You enter an old password", "Same Password");
                return redirect()->back();
            }
        } else {
            Toastr::error("Old password doesn't match", "Mismatch Old Password");
            return redirect()->back();
        }
    }
}

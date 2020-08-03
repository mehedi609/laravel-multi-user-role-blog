<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\StoreImage;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}

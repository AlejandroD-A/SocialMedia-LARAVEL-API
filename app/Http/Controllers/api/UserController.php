<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class UserController extends ApiResponseController
{

    public function show(Request $request, User $user)
    {

        $user->profile;

        $loggedUser = Auth::guard('api')->user();
        $isFollow = false;
        if ($loggedUser) {
            $isFollow = $loggedUser->following->contains($user->id) ? true : false;
        }
        return $this->successResponse(['user' => $user, 'isFollow' => $isFollow]);
    }

    public function updateProfile()
    {

        $validated = request()->validate([
            'about' => 'max: 255',
            'url' => 'required',
            'avatar' => 'required',
            'name' => 'string', 'max:255'
        ]);

        dd($validated);


        if (request('avatar')) {
            request()->validate([
                'image' => 'mimes:jpeg,bmp,png|size:5000'
            ]);

            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            $imageArray = ['avatar' => $imagePath];
        }

        if (request('url') != '') {
            $url = request()->validate([
                'url' => 'url'
            ]);
        }
        $user = request()->user();

        $user->update(['name' => $validated['name']]);

        unset($validated['name']);

        $user->profile->update(array_merge(
            $validated,
            $imageArray ?? [],
            $url ?? [],
        ));



        /* $user = User::find(request()->user()->id);
        $user->load('profile'); */

        return $this->successResponse(['user' => $user]);
    }
}

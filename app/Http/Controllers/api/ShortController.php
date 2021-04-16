<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use App\Models\Short;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\App;
use Intervention\Image\Facades\Image;
use App\Http\Requests\ShortPostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\api\ApiResponseController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ShortController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shorts = Short::with([
            'tags:id,name', 'user:id,username,avatar', 'images:short_id,url'
        ])
            ->latest()
            ->paginate(5);



        return $this->successResponse($shorts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Short $short)
    {
        $short->load('tags', 'user', 'images');
        return $this->successResponse($short);
    }


    public function perspective(Request $request)
    {
        $users = request()->user()->following;

        $users->push(request()->user()->id);

        $shorts = Short::whereIn('user_id', $users)
            ->with([
                'tags:id,name', 'user:id,username', 'images:short_id,url'
            ])
            ->latest()
            ->paginate(5);

        return $this->successResponse($shorts);
    }


    public function tag(Tag $tag)
    {
        $shorts = $tag->shorts()->orderBy('created_at', 'desc')->paginate(5);
        $shorts->load('tags');
        return $this->successResponse(["tag" => $tag->name, "shorts" => $shorts]);
    }

    public function store(ShortPostRequest $request)
    {


        $user = $request->user();
        $validated = $request->validated();
        $tags = [];
        $imagePaths = [];
        if (array_key_exists('tags', $validated)) {
            $tags = $validated['tags'];
            unset($validated['tags']);
        }
        if (array_key_exists('images', $validated)) {
            foreach ($validated['images'] as $image) {
                $imagePath = $this->uploadImage($image);
                array_push($imagePaths, ['url' => $imagePath]);
            }

            unset($validated['images']);
        }

        $short = new Short($validated);

        DB::transaction(function () use ($user, $short, $tags, $imagePaths) {
            $short->user_id = $user->id;
            $short->save();

            if (count($tags) > 0) {
                $listOfTags = [];
                foreach ($tags as $tag) {
                    $tag = Tag::firstOrCreate($tag);
                    array_push($listOfTags, $tag->id);
                }
                $short->tags()->attach($listOfTags);
            }

            $short->images()->createMany($imagePaths);
        });

        return $this->successResponse($short->load([
            'tags:id,name', 'user:id,username,avatar', 'images:short_id,url'
        ]), 201, 'Short created Succesfully');
    }



    public function getComments(Short $short)
    {
        $comments = $short->comments;
        $comments->load('user');

        return response()->json($comments, 200);
    }


    private function uploadImage($image)
    {

        if (App::environment('local')) {
            $imagePath = $image->store('uploads', 'public');
            $imageUpload = Image::make(public_path("storage/{$imagePath}"))->fit(1080, 1080);
            $imageUpload->save();
            return "http://localhost:8000/storage/{$imagePath}";
        } else {
            /* $imagePath = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'uploads',
                'transformation' => [
                    'width' => 1200,
                    'crop' => 'limit',
                    'quality' => 'auto',
                    'fetch_format' => 'auto'
                ]
            ])->getSecurePath(); */

            $imageFile = Image::make($image)->fit(1080, 1080);
            $imageFile = $imageFile->stream();

            $info = pathinfo($image->getClientOriginalName());
            $ext = $info['extension'];
            $imageName = uniqid() . ".$ext";


            Storage::disk('s3')->put("/imagesShorts/$imageName", (string)$imageFile);
            $path = "https://mediaesebucket.s3-sa-east-1.amazonaws.com/imagesShorts/$imageName";

            return $path;
        }
    }
}

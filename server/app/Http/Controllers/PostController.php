<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends OdgovorController
{
    public function index()
    {
        $postovi = Post::all()->load(['ucesce', 'komentari']);
        return $this->uspesno(PostResource::collection($postovi), "Uspesno ucitani postovi");
    }

    public function show($id)
    {
        $post = Post::find($id)->load(['ucesce', 'komentari']);
        if (!$post) {
            return $this->neuspesno([], "Post nije pronadjen", 404);
        }
        return $this->uspesno(new PostResource($post), "Uspesno ucitan post");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ucesce_id' => 'required|numeric|exists:ucesca,id',
            'sadrzaj' => 'required|string',
            'datum_objave' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $post = Post::create($request->all())->load(['ucesce', 'komentari']);
        return $this->uspesno(new PostResource($post), "Post uspesno kreiran");

    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->neuspesno([], "Post nije pronadjen", 404);
        }
        $post->delete();
        return $this->uspesno([], "Post uspesno obrisan");
    }
}

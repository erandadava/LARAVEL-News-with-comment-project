<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;
use App\Models\News;
use Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'body'=>'required',
            'post_id' => 'required|integer',
        ]);

        if (News::where('id', '=', $request->post_id)->exists()) {
            $comment = Comment::create([
                'body' => $request->body,
                'post_id' => $request->post_id,
            ]);

            return response()->json([
                "status" => true,
                "message" => "Comment Succesfully Added",
                "data" => $comment
            ]);
        }else{
            return response()->json([
                "status" => "Error",
                "message" => "News Doesn't exists",
            ], 404);
        }

    }
}

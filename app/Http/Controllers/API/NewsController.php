<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\News;
use App\Models\Comment;
use Validator;
use App\Http\Resources\News as NewsResource;

class NewsController extends BaseController
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $news = News::with('comments')->get();

        return response()->json([
            "status" => true,
            "message" => "News retrieved",
            "data" => $news
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $image_path = $request->file('image')->store('image', 'public');

        // if($this->validate->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

        $news = News::create([
            'image' => $image_path,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return $this->sendResponse(new NewsResource($news), 'News Created Successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                "message" => "News Not Found",
            ], 404);
        }

        return $this->sendResponse(new NewsResource($news), 'News Retrieved Successfully.');
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update(Request $request, News $news)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->store('image', 'public');

            //delete old image
            Storage::delete('image/'.$news->image);

            //update news with new image
            $news->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
            ]);

        } else {
            //update news without image
            $news->update([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        }

        return $this->sendResponse(new NewsResource($news), 'News Updated Successfully.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(News $news)
    {
        $news->delete();
        return $this->sendResponse([], 'Product Deleted Successfully.');
    }
}

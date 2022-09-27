<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Blog as BlogResource;
use App\Http\Controllers\Api\BaseController as BaseController;

class BlogController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return $this->sendResponse(BlogResource::collection($user->blogs), 'Blog Fetched Successfully!.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_title' => 'required|unique:blogs,blog_title',
            'blog_description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $user_id = Auth::user()->id;

        $blog = new Blog;
        $blog->user_id = $user_id;
        $blog->blog_title = $request->input('blog_title');
        $blog->blog_description = $request->input('blog_description');
        $blog->save();

        return $this->sendResponse(new BlogResource($blog), 'Blog Added Successfully!.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::find($id);
        if (is_null($blog)) {
            return $this->sendError('Blog does not exist.');
        }
        return $this->sendResponse(new BlogResource($blog), 'Blog with this id Fetched Successfully!.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        if (is_null($blog)) {
            return $this->sendError('Blog does not exist.');
        }

        $validator = Validator::make($request->all(), [
            'blog_title' => 'required|unique:blogs,blog_title,' . $id . ',id',
            'blog_description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $blog->blog_title = $request->input('blog_title');
        $blog->blog_description = $request->input('blog_description');
        $blog->save();

        return $this->sendResponse(new BlogResource($blog), 'Blog Edited Successfully!.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (is_null($blog)) {
            return $this->sendError('Blog does not exist.');
        }

        $blog->delete();

        return $this->sendResponse([], 'Blog Deleted Successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    
    protected $post;
    
    function __construct(Post $post) {
        $this->post = $post;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        
        $post = $this->post->all();
        return $this->response($post, self::OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StorePostRequest $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        return $this->response($post->save(), self::CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $post = $this->post->find($id);
        return $post ? $this->response($post, self::OK) : $this->error('Post not found', self::NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $post = $this->post->find($id);
        if (!$post)
            return $this->error('resource not found', self::NOT_FOUND);
        $post->delete();
        return $this->response($post, self::NO_CONTENT);
    }
}

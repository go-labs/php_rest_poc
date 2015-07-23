<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Requests\StorePostRequest;
use Log;

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
     * @param  StorePostRequest  $request
     * @return Response
     */
    public function store(StorePostRequest $request)
    {
        $post = $this->post->create($request->all());
        $this->email_sender('emails.create_new_post', $post, 'New post created.');
        return $this->response($post, self::CREATED);
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
     * @param  StorePostRequest  $request
     * @param  int  $id
     * @return Response
     */
    public function update(StorePostRequest $request, $id)
    {
        $post = $this->post->find($id);
        if (!$post)
            return $this->error('Post not found', self::NOT_FOUND);
        $post->fill($request->all());
        $post->save();
        return $this->response($post, self::OK);
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
            return $this->error('Post not found', self::NOT_FOUND);
        $post->tags()->detach();
        $post->delete();
        Log::info('Post deleted: '. $post->toJson());
        return $this->response($post, self::NO_CONTENT);
    }
    
    /**
     * add tags to specified post.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return Response
     */
    public function add_tags(Request $request, $id)
    {

        $post = $this->post->find($id);
        if (!$post)
            return $this->error('Post not found', self::NOT_FOUND);
        $post_tag = $this->post->add_tags($post, $request->tags);
        return is_null($post_tag) ? $this->response($post->tags, self::OK) : $this->error('An error has occurred. Try again.', self::NOT_FOUND);
    }
}

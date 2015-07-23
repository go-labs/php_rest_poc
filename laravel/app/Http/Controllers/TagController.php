<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Http\Requests\StoreTagRequest;
use Log;

class TagController extends Controller
{
    protected $tag;
    
    function __construct(Tag $tag) {
        $this->tag = $tag;
    }

    /**
     * Display a listing of the tags.
     * Display a listing of the posts by tags(s).
     * Display a listing of the count post by tag(s).
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        if($request->has('search')){
            $posts = $this->tag->post_by_tag($request->search);
            return $posts->count() > 0 ? $this->response($posts, self::OK) : $this->error('Tag(s) not found', self::NOT_FOUND);
        }
        if($request->has('count_posts')){
            $posts = $this->tag->count_post_by_tag($request->count);
            return $posts->count() > 0 ? $this->response($posts, self::OK) : $this->error('Tag(s) not found', self::NOT_FOUND);
        }
        $tag = $this->tag->all();
        return $this->response($tag, self::OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreTagRequest  $request
     * @return Response
     */
    public function store(StoreTagRequest $request)
    {
        $tag = $this->tag->create($request->all());
        return $this->response($tag, self::CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $tag = $this->tag->find($id);
        return $tag ? $this->response($tag, self::OK) : $this->error('tag not found', self::NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreTagRequest  $request
     * @param  int  $id
     * @return Response
     */
    public function update(StoreTagRequest $request, $id)
    {
        $tag = $this->tag->find($id);
        if (!$tag)
            return $this->error('Tag not found', self::NOT_FOUND);
        $tag->fill($request->all());
        $tag->save();
        return $this->response($tag, self::OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tag = $this->tag->find($id);
        if (!$tag)
            return $this->error('Tag not found', self::NOT_FOUND);
        $tag->posts()->detach();
        $tag->delete();
        Log::info('Tag deleted: '. $tag->toJson());
        return $this->response($tag, self::NO_CONTENT);
    }
}

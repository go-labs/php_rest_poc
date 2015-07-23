<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Requests\StorePostRequest;
use Log;

use Swagger\Annotations as SWG;

/**
 * @SWG\Resource(
 *  apiVersion="1.0",
 *  resourcePath="/post",
 *  description="Post",
 *  produces="['application/json']"
 * )
 */
class PostController extends Controller
{
    protected $post;
    
    function __construct(Post $post) {
        $this->post = $post;
    }

    /**
     * @SWG\Api(
     *  path="/post",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Displays all posts",
     *          nickname="Get Posts"
     *  )
     * )
     */

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
     * @SWG\Api(
     *  path="/post",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Store post",
     *          nickname="Store post",
     *      @SWG\Parameter(
     *          name="title",
     *          description="Title of post",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="body",
     *          description="Body of post",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="integer"
     *          ),
     *  )
     * )
     */


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
     * @SWG\Api(
     *  path="/post/{id}",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Get information of a post",
     *          nickname="Get information of a post",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of post",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */

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
     * @SWG\Api(
     *  path="/post/{id}",
     *      @SWG\Operation(
     *          method="PATCH",
     *          summary="Update information of a post",
     *          nickname="Update information of a post",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of post to update",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="title",
     *          description="title to update",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="body",
     *          description="body to update",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */

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
     * @SWG\Api(
     *  path="/post/{id}",
     *      @SWG\Operation(
     *          method="DELETE",
     *          summary="Delete information of a post",
     *          nickname="Delete information of a post",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of post to remove",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */

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
     * @SWG\Api(
     *  path="/post/{id}/tags",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Add tags to post",
     *          nickname="Add tags to post",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of post to add tags",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="tags",
     *          description="id(s) of tags to add",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
 
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
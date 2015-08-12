<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use Swagger\Annotations as SWG;
use App\Repositories\Post\IPostRepository;

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

    function __construct(IPostRepository $post) {
        $this->post = $post;
    }

    /**
     * @SWG\Api(
     *  path="/post",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Returns posts",
     *          nickname="HTTP GET posts",
     *          @SWG\Parameter(
     *            name="tags",
     *            description="search post by tags, separate multiple values with commas i.e funny,jokes,movies",
     *            paramType="query",
     *              required=false,
     *              allowMultiple=false,
     *              type="string",
     *              defaultValue=""
     *          ),
     *           @SWG\Parameter(
     *            name="count_only",
     *            description="counts the number of posts by tag",
     *            paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="boolean",
     *              defaultValue="false"
     *          )
     *  )
     * )
     */
    public function index(Request $request)
    {
        $posts = $request->has('tags') ? $this->post->search_by_tag($request->tags) : $this->post->all();
        if($request->count_only === 'true'){
            $posts = count($posts);
        }
        return $this->response($posts, self::OK);
    }

    /**
     * @SWG\Api(
     *  path="/post",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Creates a new post",
     *          nickname="HTTP POST posts",
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
    public function store(StorePostRequest $request)
    {
        $post = $this->post->create($request->all());
        return $this->response($post, self::CREATED);
    }

    /**
     * @SWG\Api(
     *  path="/post/{id}",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Returns a specific post",
     *          nickname="HTTP GET post",
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
     *          summary="Updates a specific post",
     *          nickname="HTTP PATCH post",
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
     * @SWG\Api(
     *  path="/post/{id}",
     *      @SWG\Operation(
     *          method="PUT",
     *          summary="Updates a specific post",
     *          nickname="HTTP PUT post",
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
    public function update(StorePostRequest $request, $id)
    {
        $post = $this->post->update($request->all(), $id);
        if (!$post)
            return $this->error('Post not found', self::NOT_FOUND);
        return $this->response($post, self::OK);
    }

    /**
     * @SWG\Api(
     *  path="/post/{id}",
     *      @SWG\Operation(
     *          method="DELETE",
     *          summary="Deletes a specific post",
     *          nickname="HTTP DELETE post",
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
    public function destroy($id)
    {
        $post = $this->post->delete($id);
        if (!$post)
            return $this->error('Post not found', self::NOT_FOUND);
        return $this->response($post, self::NO_CONTENT);
    }

    /**
     * @SWG\Api(
     *  path="/post/{id}/tags",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Add tags to posts",
     *          nickname="Add tags to posts",
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
     *          description="id(s) of tags to add, separate with commas i.e 1,2,3",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function add_tags(Request $request, $id)
    {
        $post = $this->post->add_tags($request->tags, $id);
        if (is_null($post))
            return $this->error('Post not found', self::NOT_FOUND);
        return $post ? $this->response($post, self::OK) : $this->error('An error has occurred. Try again.', self::NOT_FOUND);
    }
}

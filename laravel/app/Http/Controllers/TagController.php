<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Http\Requests\StoreTagRequest;
use Log;
use Swagger\Annotations as SWG;

/**
 * @SWG\Resource(
 *  apiVersion="1.0",
 *  resourcePath="/tag",
 *  description="Tag",
 *  produces="['application/json']"
 * )
 */
class TagController extends Controller
{
    protected $tag;
    
    function __construct(Tag $tag) {
        $this->tag = $tag;
    }


    /**
     * @SWG\Api(
     *  path="/tag",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Display post by tag(s)",
     *          nickname="Display post by tag(s)",
     *      @SWG\Parameter(
     *          name="tags",
     *          description="id(s) of tags to tags posts, separate multiple values with commas i.e 1,2,3",
     *          paramType="query",
     *              required=false,
     *              allowMultiple=false,
     *              type="string",
     *              defaultValue=""
     *          ),
    *      @SWG\Parameter(
     *          name="count_only",
     *          description="id(s) of tags to count posts",
     *          paramType="query",
     *              required=false,
     *              allowMultiple=false,
     *              type="boolean",
     *              defaultValue="false"
     *          )
     *  )
     * )
     */


    /**
     * Display a listing of the tags.
     * Display a listing of the posts by tags(s).
     * Display a listing of the count post by tag(s).
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tags = [];

        $tags = ($request->has('tags')) ? $this->tag->post_by_tag($request->tags) : $this->tag->all();

        if($request->count_only === 'true'){
            $tags = $tags[0]->posts->count();
        }

        return $this->response($tags, self::OK);
    }

    /**
     * @SWG\Api(
     *  path="/tag",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Store a tag",
     *          nickname="Store a tag",
     *      @SWG\Parameter(
     *          name="name",
     *          description="Name of tag",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */


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
     * @SWG\Api(
     *  path="/tag/{id}",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Get information of a tag",
     *          nickname="Get information of a tag",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of tag",
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
        $tag = $this->tag->find($id);
        return $tag ? $this->response($tag, self::OK) : $this->error('Tag not found', self::NOT_FOUND);
    }

    /**
     * @SWG\Api(
     *  path="/tag/{id}",
     *      @SWG\Operation(
     *          method="PATCH",
     *          summary="Update information of a tag",
     *          nickname="Update information of a tag",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of tag to update",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="name",
     *          description="Name to update",
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
     *  path="/tag/{id}",
     *      @SWG\Operation(
     *          method="PUT",
     *          summary="Update information of a tag",
     *          nickname="Update information of a tag",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of tag to update",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="name",
     *          description="Name to update",
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
     * @SWG\Api(
     *  path="/tag/{id}",
     *      @SWG\Operation(
     *          method="DELETE",
     *          summary="Delete information of a post",
     *          nickname="Delete information of a post",
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of tag to remove",
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
        $tag = $this->tag->find($id);
        if (!$tag)
            return $this->error('Tag not found', self::NOT_FOUND);
        $tag->posts()->detach();
        $tag->delete();
        Log::info('Tag deleted: '. $tag->toJson());
        return $this->response($tag, self::NO_CONTENT);
    }
}

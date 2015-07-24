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
     *          summary="Returns tags",
     *          nickname="HTTP GET tags",
     *          @SWG\Parameter(
     *            name="name",
     *            description="name of the tag to search",
     *            paramType="query",
     *              required=false,
     *              allowMultiple=false,
     *              type="string",
     *              defaultValue=""
     *          )
     *  )
     * )
     */
    public function index(Request $request)
    {
        $criteria = [];
        if ($request->has('name')){
            $criteria['name'] = $request->name;
        }

        return $this->response($this->tag->where($criteria)->get(), self::OK);
    }

    /**
     * @SWG\Api(
     *  path="/tag",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Creates a new tag",
     *          nickname="HTTP POST tag",
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
     *          summary="Returns a specific tag",
     *          nickname="HTTP GET tag",
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
     *          summary="Updates a specific tag",
     *          nickname="HTTP PATCH tag",
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
     *          summary="Updates a specific tag",
     *          nickname="HTTP PUT tag",
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
     *          summary="Deletes a specific tag",
     *          nickname="HTTP DELETE tag",
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

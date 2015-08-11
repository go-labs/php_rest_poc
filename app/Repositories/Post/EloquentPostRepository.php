<?php namespace App\Repositories\Post;

use App\Models\Post;

class EloquentPostRepository implements IPostRepository{


	public function all()
	{
		return Post::all();
	}

	public function create(array $data)
	{
		return Post::create($data);
	}

    public function update(array $data, $id)
    {
    	$post = Post::find($id);
    	if(!$post)
    		return false;
    	$post->fill($data)->save();
    	return $post ? $post : null;
    }

    public function delete($id)
    {
 		$post = Post::find($id);
        if (!$post)
            return false;
 		$post->tags()->detach();
        return $post->delete();
    }

    public function find($id)
    {
    	return Post::find($id);
    }

    public function add_tags($data, $id)
    {
    	try {
    		$data = explode(',', $data);
    		$post = Post::find($id);
    		if (!$post)
            	return null;
    		$post_tag = $post->tags()->attach($data);
    		return is_null($post_tag) ? $post->tags : false;
    	} catch (\Exception $e) {
    		return false;
    	}
    }

    public function search_by_tag($data){
        return \DB::table('posts')->join('post_tags', 'posts.id', '=', 'post_tags.post_id')
            ->join('tags', 'tags.id', '=', 'post_tags.tag_id')
            ->select('posts.*')
            ->wherein('tags.name', explode(',', $data))
            ->get();
    }
}
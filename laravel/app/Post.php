<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $fillable = ['title', 'body'];
	protected $guarded = ['id'];
	public $timestamps = false;

	public function tags()
    {
        return $this->belongsToMany('App\Tag','post_tags', 'post_id', 'tag_id');
    }

    public static function search_by_tag($tags){
        return \DB::table('posts')->join('post_tags', 'posts.id', '=', 'post_tags.post_id')
            ->join('tags', 'tags.id', '=', 'post_tags.tag_id')
            ->select('posts.*')
            ->wherein('tags.name', explode(',', $tags))
            ->get();
    }

    public function add_tags($post, $data)
    {
    	try {
    		$data = explode(',', $data);
    		return $post->tags()->attach($data);
    	} catch (\Exception $e) {
    		return false;
    	}
    }
}

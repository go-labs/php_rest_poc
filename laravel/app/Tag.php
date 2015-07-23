<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tag extends Model
{
	protected $fillable = ['name'];

	public function posts()
    {
        return $this->belongsToMany('App\Post', 'post_tags', 'tag_id', 'post_id');
    }

    public function count_post_by_tag($tags)
    {
    	$data = explode(',', $tags);
        $posts = $this->with(array('posts'=> function($query){
                                $query->select(DB::raw('count(*) as count_posts'));
                           }))
                           ->wherein('id', $data)
                           ->get();
        return $posts;
    }

    public function post_by_tag($tags)
    {
        $data = explode(',', $tags);
        $posts = $this->with('posts')
	                  ->wherein('id', $data)
	                  ->get();
        return $posts;
    }
}

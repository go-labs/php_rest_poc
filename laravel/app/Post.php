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
        return $this->belongsToMany('App\Tag','post_tags', 'post_id', 'tags_id');
    }
}

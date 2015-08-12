<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $fillable = ['title', 'body'];
	protected $guarded = ['id'];
	public $timestamps = false;

	public function tags()
    {
        return $this->belongsToMany('App\Models\Tag','post_tags', 'post_id', 'tag_id');
    }
}

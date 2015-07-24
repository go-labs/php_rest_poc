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
}

<?php namespace App\Repositories\Tag;

use App\Models\Tag;

class EloquentTagRepository implements ITagRepository{


    public function all()
    {
        return Tag::all();
    }

    public function find($id)
    {
        return Tag::find($id);
    }

    public function create(array $data)
    {
        return Tag::create($data);
    }

    public function update(array $data, $id)
    {
        $tag = Tag::find($id);
        if(!$tag)
            return false;
        $tag->fill($data)->save();
        return $tag ? $tag : null;
    }

    public function delete($id)
    {
        $tag = Tag::find($id);
        if (!$tag)
            return false;
        $tag->posts()->detach();
        return $tag->delete();
    }

    public function search_by(array $data) {
        return Tag::where($data)->get();
    }
}

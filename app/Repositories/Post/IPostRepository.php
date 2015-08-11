<?php namespace App\Repositories\Post;

interface IPostRepository {

    public function all();

    public function find($id);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function add_tags($data, $id);

    public function search_by_tag($data);

}

<?php namespace App\Repositories\Tag;

interface ITagRepository {

    public function all();

    public function find($id);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function search_by(array $data);
}

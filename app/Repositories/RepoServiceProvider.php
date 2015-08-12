<?php namespace App\Repositories;

use App\Repositories\Post;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('App\Repositories\Post\IPostRepository',
            'App\Repositories\Post\EloquentPostRepository');

        $this->app->bind('App\Repositories\Tag\ITagRepository',
            'App\Repositories\Tag\EloquentTagRepository');
    }
}

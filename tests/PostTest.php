<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->fake_post = [
            'id' => 1,
            'title' => 'title 1',
            'body' => 'body 1',
            'created_at' => '-0001-11-30 00:00:00',
            'updated_at' => '-0001-11-30 00:00:00'
        ];
        $this->mock = Mockery::mock('App\Repositories\Post\IPostRepository');
    }

    public function tearDown()
    {
        //Mockery::close();
    }

    public function testSearchAllPost()
    {
        $fake = array($this->fake_post);
        $this->mock->shouldReceive('all')->once()->andReturn($fake);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->get(self::API_VI . 'post')->response;
        $this->assertResponse($response, '{"success":true,"data":[{"id":1,"title":"title 1","body":"body 1","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}]}');
    }

    public function testSearchAllPosOnlyCount()
    {
        $fake = array($this->fake_post);
        $this->mock->shouldReceive('all')->once()->andReturn($fake);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->get(self::API_VI . 'post?count_only=true')->response;
        $this->assertResponse($response, '{"success":true,"data":1}');
    }

    public function testSearchPostByTags()
    {
        $fake = array($this->fake_post);
        $this->mock->shouldReceive('search_by_tag')->once()->andReturn($fake);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->get(self::API_VI . 'post?tags=1&count_only=false')->response;
        $this->assertResponse($response, '{"success":true,"data":[{"id":1,"title":"title 1","body":"body 1","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}]}');

    }
    public function testSearchPostByTagsOnlyCount()
    {
        $fake = array($this->fake_post);
        $this->mock->shouldReceive('search_by_tag')->once()->andReturn(count($fake));
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->get(self::API_VI . 'post?tags=1&count_only=true')->response;
        $this->assertResponse($response, '{"success":true,"data":1}');
    }

    public function testGetById()
    {
        $this->mock->shouldReceive('find')->with('1')->once()->andReturn($this->fake_post);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->get(self::API_VI . 'post/1')->response;
        $this->assertResponse($response, '{"success":true,"data":{"id":1,"title":"title 1","body":"body 1","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}}');
    }
    public function testPost()
    {
        $this->mock->shouldReceive('create')->once()
                   ->andReturn($this->fake_post);
        $this->app->instance('App\Repositories\Post\IPostRepository',
                             $this->mock);
        unset($this->fake_post["id"]);
        $response = $this->post(self::API_VI . 'post', $this->fake_post)->response;
        $this->assertResponse($response, '{"success":true,"data":{"id":1,"title":"title 1","body":"body 1","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}}', 201);
    }

    public function testPostWithMissingParameters()
    {
        $parameters = array();
        $response = $this->post(self::API_VI . 'post', $parameters, ["Accept" => 'application/json'] )->response;
        $this->assertResponse($response,
                             '{"title":["The title field is required."],"body":["The body field is required."]}', 422);
    }

    public function testDelete()
    {
        $this->mock->shouldReceive('delete')->with('1')->once()->andReturn($this->fake_post);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->delete(self::API_VI . 'post/1')->response;
        $this->assertResponse($response, '', 204);
    }

    public function testDeleteWithErrorPost()
    {
        $response = $this->delete(self::API_VI . 'post/52')->response;
        $this->assertResponse($response, '{"success":false,"errors":"Post not found"}', 404);
    }

    public function testPut()
    {
        $this->update_mock = Mockery::mock('App\Repositories\Post\IPostRepository');
        $this->update_mock->shouldReceive('jsonSerialize')->once();
        $this->update_mock->shouldReceive('setAttribute')->times(2);
        $this->update_mock->shouldReceive('save')->once();
        $this->mock->shouldReceive('update')->once()->andReturn($this->update_mock);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $parameters = [ 'title' => 'title updated 2',
                        'body' => 'body updated 2'];
        $response = $this->put(self::API_VI . 'post/1', $parameters, ["Accept" => 'application/json'])->response;
        $this->assertResponse($response, '{"success":true,"data":{}}', 200);
    }

    public function testPatch()
    {
        $this->update_mock = Mockery::mock('App\Repositories\Post\IPostRepository');
        $this->update_mock->shouldReceive('jsonSerialize')->once();
        $this->update_mock->shouldReceive('setAttribute')->times(2);
        $this->update_mock->shouldReceive('save')->once();
        $this->mock->shouldReceive('update')->once()->andReturn($this->update_mock);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $parameters = [ 'title' => 'title updated 2',
                        'body' => 'body updated 2'];
        $response = $this->patch(self::API_VI . 'post/1', $parameters, ["Accept" => 'application/json'])->response;
        $this->assertResponse($response, '{"success":true,"data":{}}', 200);
    }

    public function testUpdateWithMissingParameters()
    {
        $this->update_mock = Mockery::mock('App\Repositories\Post\IPostRepository');
        $this->mock->shouldReceive('update')->once()->andReturn($this->update_mock);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->put(self::API_VI . 'post/1', [], ["Accept" => 'application/json'])->response;
        $this->assertResponse($response, '{"title":["The title field is required."],"body":["The body field is required."]}', 422);
    }

    /*public function testAddTags()
    {
        $this->fake_post_tag = [
            'id' => 1,
            'title' => 'title 1',
            'body' => 'body 1',
            'created_at' => '-0001-11-30 00:00:00',
            'updated_at' => '-0001-11-30 00:00:00',
            'pivot' => [ 'post_id' => 1, 'tag' => 1 ]
            ];
        $this->mock->shouldReceive('add_tags')->once()->andReturn($this->fake_post);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->post(self::API_VI . 'post/2/tags', ['tag' => 1])->response;
        $this->assertResponse($response, '{"success":true,"data":{"id":1,"title":"title 1","body":"body 1","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00","pivot":{"post_id":1,"tag":1}}}');
    }*/

    public function testAddTagsFail()
    {
        $this->mock->shouldReceive('add_tags')->once()->andReturn([]);
        $this->app->instance('App\Repositories\Post\IPostRepository', $this->mock);
        $response = $this->post(self::API_VI . 'post/2/tags')->response;
        $this->assertResponse($response, '{"success":false,"errors":"An error has occurred. Try again."}', 404);
    }
}

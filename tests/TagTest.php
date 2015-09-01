<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\Tag\ITagRepository as ITagRepository;
class TagTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->fake_tag = [
            'id' => 1,
            'name' => 'test',
            'created_at' => '-0001-11-30 00:00:00',
            'updated_at' => '-0001-11-30 00:00:00'
        ];
        $this->mock = Mockery::mock('App\Repositories\Tag\ITagRepository');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testSearchAllTag()
    {
        $fake = array($this->fake_tag);
        $this->mock->shouldReceive('search_by')->once()->andReturn($fake);
        $this->app->instance('App\Repositories\Tag\ITagRepository', $this->mock);
        $response = $this->get(self::API_VI . 'tag')->response;
        $this->assertResponse($response, '{"success":true,"data":[{"id":1,"name":"test","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}]}');
    }

    public function testSearchTagByName()
    {
        $fake = array($this->fake_tag);
        $this->mock->shouldReceive('search_by')->once()->andReturn($fake);
        $this->app->instance('App\Repositories\Tag\ITagRepository', $this->mock);
        $response = $this->get(self::API_VI . 'tag?name=test')->response;
        $this->assertResponse($response, '{"success":true,"data":[{"id":1,"name":"test","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}]}');

    }

    public function testGetById()
    {
        $this->mock->shouldReceive('find')->with('1')->once()->andReturn($this->fake_tag);
        $this->app->instance('App\Repositories\Tag\ITagRepository', $this->mock);
        $response = $this->get(self::API_VI . 'tag/1')->response;
        $this->assertResponse($response, '{"success":true,"data":{"id":1,"name":"test","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}}');
    }
    public function testPost()
    {
        $this->mock->shouldReceive('create')->once()
                   ->andReturn($this->fake_tag);
        $this->app->instance('App\Repositories\Tag\ITagRepository',
                             $this->mock);
        unset($this->fake_tag["id"]);
        $response = $this->post(self::API_VI . 'tag', $this->fake_tag)->response;
        $this->assertResponse($response, '{"success":true,"data":{"id":1,"name":"test","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}}', 201);
    }

    public function testPostWithMissingParameters()
    {
        $parameters = array();
        $response = $this->post(self::API_VI . 'tag', $parameters, ["Accept" => 'application/json'] )->response;
        $this->assertResponse($response,'{"name":["The name field is required."]}', 422);
    }

    public function testDelete()
    {
        $this->mock->shouldReceive('delete')->with('1')->once()->andReturn($this->fake_tag);
        $this->app->instance('App\Repositories\Tag\ITagRepository', $this->mock);
        $response = $this->delete(self::API_VI . 'tag/1')->response;
        $this->assertResponse($response, '', 204);
    }

    public function testDeleteWithErrorPost()
    {
        $response = $this->delete(self::API_VI . 'tag/52')->response;
        $this->assertResponse($response, '{"success":false,"errors":"Tag not found"}', 404);
    }

    public function testPut()
    {
        $this->fake_tag_updated = [
            'id' => 1,
            'name' => 'test 2',
            'created_at' => '-0001-11-30 00:00:00',
            'updated_at' => '-0001-11-30 00:00:00'
        ];
        $this->mock->shouldReceive('update')->once()->andReturn($this->fake_tag_updated);
        $this->app->instance('App\Repositories\Tag\ITagRepository', $this->mock);
        $parameters = array(
           'name' => 'test 2',
        );
        $response = $this->put(self::API_VI . 'tag/1', $parameters);
        $this->assertResponse($response->response, '{"success":true,"data":{"id":1,"name":"test 2","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}}', 200);
    }

    public function testPatch()
    {
        $this->fake_tag_updated = [
            'id' => 1,
            'name' => 'test 2',
            'created_at' => '-0001-11-30 00:00:00',
            'updated_at' => '-0001-11-30 00:00:00'
        ];
        $this->mock->shouldReceive('update')->once()->andReturn($this->fake_tag_updated);
        $this->app->instance('App\Repositories\Tag\ITagRepository', $this->mock);
        $parameters = array(
           'name' => 'test 2',
        );
        $response = $this->patch(self::API_VI . 'tag/1', $parameters);
        $this->assertResponse($response->response, '{"success":true,"data":{"id":1,"name":"test 2","created_at":"-0001-11-30 00:00:00","updated_at":"-0001-11-30 00:00:00"}}', 200);
    }

    public function testUpdateWithMissingParameters()
    {
        $response = $this->put(self::API_VI . 'tag/1', [], ["Accept" => 'application/json'])->response;
        $this->assertResponse($response, '{"name":["The name field is required."]}', 422);
    }

}

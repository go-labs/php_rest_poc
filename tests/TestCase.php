<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost:8000';
    const API_VI = '/api/v1/';
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function assertResponse($response, $expected_body, $expected_status = 200)
    {
        $body = $response->getContent();
        $this->assertEquals($expected_body, $body);
        $this->assertResponseStatus($expected_status);
    }
}

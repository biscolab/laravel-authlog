<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - AuthLogMiddlewareTest.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 23/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Tests;

use Biscolab\LaravelAuthLog\Facades\AuthLog;
use Biscolab\LaravelAuthLog\Middleware\AuthLogMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthLogMiddlewareTest extends TestCase
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var AuthLogMiddleware
     */
    protected $middleware;

    /**
     * @test
     */
    public function testAddAuthLogIdHeaderToResponse()
    {

        $response = new Response();

        $response = $this->middleware->handle($this->request, function () use ($response) {

            return $response;
        });
        $this->assertInstanceOf('Illuminate\Http\Response', $response);
        $this->assertEquals(env('DEFAULT_AUTH_LOG_ID'), $response->headers->get(config('authlog.auth_log_id_header_name')));
    }

    protected function tearDown(): void
    {

        parent::tearDown();
    }

    protected function setUp(): void
    {

        parent::setUp();

        $this->request = new Request();

        $this->middleware = new AuthLogMiddleware();

        AuthLog::shouldReceive('getAuthLogId')
            ->andReturn(env('DEFAULT_AUTH_LOG_ID'));

        AuthLog::shouldReceive('getCurrentSessionId')
            ->andReturn(env('DEFAULT_SESSION_ID'));

    }
}
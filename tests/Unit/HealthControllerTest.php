<?php

namespace Tests\Unit;

use function config;
use Symfony\Component\HttpFoundation\Response as IlluminateResponse;
use Tests\TestCase;

class HealthControllerTest extends TestCase
{
    /** @test */
    public function it_should_always_return_true()
    {
        $response = $this->get('/');

        $response->assertStatus(IlluminateResponse::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'message' => 'Request successfully completed.',
                    'version' => config('app.version'),
                ],
                'success' => true,
                'status_code' => IlluminateResponse::HTTP_OK,
            ]);
    }
}

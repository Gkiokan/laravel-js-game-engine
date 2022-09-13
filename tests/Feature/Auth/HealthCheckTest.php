<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{

    public function test_check_hearthbeath_endpoint()
    {
        $response = $this->get(env('APP_API_URL') . '/hb');
        $response->assertStatus(200);
    }

}

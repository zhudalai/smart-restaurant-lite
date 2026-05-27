<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302); // redirects to /pos
    }

    public function test_pos_page_returns_200(): void
    {
        $response = $this->get('/pos');
        $response->assertStatus(200);
    }
}

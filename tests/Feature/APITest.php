<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class APITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetDivisions()
    {
        $response = $this->get('/api/getDivisions');
        // $response->dump();
        $response->assertStatus(200);
        $response->assertJsonStructure([
                '*' => [
                'id', 'is_active', 'parish', 'town'
                ]
        ]);
        
    }
}

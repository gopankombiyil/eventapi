<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testEventsApi()
    {
        $response1 = $this->get('/api/events');
        $response1->assertStatus(200);
        $response1->assertJsonCount(8);

        $response2 = $this->get('/api/events/place-1');

        $response2->assertStatus(200);
        $response2->assertJson([
                'Title' => 'Place 1',
            ]);

        $response3 = $this->get('/api/events/place-1?keyword=Place');
        $response3->assertStatus(200);

        $response4 = $this->get('/api/events/place-1?keyword=Place 1');
        $response4->assertStatus(200);
        $response4->assertJsonCount(4);
        $response4->assertJson([
                'Title' => 'Place 1',
            ]);
    }
}

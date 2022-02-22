<?php

namespace Tests\Feature;

use App\Models\Movies;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;
use Tests\TestCase;

class MoviesTest extends TestCase
{
    use WithFaker, RefreshDatabase, CreatesApplication;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected bool $seed = true;

    /**
     * A basic feature test for add a movie.
     * @test
     * @return void
     */
    public function a_request_can_create_a_movie(): void
    {
        $attributes = Movies::factory()->make()->getAttributes();
        $response = $this->post('/api/add/movie', $attributes);
        $this->assertDatabaseHas('movies', $attributes);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test for add a incomplete movie.
     * @test
     * @return void
     */
    public function a_incomplete_request_cant_create_a_movie(): void
    {
        $attributes = [
            "title" => $this->faker->title
        ];
        $response = $this->post('/api/add/movie', $attributes);
        $this->assertDatabaseMissing('movies', $attributes);
        $response->assertStatus(400);
    }

    /**
     * A basic feature test for list movies.
     * @test
     * @return void
     */
    public function a_list_of_movies(): void
    {
        $response = $this->get('/api/movies');
        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }
}

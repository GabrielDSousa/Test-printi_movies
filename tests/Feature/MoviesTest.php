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
            "title" => $this->faker->sentence(3)
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

    /**
     * A basic feature test for list movies with filters.
     * @test
     * @return void
     */
    public function a_filtered_list_of_movies(): void
    {
        $attributes = [
            "title" => $this->faker->sentence(3),
            "category" => $this->faker->word()
        ];
        $response = $this->post("/api/add/movie", $attributes);
        $this->assertDatabaseHas("movies", $attributes);
        $response->assertStatus(200);

        $response = $this->get("/api/movies?title={$attributes['title']}");
        $response->assertSeeText($attributes['title']);

        $response = $this->get("/api/movies?category={$attributes['category']}");
        $response->assertSeeText($attributes['category']);

        $response = $this->get("/api/movies?category={$attributes['category']}&title={$attributes['title']}");
        $response->assertSeeText($attributes['title']);
        $response->assertSeeText($attributes['category']);
    }

    /**
     * A basic feature test for list movies with wrong filters.
     * @test
     * @return void
     */
    public function a_wrong_filtered_list_of_movies(): void
    {
        $attributes = [
            "title" => $this->faker->sentence(3),
            "category" => $this->faker->word()
        ];
        $response = $this->post("/api/add/movie", $attributes);
        $this->assertDatabaseHas("movies", $attributes);
        $response->assertStatus(200);

        $response = $this->get("/api/movies?title=RandomTextDifferentOfAnyTitle");
        $response->assertDontSeeText($attributes['title']);

        $response = $this->get("/api/movies?category=RandomTextDifferentOfAnyCategory}");
        $response->assertDontSeeText($attributes['category']);

        $response = $this->get("/api/movies?category=RandomTextDifferentOfAnyCategory&title=RandomTextDifferentOfAnyTitle");
        $response->assertDontSeeText($attributes['title']);
        $response->assertDontSeeText($attributes['category']);
    }
}

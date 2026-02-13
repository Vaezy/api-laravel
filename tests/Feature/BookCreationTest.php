<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BookCreationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_book_with_valid_data(): void
    {
      $user = User::factory()->create();

        $data = [
            'title' => 'Title number 1',
            'author' => 'Author number 1',
            'summary' => 'Summary number 1',
            'isbn' => '9780553293357',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/books', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('books', [
            'title' => 'Title number 1',
        ]);
    }
    public function test_book_with_invalid_data(): void
    {
       $user = User::factory()->create();

        $data = [
            'title' => 'Ti',
            'author' => 'Author number 1',
            'summary' => 'Summary number 1',
            'isbn' => '9780553293357',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/books', $data);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('books', [
            'title' => 'Ti',
        ]);
    }
    public function test_book_user_not_authenticated()
    {
        $data = [
            'title' => 'Title number 1',
            'author' => 'Author number 1',
            'summary' => 'Summary number 1',
            'isbn' => '9780553293357',
        ];

        $response = $this->postJson('/api/v1/books', $data);
        $response->assertStatus(401);
        $this->assertDatabaseCount('books', 0);
    }
}

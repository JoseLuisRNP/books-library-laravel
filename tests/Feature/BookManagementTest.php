<?php

namespace Tests\Feature;

use App\Book;
use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_the_library() {

        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author_id' => 1,
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required() {

        $response = $this->post('/books', [
            'title' => '',
            'author_id' => 'Victor'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required() {

        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author_id' => ''
        ]);

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated(){

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author_id' => 1,
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(),[
            'title' => 'New Title',
            'author_id' => 'New author',
        ]);
        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted(){

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author_id' => 1,
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/'.$book->id);

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    /** @test */
    public function a_new_author_is_automatically_added(){
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author_id' => 'Victor'
        ]);

        $book = Book::first();
        $author = Author::first();


        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }
}

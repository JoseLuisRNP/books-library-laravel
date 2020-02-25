<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function store() {
        Book::create($this->validateFields());
    }

    public function update(Book $book) {

        $book->update($this->validateFields());
    }

    protected function validateFields() {
        return request()->validate([
            'title' => 'required',
            'author' => 'required',
        ]);
    }
}

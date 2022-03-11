<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\Book as BookResource;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(10);
        return BookResource::collection($books);
    }

    public function show($id) {
        $book = Book::findOrFail( $id );
        return new BookResource($book);
    }

    public function store(String $author = null, String $genre = null)
    {
        try {
            $array = [];

            if (empty($author) && empty($genre)) {
                $response = "MISSING_DATA";

                return $response;
            } elseif (!empty($author) && !empty($genre)) {
                $array['name'] = $author;
                $array['genre'] = $genre;
            } elseif (!empty($author)) {
                $array['name'] = $author;
            } else {
                $array['genre'] = $genre;
            }

            $response = Http::get(
                'https://api.lib.harvard.edu/v2/items.dc.json?limit=10',
                $array
            );

            return json_decode($response)->items;

        } catch (Exception $e) {
            return 'Error ' . $e;
        }
    }
}

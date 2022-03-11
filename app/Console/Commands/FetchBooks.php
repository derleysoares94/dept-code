<?php

namespace App\Console\Commands;

use App\Http\Controllers\BookController;
use Illuminate\Console\Command;
use App\Models\Book;

class FetchBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'harvard:fetch_books {author?} {genre?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch books from Harvard Library API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $author = $this->argument('author') ?? '';
        $genre = $this->argument('genre') ?? '';

        $books = new BookController();
        $ret = $books->store($author, $genre);

        if($ret == 'MISSING_DATA') {
            $response = 'Neither Author nor Genre was specified.';
        } elseif(is_null($ret)) {
            $response = "We didn't find any book.";
        } else {
            $books = $ret->dc;

            if(is_array($books)) {
                foreach($books as $book) {
                    if(isset($book->title)) {
                        $title = is_array($book->title) ? str_replace('.', '', implode(' - ', $book->title)) : $book->title;
                    }

                    if(isset($book->creator)) {
                        $creator = is_array($book->creator) ? str_replace('.', '', implode(' - ', $book->creator)) : $book->creator;
                    }

                    if(isset($book->type)) {
                        $type = is_array($book->type) ? str_replace('.', '', implode(' - ', $book->type)) : $book->type;
                    }

                    if(isset($book->description)) {
                        $description = is_array($book->description) ?
                        str_replace('--', '-',str_replace("'", "",str_replace('"', '', implode(' - ', $book->description))))
                        : $book->description;
                    }

                    $attributes = [
                        'title' => $title,
                        'creator' => $creator ?? 'Missing creator inf',
                        'type' => $type,
                        'description' => $description
                    ];

                    if(!Book::where('title', $title)->exists()){
                        Book::create($attributes);
                    }
                }
            } else {
                if(isset($books->title)) {
                    $title = is_array($books->title) ? str_replace('.', '', implode(' - ', $books->title)) : $books->title;
                }

                if(isset($books->creator)) {
                    $creator = is_array($books->creator) ? str_replace('.', '', implode(' - ', $books->creator)) : $books->creator;
                }

                if(isset($books->type)) {
                    $type = is_array($books->type) ? str_replace('.', '', implode(' - ', $books->type)) : $books->type;
                }

                if(isset($books->description)) {
                    $description = is_array($books->description) ? str_replace('--', '-',str_replace("'", "",str_replace('"', '', implode(' - ', $books->description)))) : $books->description;
                }

                $attributes = [
                    'title' => $title,
                    'creator' => $creator ?? 'Missing creator inf',
                    'type' => $type,
                    'description' => $description
                ];

                if(!Book::where('title', $title)->exists()){
                    Book::create($attributes);
                }
            }

            $response = 'Success.';
        }

        $this->info($response);
    }
}

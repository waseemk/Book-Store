<?php
namespace App\Services;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BookService {
    
    /**
     * Import sale product records from the file into sale product Import Table with error related to each row (If Any)
     *
     * @param   string  $file_location   The location of the file on the local server
     * @return  mixed  Boolean TRUE if the file could be imported, array of formatting error msgs otherwise
     */
    public function filterBooks($filters) {

        $search         = isset($filters['search']) ? strtolower($filters['search']) : '';
        $status         = isset($filters['status']) ? strtolower($filters['status']) : '';
        $author_id      = isset($filters['author_id']) ? strtolower($filters['author_id']) : '';
        $genre_id       = isset($filters['genre_id']) ? strtolower($filters['genre_id']) : '';
        $publisher_id   = isset($filters['publisher_id']) ? strtolower($filters['publisher_id']) : '';
        $published      = isset($filters['published']) ? strtolower($filters['published']) : '';

        $books = Book::query();

        if($search) {
            $books->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%'.strtolower($search).'%']);
                $query->orWhere('isbn', 'LIKE', '%' . $search . '%');
            });
        }

        if($status) {
            $active = ($status == 'active') ? 1 : 0;
            $books->where('status', $active);
        }

        if($author_id) {
            $books->where('author_id', $author_id);
        }

        if($genre_id) {
            $books->where('genre_id', $genre_id);
        }

        if($publisher_id) {
            $books->where('publisher_id', $publisher_id);
        }

        if($published) {
            $books->where('published', $published);
        }

        return ['books' => $books];

    }
}

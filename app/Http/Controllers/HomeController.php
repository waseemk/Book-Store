<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\BookService;

class HomeController extends Controller
{
    public function __construct() {
        $this->bookService = new BookService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $request->validate([
            'search' => 'nullable|string|max:100|regex:/[a-zA-Z0-9\s]+/',
            'author_id' => 'nullable|string|in:'.implode(',', Author::where('status', 1)->pluck('id')->all()),
            'genre_id' => 'nullable|string|in:'.implode(',', Genre::where('status', 1)->pluck('id')->all()),
            'publisher_id' => 'nullable|string|in:'.implode(',', Publisher::where('status', 1)->pluck('id')->all()),
            'published' => 'nullable|date_format:Y-m-d',
        ]);

        $filters['search'] = $request->input('search', ''); // Get search query parameter
        $filters['status'] = 'active'; // Get status query parameter
        $filters['author_id'] = $request->input('author_id', ''); // Get author query parameter
        $filters['genre_id'] = $request->input('genre_id', ''); // Get genre query parameter
        $filters['publisher_id'] = $request->input('publisher_id', ''); // Get publisher_id query parameter
        $filters['published'] = $request->input('published', ''); // Get published query parameter

        $filterData = $this->bookService->filterBooks($filters);

        $books = $filterData['books'];
        $books = $books->orderBy('title', 'asc')->with(['author', 'genre', 'publisher'])->paginate(100)->withQueryString();

        return Inertia::render('Home', [
            "filters" => $filters,
            'books' => $books,
            'authors' => Author::where('status', 1)->orderBy('name')->get(),
            'genres' => Genre::where('status', 1)->orderBy('name')->get(),
            'publishers' => Publisher::where('status', 1)->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the book detail.
     */
    public function detail(string $isbn)
    {
        $book = Book::where('isbn', $isbn)->where('status', 1)->with(['author', 'genre', 'publisher'])->firstOrFail();
        return Inertia::render('BookDetail', [
            'book' => $book,
        ]);
    }
}

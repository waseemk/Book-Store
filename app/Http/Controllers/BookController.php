<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Jobs\ImportBook;
use App\Services\BookService;
use Storage;

class BookController extends Controller
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
            'status' => 'nullable|string|in:active,inactive',
            'author_id' => 'nullable|string|in:'.implode(',', Author::where('status', 1)->pluck('id')->all()),
            'genre_id' => 'nullable|string|in:'.implode(',', Genre::where('status', 1)->pluck('id')->all()),
            'publisher_id' => 'nullable|string|in:'.implode(',', Publisher::where('status', 1)->pluck('id')->all()),
            'per_page' => 'nullable|integer|in:25,50,100',
        ]);

        $filters['search'] = $request->input('search', ''); // Get search query parameter
        $filters['status'] = $request->input('status', ''); // Get status query parameter
        $filters['author_id'] = $request->input('author_id', ''); // Get author query parameter
        $filters['genre_id'] = $request->input('genre_id', ''); // Get genre query parameter
        $filters['publisher_id'] = $request->input('publisher_id', ''); // Get publisher_id query parameter
        $per_page = $request->input('per_page', '25'); // Get published query parameter
        $filters['per_page'] = $per_page;

        $filterData = $this->bookService->filterBooks($filters);

        $books = $filterData['books'];
        $books = $books->orderBy('title', 'asc')->with(['author', 'genre', 'publisher'])->paginate($per_page)->withQueryString();

        return Inertia::render('Books/List', [
            "filters" => $filters,
            'books' => $books,
            'authors' => Author::where('status', 1)->orderBy('name')->get(),
            'genres' => Genre::where('status', 1)->orderBy('name')->get(),
            'publishers' => Publisher::where('status', 1)->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Books/Create', [
            'authors' => Author::where('status', 1)->orderBy('name')->get(),
            'genres' => Genre::where('status', 1)->orderBy('name')->get(),
            'publishers' => Publisher::where('status', 1)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100|regex:/[a-zA-Z0-9\s]+/',
            'isbn' => 'required|string|unique:books,isbn|regex:/[a-zA-Z0-9\s]+/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_id' => 'required|integer|exists:authors,id',
            'genre_id' => 'required|integer|exists:genres,id',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'description' => 'required|string|regex:/[a-zA-Z0-9\s]+/',
            'published' => 'required|date_format:Y-m-d',
            'status' => 'required|integer|in:0,1',
        ]);

        $input = $request->all();

        if($request->hasFile('image')) {
            $file = $request->image->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = $request->image->getClientOriginalExtension();
            
            $nameWithoutSpace = str_replace(' ', '-', $filename); // Replaces all spaces with hyphens.
            $nameWithoutSpChar = preg_replace('/[^A-Za-z0-9\-]/', '', $nameWithoutSpace); // Removes special chars.
            
            $logoName = time().'_'.$nameWithoutSpChar;
            $logoName = (strlen($logoName) > 60) ? substr($logoName,0,60) : $logoName;

            $image = $request->file('image');
            
            $imageName = $logoName.'.'.$extension;
            Storage::disk('public')->put('images/books/'.$imageName, file_get_contents($image));
            $input['image'] = $imageName;
        }
        $book  = Book::create($input);
        if (!empty($book)) {
            return redirect()->route('books')->with('success', 'Book created successfully');
        } else {
            return back()->with('error', 'Error Occurred');
            
        }
        return back();
    }

    /**
     * Update Status.
     */
    public function updateStatus(int $id)
    {
        try {
            $book = Book::findOrFail($id);

            $status = $book->status;
            $status = $status ? 0 : 1;

            $book->status = $status;
            $book->save();

            return response()->json([
                'success' =>true,
                'status' => $status
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }
        
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $book = Book::findOrFail($id);
        return Inertia::render('Books/Edit', [
            'book' => $book,
            'authors' => Author::where('status', 1)->orderBy('name')->get(),
            'genres' => Genre::where('status', 1)->orderBy('name')->get(),
            'publishers' => Publisher::where('status', 1)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        

        $request->validate([
            'title' => 'required|string|max:100|regex:/[a-zA-Z0-9\s]+/',
            'isbn' => 'required|string|unique:books,isbn,' . $id . ',id|regex:/[a-zA-Z0-9\s]+/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_id' => 'required|integer|exists:authors,id',
            'genre_id' => 'required|integer|exists:genres,id',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'description' => 'required|string|regex:/[a-zA-Z0-9\s]+/',
            'published' => 'required|date_format:Y-m-d',
            'status' => 'required|integer|in:0,1',
        ]);

        $book = Book::findOrFail($id);
        $book->title = $request->title;
        $book->isbn = $request->isbn;
        $book->author_id = $request->author_id;
        $book->genre_id = $request->genre_id;
        $book->publisher_id = $request->publisher_id;
        $book->description = $request->description;
        $book->published = $request->published;
        $book->status = $request->status;

        if($request->hasFile('image')) {

            if (Storage::disk('public')->exists("images/books/".$book->image)) {
                Storage::disk('public')->delete("images/books/".$book->image);
            }

            $file = $request->image->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $extension = $request->image->getClientOriginalExtension();
            
            $nameWithoutSpace = str_replace(' ', '-', $filename); // Replaces all spaces with hyphens.
            $nameWithoutSpChar = preg_replace('/[^A-Za-z0-9\-]/', '', $nameWithoutSpace); // Removes special chars.
            
            $logoName = time().'_'.$nameWithoutSpChar;
            $logoName = (strlen($logoName) > 60) ? substr($logoName,0,60) : $logoName;

            $image = $request->file('image');
            
            $imageName = $logoName.'.'.$extension;
            Storage::disk('public')->put('images/books/'.$imageName, file_get_contents($image));
            $book->image = $imageName;
        }
        $book->save();

        return back()->with('success', 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $book = Book::findOrFail($id);

            $book->delete();

            return response()->json([
                'success' =>true,
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }
    }
    /**
     * Import book dataset.
     */
    public function importData()
    {
        ImportBook::dispatch(\Auth::user());
        return back()->with('success', 'Import Book Data  - An email will be sent when completed.');
    }
}

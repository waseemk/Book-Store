<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100|regex:/[a-zA-Z0-9\s]+/',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $search = $request->input('search', ''); // Get search query parameter
        $status = $request->input('status', ''); // Get status query parameter

        $qrySearch = Author::query();

        if($search) {
            $qrySearch->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($search).'%']);
            });
        }

        if($status) {
            $active = ($status == 'active') ? 1 : 0;
            $qrySearch->where('status', $active);
        }

        $qrySearch->orderBy('name', 'asc');

        $authors = $qrySearch->paginate(25)->withQueryString();
        
        return Inertia::render('Author/List', [
            "filters" => [
                'search' => $search, // Pass the search query to the view
                'status' => $status, // Pass the status query to the view
            ],
            'authors' => $authors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Author/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|regex:/[a-zA-Z0-9\s]+/',
            'status' => 'required|integer|in:0,1',
        ]);

        $input = $request->all();
        $author  = Author::create($input);
        if (!empty($author)) {
            return redirect()->route('authors')->with('success', 'Author created successfully');
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
            $author = Author::findOrFail($id);

            $status = $author->status;
            $status = $status ? 0 : 1;

            $author->status = $status;
            $author->save();

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
        $author = Author::findOrFail($id);
        return Inertia::render('Author/Edit', [
            'author' => $author
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        

        try {
            $author = Author::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:100|regex:/[a-zA-Z0-9\s]+/',
                'status' => 'required|integer|in:0,1',
            ]);

            $author->name = $request->name;
            $author->status = $request->status;
            $author->save();

            return back()->with('success', 'Author updated successfully');
            
        } catch(\Exception $e) {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $author = Author::findOrFail($id);

            $author->delete();

            return response()->json([
                'success' =>true,
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }
    }
}

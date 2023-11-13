<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PublisherController extends Controller
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

        $qrySearch = Publisher::query();

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

        $publishers = $qrySearch->paginate(25)->withQueryString();
        
        return Inertia::render('Publisher/List', [
            "filters" => [
                'search' => $search, // Pass the search query to the view
                'status' => $status, // Pass the status query to the view
            ],
            'publishers' => $publishers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Publisher/Create');
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
        $publisher  = Publisher::create($input);
        if (!empty($publisher)) {
            return redirect()->route('publishers')->with('success', 'Publisher created successfully');
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
            $publisher = Publisher::findOrFail($id);

            $status = $publisher->status;
            $status = $status ? 0 : 1;

            $publisher->status = $status;
            $publisher->save();

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
        $publisher = Publisher::findOrFail($id);
        return Inertia::render('Publisher/Edit', [
            'publisher' => $publisher
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $publisher = Publisher::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:100|regex:/[a-zA-Z0-9\s]+/',
                'status' => 'required|integer|in:0,1',
            ]);

            $publisher->name = $request->name;
            $publisher->status = $request->status;
            $publisher->save();

            return back()->with('success', 'Publisher updated successfully');
            
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
            $publisher = Publisher::findOrFail($id);

            $publisher->delete();

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

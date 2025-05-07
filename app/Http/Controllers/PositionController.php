<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of positions.
     */
    public function index()
    {
        $positions = Position::all();
        return response()->json($positions);
    }

    /**
     * Store a newly created position.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'tunjangan' => 'required|numeric',
        ]);

        $position = Position::create($validated);

        return response()->json($position, 201);
    }

    /**
     * Display the specified position.
     */
    public function show($id)
    {
        $position = Position::findOrFail($id);
        return response()->json($position);
    }

    /**
     * Update the specified position.
     */
    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'tunjangan' => 'required|numeric',
        ]);

        $position->update($validated);

        return response()->json($position);
    }

    /**
     * Remove the specified position.
     */
    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json(null, 204);
    }
}

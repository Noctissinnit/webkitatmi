<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    public function index()
    {
        return view('positions.index');
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:positions,name',
            'description' => 'nullable|string',
        ]);

        Position::create($validated);
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:positions,name,' . $position->id,
            'description' => 'nullable|string',
        ]);

        $position->update($validated);
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus');
    }

    // API endpoint untuk DataTables
    public function getPositions()
    {
        $positions = Position::select('positions.*');

        return DataTables::of($positions)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editBtn = '<a href="' . route('positions.edit', $row->id) . '" title="Edit" style="color:#6b7280;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;">';
                $editBtn .= '<i class="fas fa-edit w-4 h-4"></i> Edit</a>';
                
                $deleteBtn = '<form action="' . route('positions.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Delete this position?\');">';
                $deleteBtn .= '<input type="hidden" name="_method" value="DELETE">';
                $deleteBtn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $deleteBtn .= '<button type="submit" title="Delete" style="color:#9ca3af;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;background:none;border:none;cursor:pointer;margin-left:1.5rem;">';
                $deleteBtn .= '<i class="fas fa-trash w-4 h-4"></i> Delete</button></form>';
                
                return '<div class="action-links justify-center" style="display:flex;gap:1.5rem;align-items:center;justify-content:center;">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

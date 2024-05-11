<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use Illuminate\Http\Request;

class PaysController extends Controller
{
    public function index()
    {
        $pays = Pays::all();
        return view('pays.index', compact('pays'));
    }

    public function create()
    {
        return view('pays.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        // Validation des données

        Pays::create($request->all());

        return redirect()->route('pays.index')->with('success', 'Pays ajouté avec succès.');
    }

    public function edit($id)
    {
        $pays = Pays::findOrFail($id);
        return view('pays.edit', compact('pays'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        // Validation des données

        $pays = Pays::findOrFail($id);
        $pays->update($request->all());

        return redirect()->route('pays.index')->with('success', 'Pays modifié avec succès.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        Pays::findOrFail($id)->delete();

        return redirect()->route('pays.index')->with('success', 'Pays supprimé avec succès.');
    }
}
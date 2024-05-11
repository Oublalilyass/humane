<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use Illuminate\Http\Request;

class PersonneController extends Controller
{
    public function index()
    {
        $personnes = Personne::all();
        return view('personnes.index', compact('personnes'));
    }
    // Autres méthodes d'action pour l'affichage
    public function create()
    {
        return view('personnes.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        Personne::create($request->all());

        return redirect()->route('personnes.index')
                         ->with('success', 'Personne ajoutée avec succès.');
    }

    public function show($id)
    {
        $personne = Personne::findOrFail($id);
        return view('personnes.show', compact('personne'));
    }

    public function edit($id)
    {
        $personne = Personne::findOrFail($id);
        return view('personnes.edit', compact('personne'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $personne = Personne::findOrFail($id);
        $personne->update($request->all());

        return redirect()->route('personnes.index')
                         ->with('success', 'Personne mise à jour avec succès.');
    }


    public function destroy($id)
    {
        if (auth()->user()->role === 'admin') {
            $personne = Personne::findOrFail($id);
            $personne->delete();
            
            return redirect()->route('personnes.index')->with('success', 'Personne supprimée avec succès.');
        } else {
            return redirect()->route('personnes.index')->with('error', 'Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.');
        }
    }
}
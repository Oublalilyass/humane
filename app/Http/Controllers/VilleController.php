<?php

namespace App\Http\Controllers;

use App\Models\Ville;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    

        public function index()
        {
            $villes = Ville::all();
            return view('villes.index', compact('villes'));
        }
    

        public function create()
        {
            return view('villes.create');
        }
    

        public function store(Request $request)
        {
            $request->validate([
                'nom' => 'required|string|max:255',
            ]);
    
            Ville::create($request->all());
    
            return redirect()->route('villes.index')
                             ->with('success', 'Ville ajoutée avec succès.');
        }
    
        public function show($id)
        {
            $ville = Ville::findOrFail($id);
            return view('villes.show', compact('ville'));
        }
    

        public function edit($id)
        {
            $ville = Ville::findOrFail($id);
            return view('villes.edit', compact('ville'));
        }
    

        public function update(Request $request, $id)
        {
            $request->validate([
                'nom' => 'required|string|max:255',
            ]);
    
            $ville = Ville::findOrFail($id);
            $ville->update($request->all());
    
            return redirect()->route('villes.index')
                             ->with('success', 'Ville mise à jour avec succès.');
        }
    
    
        public function destroy($id)
        {
            if (auth()->user()->role === 'admin') {
                $ville = Ville::findOrFail($id);
                $ville->delete();
                
                return redirect()->route('villes.index')->with('success', 'Ville supprimée avec succès.');
            } else {
                return redirect()->route('villes.index')->with('error', 'Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.');
            }
        }
        
}
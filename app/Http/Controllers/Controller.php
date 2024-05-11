<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Ville;
use App\Models\Personne;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Maatwebsite\Excel\Facades\Excel;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function ajouter(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:pays,ville,personne',
            'nom' => 'required|string|max:255',
        ]);
    
        $type = $validatedData['type'];
        $nom = $validatedData['nom'];
    
        switch ($type) {
            case 'pays':
                $entity = new Pays();
                break;
            case 'ville':
                $entity = new Ville();
                break;
            case 'personne':
                $entity = new Personne();
                break;
            default:
                break;
        }
    
        $entity->nom = $nom;
        // Ajouter d'autres champs si nécessaire
        $entity->save();
    
        // Redirection ou toute autre logique après l'ajout
        return redirect()->back()->with('success', ucfirst($type) . ' ajouté avec succès.');
    }



    public function exportCsv()
    {
    return Excel::download(new ExportData(), 'data.csv');
     }

    public function exportExcel()
    {
    return Excel::download(new ExportData(), 'data.xlsx');
    }


}

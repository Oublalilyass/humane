@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Formulaire d'ajout ou de modification -->
<div class="mb-3">
    <h2>{{ isset($entity) ? 'Modifier' : 'Ajouter' }}</h2>
    <form action="{{ isset($entity) ? route('modifier', $entity->id) : route('ajouter') }}" method="POST">
        @csrf
        @if(isset($entity))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="type">Sélectionner le type :</label>
            <select name="type" id="type" class="form-control">
                <option value="pays" {{ isset($entity) && $entity->type === 'pays' ? 'selected' : '' }}>Pays</option>
                <option value="villes" {{ isset($entity) && $entity->type === 'villes' ? 'selected' : '' }}>Ville</option>
                <option value="personnes" {{ isset($entity) && $entity->type === 'personnes' ? 'selected' : '' }}>Personne</option>
            </select>
        </div>
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom" value="{{ isset($entity) ? $entity->nom : '' }}">
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($entity) ? 'Modifier' : 'Ajouter' }}</button>
    </form>
</div>
     
        <h2>Liste des pays</h2>
        <table id="pays-table" class="table">
            <thead>
                <tr>
                    <th scope="col">Nom du pays</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pays as $pays)
                    <tr data-id="{{ $pays->id }}" class="pays-row">
                        <td>{{ $pays->nom }}</td>
                        <td>
                            <a href="{{ isset($entity) ? route('modifier', ['id' => $entity->id]) : route('ajouter') }}" class="btn btn-primary btn-sm edit-btn">
                                Modifier
                            </a>
                            
                            <button type="button" class="btn btn-danger btn-sm delete-btn">Supprimer</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="villes-container" style="display: none;">
            <h2>Liste des villes</h2>
            <table id="villes-table" class="table">
                <thead>
                    <tr>
                        <th scope="col">Nom de la ville</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="villes-body">
                    <!-- Les données des villes seront chargées ici via JavaScript -->
                </tbody>
            </table>
        </div>
        <div id="personnes-container" style="display: none;">
            <h2>Liste des personnes</h2>
            <table id="personnes-table" class="table">
                <thead>
                    <tr>
                        <th scope="col">Nom de la personne</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="personnes-body">
                    <!-- Les données des personnes seront chargées ici via JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- export data -->
        <div class="mb-3">
            <a href="{{ route('export.csv') }}" class="btn btn-primary">Exporter en CSV</a>
            <a href="{{ route('export.excel') }}" class="btn btn-primary">Exporter en Excel</a>
        </div>
    </div>
    </div>

    <!-- Modal de modification -->
    {{-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier le pays</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire de modification des pays -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Vérifier le rôle de l'utilisateur
        const isAdmin = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};

        // Écouteur de clic sur les lignes de la table des pays
        const paysRows = document.querySelectorAll('.pays-row');

        paysRows.forEach(row => {
            row.addEventListener('click', function () {
                const paysId = this.getAttribute('data-id');
                fetch(`/pays/${paysId}/villes`)
                    .then(response => response.json())
                    .then(data => {
                        const villesBody = document.getElementById('villes-body');
                        villesBody.innerHTML = '';
                        data.forEach(ville => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${ville.nom}</td>
                                <td>
                                    ${isAdmin ? `
                                        <button type="button" class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editModal">Modifier</button>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn">Supprimer</button>
                                    ` : ''}
                                </td>
                            `;
                            villesBody.appendChild(row);
                        });
                        document.getElementById('villes-container').style.display = 'block';
                        document.getElementById('personnes-container').style.display = 'none';
                    });
            });
        });
// Écouteur de clic sur les boutons de suppression dans la table des villes
const villesTable = document.getElementById('villes-table');

villesTable.addEventListener('click', function (e) {
    if (isAdmin && e.target.classList.contains('delete-btn')) {
        const confirmation = confirm('Êtes-vous sûr de vouloir supprimer cette ville ?');
        if (confirmation) {
            const villeId = e.target.closest('tr').getAttribute('data-id');
            // Effectuer une requête AJAX pour supprimer la ville
            fetch(`/delete-ville/${villeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    // Actualiser la page ou mettre à jour le tableau des villes
                    window.location.reload(); // Recharger la page
                    // Ou vous pouvez mettre à jour le tableau des villes sans recharger la page
                } else {
                    // Gérer les erreurs de suppression
                    console.error('Une erreur est survenue lors de la suppression de la ville.');
                }
            })
            .catch(error => {
                console.error('Erreur :', error);
            });
        }
    }
});


        // Écouteur de clic sur les boutons de modification dans la table des villes
        villesTable.addEventListener('click', function (e) {
            if (isAdmin && e.target.classList.contains('edit-btn')) {
                const villeId = e.target.closest('tr').getAttribute('data-id');
                // la logique de modification ici
            }
        });
    });
</script>

@endsection

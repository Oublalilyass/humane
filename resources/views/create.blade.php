@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Ajouter un nouveau pays</h2>
        <form action="{{ route('pays.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nom">Nom du pays</label>
                <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom du pays">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
@endsection

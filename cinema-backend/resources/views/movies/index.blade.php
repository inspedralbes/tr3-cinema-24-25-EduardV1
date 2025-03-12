@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Películas</h2>
                    <a href="{{ route('movies.create') }}" class="btn btn-primary">Nueva Película</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Duración</th>
                                    <th>Género</th>
                                    <th>Director</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movies as $movie)
                                <tr>
                                    <td>{{ $movie->title }}</td>
                                    <td>{{ $movie->duration }} min</td>
                                    <td>{{ $movie->genre }}</td>
                                    <td>{{ $movie->director }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-info btn-sm">Ver</a>
                                            <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('movies.destroy', $movie->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Sesiones</h2>
                    <a href="{{ route('sessions.create') }}" class="btn btn-primary">Nueva Sesión</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Película</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Sala</th>
                                    <th>Precio</th>
                                    <th>Asientos Disponibles</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                <tr>
                                    <td>{{ $session->movie->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($session->time)->format('H:i') }}</td>
                                    <td>{{ $session->room }}</td>
                                    <td>{{ number_format($session->price, 2) }}€</td>
                                    <td>{{ $session->available_seats }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sessions.show', $session->id) }}" class="btn btn-info btn-sm">Ver</a>
                                            <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('sessions.destroy', $session->id) }}" method="POST" class="d-inline">
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

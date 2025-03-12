@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Editar Sesión</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('sessions.update', $session->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="movie_id" class="form-label">Película</label>
                            <select class="form-select @error('movie_id') is-invalid @enderror" id="movie_id" name="movie_id" required>
                                <option value="">Selecciona una película</option>
                                @foreach($movies as $movie)
                                    <option value="{{ $movie->id }}" {{ (old('movie_id', $session->movie_id) == $movie->id) ? 'selected' : '' }}>
                                        {{ $movie->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('movie_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $session->date) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Hora</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror" id="time" name="time" value="{{ old('time', \Carbon\Carbon::parse($session->time)->format('H:i')) }}" required>
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room" class="form-label">Sala</label>
                            <input type="number" class="form-control @error('room') is-invalid @enderror" id="room" name="room" value="{{ old('room', $session->room) }}" required min="1">
                            @error('room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Precio (€)</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $session->price) }}" required min="0">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="available_seats" class="form-label">Asientos Disponibles</label>
                            <input type="number" class="form-control @error('available_seats') is-invalid @enderror" id="available_seats" name="available_seats" value="{{ old('available_seats', $session->available_seats) }}" required min="0">
                            @error('available_seats')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sessions.index') }}" class="btn btn-secondary">Volver</a>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

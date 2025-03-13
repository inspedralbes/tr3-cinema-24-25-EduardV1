@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Session
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">
                    Back to Sessions
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('admin.sessions.update', $session) }}" method="POST" class="space-y-8 divide-y divide-gray-200">
                @csrf
                @method('PUT')
                
                <div class="p-6">
                    @include('admin.sessions.form')
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end rounded-b-lg">
                    <button type="submit" class="btn btn-primary">
                        Update Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', $user->id ? 'Edit User' : 'Create User')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">{{ $user->id ? 'Edit User' : 'Create User' }}</h1>

    <form action="{{ $action }}" method="POST" class="space-y-4">
        @csrf
        @if($method === 'PUT')
            @method('PUT')
        @endif

        <div>
            <label class="block font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border px-3 py-2 w-full rounded" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border px-3 py-2 w-full rounded" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Role</label>
            <select name="role" class="border px-3 py-2 w-full rounded" required>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
            @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Password {{ $user->id ? '(leave blank to keep current)' : '' }}</label>
            <input type="password" name="password" class="border px-3 py-2 w-full rounded" {{ $user->id ? '' : 'required' }}>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" class="border px-3 py-2 w-full rounded" {{ $user->id ? '' : 'required' }}>
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                {{ $user->id ? 'Update' : 'Create' }}
            </button>
        </div>
    </form>
</div>
@endsection

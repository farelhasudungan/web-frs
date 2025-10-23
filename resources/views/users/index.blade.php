@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<style>
    /* Local styles agar konsisten dengan dashboard */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        outline: 6px solid rgba(0,0,0,1);
        border: 3px solid rgba(0,0,0,1);
        background: #fff;
    }

    .role-badge {
        display: inline-block;
        padding: .35rem .6rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: .85rem;
    }
    .role-admin { background: linear-gradient(135deg,#0d6efd 0%,#3aa0ff 100%); color: #fff; }
    .role-lecturer { background: linear-gradient(135deg,#20c997 0%,#4bd08f 100%); color: #fff; }
    .role-student { background: linear-gradient(135deg,#0dcaf0 0%,#7be6ff 100%); color: #06325a; }

    .small-muted { color: rgba(11,37,69,0.6); }
    .action-space > * { margin-right: .35rem; }
    @media (max-width: 767px) {
        .action-space { display:flex; flex-wrap:wrap; gap:.4rem; }
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-0">Manage Users</h1>
            <small class="small-muted">Admin panel — daftar user sistem</small>
        </div>

        <div class="text-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create New User</a>
        </div>
    </div>

    {{-- flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Search & filter --}}
    <form method="GET" class="row g-2 align-items-center mb-3">
        <div class="col-md-4 col-sm-6">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name or email">
        </div>
        <div class="col-md-3 col-sm-6">
            <select name="role" class="form-select">
                <option value="">All roles</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="lecturer" {{ request('role') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
        </div>
        <div class="col text-end small-muted">
            @if($users->total())
                Showing {{ $users->firstItem() }} - {{ $users->lastItem() }} of {{ $users->total() }}
            @else
                No users
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="card mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:6%;">#</th>
                            <th style="width:25%;">Name</th>
                            <th style="width:30%;">Email</th>
                            <th style="width:12%;">Role</th>
                            <th style="width:12%;">Since</th>
                            <th class="text-center" style="width:15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr id="user-row-{{ $user->id }}">
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="small-muted">{{ $user->username ?? '' }}</small>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleClass = 'role-student';
                                        if ($user->role === 'admin') $roleClass = 'role-admin';
                                        if ($user->role === 'lecturer') $roleClass = 'role-lecturer';
                                    @endphp
                                    <span class="role-badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</td>
                                <td class="text-center">
                                    <div class="action-space d-flex justify-content-center">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit user">Edit</a>

                                        {{-- Toggle active (optional endpoint) --}}
                                        @if(method_exists(App\Http\Controllers\Controller::class, '__construct') || true)
                                            {{-- leave placeholder for toggle if you implement route/admin.users.toggleActive --}}
                                        @endif

                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this);">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 small-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- footer with pagination --}}
            <div class="p-3 d-flex justify-content-between align-items-center">
                <div><small class="small-muted">Tip: Use search or filter to find users faster.</small></div>
                <div>
                    {{-- preserve query string when paginating --}}
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // simple confirm dialog for delete; you can replace with a custom modal if needed
    function confirmDelete(form) {
        return confirm('Are you sure you want to delete this user? This action cannot be undone.');
    }
</script>
@endsection

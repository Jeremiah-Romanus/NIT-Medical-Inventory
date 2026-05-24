@extends('layouts.layout')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Assign each account to the right system role and keep access organized from one place.')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">All registered users</h5>
            <span class="text-muted small">{{ $users->count() }} total users</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Current role</th>
                            <th>Joined</th>
                            <th class="text-end">Update role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->role === 'procurement' ? 'Procurement Officer' : ucfirst($user->role) }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-inline-flex gap-2 align-items-center justify-content-end">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-select form-select-sm" style="min-width: 200px;">
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                            <option value="pharmacist" @selected($user->role === 'pharmacist')>Pharmacist</option>
                                            <option value="procurement" @selected($user->role === 'procurement')>Procurement Officer</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No users available yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

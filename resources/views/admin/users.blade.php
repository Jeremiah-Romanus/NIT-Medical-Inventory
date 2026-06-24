@extends('layouts.layout')

@section('title', __('user.title'))
@section('page-title', __('user.title'))
@section('page-subtitle', __('user.subtitle'))

@section('content')
    <div class="card" x-data="{ search: '', showCreate: {{ $errors->any() ? 'true' : 'false' }} }">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="card-title">{{ __('user.title') }}</h5>
                <p class="text-muted mb-0">{{ __('user.subtitle') }}</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" @@click="showCreate = !showCreate">
                    {{ __('user.add_user') }}
                </button>
                <input type="text" x-model="search" class="form-control form-control-sm table-filter-input" placeholder="{{ __('common.search') }}">
                <span class="text-muted small">{{ $users->count() }} {{ __('user.total') }}</span>
            </div>
        </div>

        <div class="card-body p-0" x-show="showCreate" x-cloak>
            <div class="bg-light border-bottom p-4">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('user.full_name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('user.email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('user.role') }}</label>
                            <select name="role" class="form-select" required>
                                <option value="admin" @selected(old('role') === 'admin')>{{ __('role.admin') }}</option>
                                <option value="procurement" @selected(old('role') === 'procurement')>{{ __('role.procurement') }}</option>
                                <option value="pharmacist" @selected(old('role') === 'pharmacist')>{{ __('role.pharmacist') }}</option>
                            </select>
                            @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('user.password') }}</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('user.password_confirm') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('user.create_user') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('user.name') }}</th>
                            <th>{{ __('user.email') }}</th>
                            <th>{{ __('user.phone') }}</th>
                            <th>{{ __('user.role') }}</th>
                            <th>{{ __('user.joined') }}</th>
                            <th class="text-end">{{ __('user.update_role') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr x-show="!search || '{{ strtolower($user->name) }} {{ strtolower($user->email) }} {{ strtolower($user->role) }}'.includes(search.toLowerCase())" class="fade-in">
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    @switch($user->role)
                                        @case('admin') {{ __('role.admin') }} @break
                                        @case('procurement') {{ __('role.procurement') }} @break
                                        @default {{ __('role.pharmacist') }}
                                    @endswitch
                                </td>
                                <td>{{ \App\Helpers\DateHelper::formatDate($user->created_at) }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-flex flex-column gap-2">
                                            @csrf
                                            @method('PUT')

                                            <select name="role" class="form-select form-select-sm" style="min-width: 145px;">
                                                <option value="admin" @selected($user->role === 'admin')>{{ __('role.admin') }}</option>
                                                <option value="pharmacist" @selected($user->role === 'pharmacist')>{{ __('role.pharmacist') }}</option>
                                                <option value="procurement" @selected($user->role === 'procurement')>{{ __('role.procurement') }}</option>
                                            </select>

                                            <input type="password" name="password" class="form-control form-control-sm" placeholder="{{ __('user.new_password') }}">
                                            <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="{{ __('user.password_confirm') }}">

                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm">{{ __('user.save') }}</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="this.closest('form').reset();">{{ __('common.cancel') }}</button>
                                            </div>
                                        </form>

                                        @if($user->role !== 'admin')
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('user.delete') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">{{ __('user.no_users') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

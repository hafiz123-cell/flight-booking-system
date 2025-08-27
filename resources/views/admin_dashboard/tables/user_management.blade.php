@extends('admin_dashboard.layouts.vertical', ['subtitle' => 'Users Table'])

@section('content')
@include('admin_dashboard.layouts.partials.page-title', ['title' => 'Tables', 'subtitle' => 'Users'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title">User List</h5>
        <p class="card-subtitle">
            Manage your users here. You can search, paginate, and view details for each user.
        </p>
    </div>

    <div class="card-body">
        <!-- Search Form -->
        <div class="mb-3">
            <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm">
                <input type="text" name="search" id="searchInput"
                       class="form-control"
                       placeholder="Search by name, email or role"
                       value="{{ request('search') }}"
                       autocomplete="off">
            </form>
        </div>

        <!-- Users Table (wrapped for AJAX) -->
        <div id="usersTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created At</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                <td>{{ $user->role ?? 'User' }}</td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($user->status === 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @elseif($user->status === 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.admin.view', $user->id) }}" class="btn btn-sm btn-info">Details</a>
                                    <a href="{{ route('admin.admin.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.admin.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#searchInput').on('keyup', function(){
        let search = $(this).val();
        $.ajax({
            url: "{{ route('admin.users.index') }}",
            type: "GET",
            data: { search: search },
            success: function(data){
                // Replace only the #usersTable part
                $('#usersTable').html($(data).find('#usersTable').html());
            }
        });
    });
});
</script>
@endpush

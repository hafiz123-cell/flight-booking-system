@extends('admin_dashboard.layouts.vertical', ['subtitle' => 'User Details'])

@section('content')
<div class="card">
    <div class="card-header">
        <h5>User Details</h5>
    </div>
    <div class="card-body">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ $user->role ?? 'User' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
        <p><strong>Created At:</strong> {{ $user->created_at }}</p>
    </div>
</div>
<button class="btn" style="font-size: 24px; font-weight: 600; background letter-spacing: 0px; color: #ec7b34; padding-top:10px;">
<a  style="font-size: 24px; font-weight: 600; background letter-spacing: 0px; color: #ec7b34; padding-top:10px;" href="{{route('admin.user')}}">Back</a>
</button>
@endsection

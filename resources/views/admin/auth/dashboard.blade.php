@extends('layouts.admin')

@section('content')
<div class="container">
    @if(session('admin_user'))
        <h2>Welcome to Admin Dashboard, {{ session('admin_user')->name }}</h2>
    @endif

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection

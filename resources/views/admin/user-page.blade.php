@extends('layouts.admin_master')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Dashboard Overview</h1>
        <p>Welcome back! Here's what's happening with ClassSync today.</p>
    </div>
    <div class="page-header-actions">
        <button class="btn btn-primary" id="btnOpenUserModal">
            <i class="fas fa-user-plus"></i>
            <span class="btn-text">New User</span>
        </button>
        <button class="btn btn-primary" id="btnOpenClassModal">
            <i class="fas fa-plus"></i>
            <span class="btn-text">New Class</span>
        </button>
    </div>
</div>



@endsection
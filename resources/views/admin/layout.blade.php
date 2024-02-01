@extends('layout')

@section('content')
<div class="admin-content">
<header id="header">
    <h1>
    <a href="{{ route('index') }}">pastebin</a> admin /
    @yield('admin-title')
    </h1>
</header>

    @yield('admin-content')
</div>
@endsection

@section('footer-extra-scripts')
<script src="{{ mix('assets/js/admin.js') }}"></script>
@endsection

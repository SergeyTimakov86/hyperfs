@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

<div id="login-overlay" hidden>
    <div class="spinner">Login...</div>
</div>

@section('content')
    <p>!!!!!!!!!!!!!!!!!!!!!!!.</p>
    <a href="/">Home</a><br />
    <a href="/api/ept">Auth</a><br />
@endsection
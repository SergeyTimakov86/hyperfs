@extends('layouts.app')
@section('navbar')
    <x-navbar :links="[
        '/admin/account' => 'AccountRepository',
        '/admin/b' => 'bbbbbbb',
        '/admin/c' => 'ccccccc',
    ]" />
@endsection


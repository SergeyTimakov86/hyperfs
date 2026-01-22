@extends('layouts.app')
@section('navbar')
    <x-navbar :links="[
        '/admin/account' => 'Accounts',
        '/admin/b' => 'bbbbbbb',
        '/admin/c' => 'ccccccc',
    ]" />
@endsection


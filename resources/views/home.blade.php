@extends('layouts.app')

@section('content')
    <asideblock :show=true placement="left" header="Users list">
        <sidebar></sidebar>
    </asideblock>
    <div class="container">
        <actions></actions>
        <wall>

        </wall>
    </div>
@endsection

@extends('layouts.admin2')
@section('head')
    <link href="{{URL::asset('https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Fleet</li>
@endsection

@section('content')
    @if(session('aircraft_created'))
        <div class="alert alert-success"><b>Aircraft Successfully Added:</b> {{session('aircraft_created')}}</div>
    @endif

    <h1>Aircraft</h1>
    <aircraft-list list_data="{{$groups}}"></aircraft-list>
@endsection
@section('js')

@endsection
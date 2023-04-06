@extends('layouts.default')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Dashboard</h1>
        </div><!-- End Page Title -->

        <div class="section dashboard">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-md-6 col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Selamat datang </span></h5>
                            <span>Halo {{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

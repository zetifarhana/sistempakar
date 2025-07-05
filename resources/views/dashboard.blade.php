@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')
    <h1 class="mt-4">Diagnosa Jaringan Iconnet</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Mengetahui Penyebab Jaringan yang sedang dialami!</li>
    </ol>

    <!-- Tambahkan gambar -->
    <div class="card mb-4">
        <div class="card-body">
            <img src="{{ asset('image/ICONNET.png') }}" alt="Gambar Diagnosa" class="img-fluid rounded">
        </div>
    </div>
@endsection

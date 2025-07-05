@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />
@section('content')

<div class="container mt-5">
    <h4 class="text-center mb-4">DIAGNOSA</h4>

    <!-- Progress Bar -->
    <div class="mb-4 text-center">
        <div class="d-flex justify-content-center">
            <div class="mx-2">
                <strong>Data Diri</strong>
            </div>
            <div class="mx-2">→</div>
            <div class="mx-2">
                Isi Pertanyaan
            </div>
            <div class="mx-2">→</div>
            <div class="mx-2">
                Hasil Diagnosa
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('diagnosa.storeData') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="id_pelanggan">ID Pelanggan</label>
            <input type="text" name="id_pelanggan" class="form-control" placeholder="Masukkan ID Pelanggan" required>
        </div>

        <div class="form-group mt-3">
            <label for="nama_pelanggan">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" placeholder="Masukkan Nama Pelanggan" required>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Selanjutnya</button>
        </div>
    </form>
</div>
@endsection

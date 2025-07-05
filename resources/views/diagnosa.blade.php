@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')

<form id="diagnosa" class="form" action="{{ route('diagnosa.proses') }}" method="POST">
    @csrf

    {{-- Data Pelanggan --}}
    <div class="step">
        <div class="table-responsive">
            <div class="alert alert-info mt-4">
                <strong>Langkah 1:</strong> Masukkan data pelanggan terlebih dahulu.
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white">Data Pelanggan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Nama Pelanggan</strong></td>
                        <td>
                            <input type="text" 
                                   name="nama_pelanggan" 
                                   class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                                   placeholder="Masukkan nama lengkap" 
                                   value="{{ old('nama_pelanggan') }}" 
                                   required>
                            @error('nama_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><strong>ID Pelanggan</strong></td>
                        <td>
                            <input type="text" 
                                   name="id_pelanggan" 
                                   class="form-control @error('id_pelanggan') is-invalid @enderror" 
                                   placeholder="Masukkan ID pelanggan" 
                                   value="{{ old('id_pelanggan') }}" 
                                   required>
                            @error('id_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Table Checkboxes --}}
    <div class="step">
        <div class="table-responsive">
            <div class="alert alert-primary mt-4">
                <strong>Langkah 2:</strong> Silahkan pilih <strong>Ya</strong> atau <strong>Tidak</strong> untuk setiap gejala di bawah ini.
            </div>
            <table class="table table-bordered table-striped ">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Inisial</th>
                        <th>Nama Gangguan</th>
                        <th class="text-center">Ya</th>
                        <th class="text-center">Tidak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gangguan as $gangguann)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $gangguann->kode_gangguan }}</strong></td>
                            <td>{{ $gangguann->nama_gangguan }}</td>
                            <td class="text-center">
                                <input type="radio" 
                                       name="gangguan[{{ $gangguann->kode_gangguan }}]" 
                                       value="1" 
                                       {{ old("gangguan.{$gangguann->kode_gangguan}") == '1' ? 'checked' : '' }}
                                       required>
                            </td>
                            <td class="text-center">
                                <input type="radio" 
                                       name="gangguan[{{ $gangguann->kode_gangguan }}]" 
                                       value="0"
                                       {{ old("gangguan.{$gangguann->kode_gangguan}") == '0' ? 'checked' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-search"></i> Cek Hasil Diagnosa
            </button>
        </div>
    </div>
</form>

@endsection
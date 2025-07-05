@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')
<div class="container mt-4">
    <h2>Data Solusi</h2>

    <!-- Pesan sukses -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahSolusiModal">
        Tambah
    </button>

    <!-- Tabel Data Solusi -->
    <table id="solusiTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Solusi</th>
                <th>Deskripsi Solusi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solusi as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_solusi }}</td>
                <td>{{ $item->deskripsi_solusi }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editSolusiModal{{ $item->kode_solusi }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>

                    <!-- Tombol Hapus -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusSolusiModal{{ $item->kode_solusi }}">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </td>
            </tr>

            <!-- Modal Edit Solusi -->
            <div class="modal fade" id="editSolusiModal{{ $item->kode_solusi }}" tabindex="-1" aria-labelledby="editSolusiModalLabel{{ $item->kode_solusi }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('solusi.update', $item->kode_solusi) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSolusiModalLabel{{ $item->kode_solusi }}">Edit Solusi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="kode_solusi" class="form-label">Kode Solusi</label>
                                    <input type="text" name="kode_solusi" class="form-control" value="{{ $item->kode_solusi }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi_solusi" class="form-label">Deskripsi Solusi</label>
                                    <textarea name="deskripsi_solusi" class="form-control" required>{{ $item->deskripsi_solusi }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="hapusSolusiModal{{ $item->kode_solusi }}" tabindex="-1" aria-labelledby="hapusSolusiModalLabel{{ $item->kode_solusi }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('solusi.destroy', $item->kode_solusi) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title" id="hapusSolusiModalLabel{{ $item->kode_solusi }}">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body text-center">
                                Apakah kamu yakin ingin menghapus solusi <strong>{{ $item->deskripsi_solusi }}</strong>?
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Solusi -->
<div class="modal fade" id="tambahSolusiModal" tabindex="-1" aria-labelledby="tambahSolusiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('solusi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSolusiModalLabel">Tambah Solusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_solusi" class="form-label">Kode Solusi</label>
                        <input type="text" name="kode_solusi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_solusi" class="form-label">Deskripsi Solusi</label>
                        <textarea name="deskripsi_solusi" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

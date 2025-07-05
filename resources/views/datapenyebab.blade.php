@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')
<div class="container mt-4">
    <h2>Data Penyebab</h2>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPenyebabModal">
        Tambah
    </button>

    <!-- Tabel Data Penyebab -->
    <table id="penyebabTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Penyebab</th>
                <th>Nama Penyebab</th>
                <th>Kode Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penyebab as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_penyebab }}</td>
                <td>{{ $item->nama_penyebab }}</td>
                <td>{{ $item->kode_kategori }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->kode_penyebab }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>

                    <!-- Tombol Hapus -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusPenyebabModal{{ $item->kode_penyebab }}">
                        <i class="bi bi-trash"></i> Hapus
                    </button>

                    <!-- Modal Konfirmasi Hapus -->
                    <div class="modal fade" id="hapusPenyebabModal{{ $item->kode_penyebab }}" tabindex="-1" aria-labelledby="hapusPenyebabModalLabel{{ $item->kode_penyebab }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('penyebab.destroy', $item->kode_penyebab) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        Apakah kamu yakin ingin menghapus <strong>{{ $item->nama_penyebab }}</strong>?
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Edit Penyebab -->
                    <div class="modal fade" id="editModal{{ $item->kode_penyebab }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->kode_penyebab }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('penyebab.update', $item->kode_penyebab) }}" method="POST" class="modal-content">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Penyebab</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Penyebab</label>
                                        <input type="text" name="kode_penyebab" class="form-control" value="{{ $item->kode_penyebab }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Penyebab</label>
                                        <input type="text" name="nama_penyebab" class="form-control" value="{{ $item->nama_penyebab }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Kategori</label>
                                        <input type="text" name="kode_kategori" class="form-control" value="{{ $item->kode_kategori }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Penyebab -->
<div class="modal fade" id="tambahPenyebabModal" tabindex="-1" aria-labelledby="tambahPenyebabModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('penyebab.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penyebab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Penyebab</label>
                        <input type="text" name="kode_penyebab" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penyebab</label>
                        <input type="text" name="nama_penyebab" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Kategori</label>
                        <input type="text" name="kode_kategori" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

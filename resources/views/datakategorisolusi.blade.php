@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')
<div class="container mt-4">
    <h2>Data Kategori Solusi</h2>
    
    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahKategoriSolusiModal">
        Tambah
    </button>

    <!-- Tabel Data Kategori Solusi -->
    <table id="kategoriSolusiTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Kategori</th>
                <th>Daftar Solusi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategoriSolusi as $index => $kategori)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $kategori->kode_kategori }}</td>
                <td>{{ $kategori->solusis }}</td>
                <td>
                    <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editKategoriSolusiModal{{ $kategori->kode_kategori }}">
                        <i class="bi bi-pencil-square"></i> Edit

                        <!-- Tombol Hapus (buka modal) -->
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $kategori->kode_kategori }}">
                <i class="bi bi-trash"></i> Hapus
            </button>

            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="hapusModal{{ $kategori->kode_kategori }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $kategori->kode_kategori }}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel{{ $kategori->kode_kategori }}">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    Apakah kamu yakin ingin menghapus solusi <strong>{{ $kategori->solusis }}</strong>?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('kategorisolusi.destroy', $kategori->kode_kategori) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>


            <!-- Modal Edit -->
            <div class="modal fade" id="editKategoriSolusiModal{{ $kategori->kode_kategori }}" tabindex="-1" aria-labelledby="editKategoriSolusiModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kategorisolusi.update', $kategori->kode_kategori) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h5 class="modal-title" id="editKategoriSolusiModalLabel">Edit Kategori Solusi</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label">Kode Kategori</label>
                            <input type="text" name="kode_kategori" class="form-control" value="{{ $kategori->kode_kategori }}" readonly>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Daftar Solusi</label>
                            <input type="text" name="solusis" class="form-control" value="{{ $kategori->solusis }}" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
              </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahKategoriSolusiModal" tabindex="-1" aria-labelledby="tambahKategoriSolusiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('kategorisolusi.store') }}" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="tambahKategoriSolusiModalLabel">Tambah Kategori Solusi</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Kode Kategori</label>
                <input type="text" name="kode_kategori" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Daftar Solusi</label>
                <input type="text" name="solusis" class="form-control" required>
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

@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')
<div class="container mt-4">
    <h2>Data Gangguan</h2>
    
    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahGangguanModal">
        Tambah
    </button>

    <!-- Tabel Data Gangguan -->
    <table id="gangguanTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Ganggguan</th>
                <th>Nama Gangguan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gangguan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_gangguan }}</td>
                <td>{{ $item->nama_gangguan }}</td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->kode_gangguan }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>

                                <!-- Tombol Hapus -->
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusGangguanModal{{ $item->kode_gangguan }}">
                <i class="bi bi-trash"></i> Hapus
            </button>

            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="hapusGangguanModal{{ $item->kode_gangguan }}" tabindex="-1" aria-labelledby="hapusGangguanModalLabel{{ $item->kode_gangguan }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('gangguan.destroy', $item->kode_gangguan) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title" id="hapusGangguanModalLabel{{ $item->kode_gangguan }}">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body text-center">
                                Apakah kamu yakin ingin menghapus gangguan <strong>{{ $item->nama_gangguan }}</strong>?
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Modal Edit Gangguan -->
            <div class="modal fade" id="editModal{{ $item->kode_gangguan }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->kode_gangguan }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('gangguan.update', $item->kode_gangguan) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Gangguan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="kode_gangguan" class="form-label">Kode Gangguan</label>
                                <input type="text" name="kode_gangguan" class="form-control" value="{{ $item->kode_gangguan }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_gangguan" class="form-label">Nama Gangguan</label>
                                <input type="text" name="nama_gangguan" class="form-control" value="{{ $item->nama_gangguan }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Gangguan -->
<div class="modal fade" id="tambahGangguanModal" tabindex="-1" aria-labelledby="tambahGangguanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('gangguan.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="tambahGangguanModalLabel">Tambah Gangguan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="kode_gangguan" class="form-label">Kode Gangguan</label>
            <input type="text" name="kode_gangguan" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="nama_gangguan" class="form-label">Nama Gangguan</label>
            <input type="text" name="nama_gangguan" class="form-control" required>
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

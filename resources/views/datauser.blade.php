@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')
<div class="container mt-4">
    <h2>Data User</h2>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
        Tambah
    </button>

    <!-- Tabel Data User -->
    <table id="userTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Level</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $index => $user)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->level }}</td>
            <td><i class="fa fa-lock"></i></td>
            <td>
                <!-- Tombol Edit -->
                <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>

                <!-- Tombol Hapus -->
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusUserModal{{ $user->id }}">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </td>
          </tr>

          <!-- Modal Konfirmasi Hapus -->
          <div class="modal fade" id="hapusUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="hapusUserModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <div class="modal-header">
                    <h5 class="modal-title" id="hapusUserModalLabel{{ $user->id }}">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    Apakah kamu yakin ingin menghapus user <strong>{{ $user->username }}</strong>?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Modal Edit -->
          <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('user.update', ['id' => $user->id]) }}" method="POST">
                  @csrf
                  <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                      <!-- Tidak gunakan name="username" agar tidak terkirim ke controller -->
                    </div>
                    <div class="mb-3">
                      <label for="password" class="form-label">Password (biarkan kosong jika tidak diubah)</label>
                      <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label for="level" class="form-label">Level</label>
                      <select name="level" class="form-select" required>
                        <option value="superadmin" {{ $user->level == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                        <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                      </select>
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

        @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="tambahUserModalLabel">Tambah User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <select name="level" class="form-select" required>
              <option value="">-- Pilih Level --</option>
              <option value="superadmin">Superadmin</option>
              <option value="admin">Admin</option>
            </select>
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

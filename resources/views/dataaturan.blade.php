@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

@section('content')
<div class="container mt-4">
    <h2>Data Aturan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahAturanModal">Tambah</button>

    <table id="aturanTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Aturan</th>
                <th>Kode Gangguan</th>
                <th>Kode Penyebab</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aturan as $index => $item)
            @php
                // Gunakan helper method yang sudah dibuat di model
                $decodedGangguan = $item->kode_gangguan_array;
                
                // Debug: pastikan $decodedGangguan adalah array
                if (!is_array($decodedGangguan)) {
                    $decodedGangguan = [];
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_aturan }}</td>
                <td>{{ implode(', ', $decodedGangguan) }}</td>
                <td>{{ $item->kode_penyebab }}</td>
                <td>
                    <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editAturanModal{{ $item->kode_aturan }}">Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusAturanModal{{ $item->kode_aturan }}">Hapus</button>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editAturanModal{{ $item->kode_aturan }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('aturan.update', $item->kode_aturan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Aturan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Kode Aturan</label>
                                    <input type="text" name="kode_aturan" class="form-control" value="{{ $item->kode_aturan }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label>Kode Gangguan</label><br>
                                    @php
                                        $listGangguan = ['G1','G2','G3','G4','G5','G6','G7','G8'];
                                    @endphp
                                    @foreach($listGangguan as $kode)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="kode_gangguan[]" value="{{ $kode }}"
                                                {{ in_array($kode, $decodedGangguan) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $kode }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mb-3">
                                    <label>Kode Penyebab</label>
                                    <input type="text" name="kode_penyebab" class="form-control" value="{{ $item->kode_penyebab }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Hapus -->
            <div class="modal fade" id="hapusAturanModal{{ $item->kode_aturan }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('aturan.destroy', $item->kode_aturan) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin hapus <strong>{{ $item->kode_aturan }}</strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
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
<div class="modal fade" id="tambahAturanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('aturan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Aturan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Aturan</label>
                        <input type="text" name="kode_aturan" class="form-control" required>
                    </div>
                    @php
                        $listGangguan = ['G1','G2','G3','G4','G5','G6','G7','G8','G9','G10'];
                    @endphp
                    <div class="mb-3">
                        <label>Kode Gangguan</label><br>
                        @foreach($listGangguan as $kode)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="kode_gangguan[]" value="{{ $kode }}">
                                <label class="form-check-label">{{ $kode }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <label>Kode Penyebab</label>
                        <input type="text" name="kode_penyebab" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
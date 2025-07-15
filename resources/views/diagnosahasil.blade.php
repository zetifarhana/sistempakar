@extends('layout.index')
<link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

@section('content')
<form id="diagnosa" class="form">
    <div class="step">
        {{-- Data Pelanggan --}}
        @if(isset($dataPelanggan) && $dataPelanggan)
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white">Data Pelanggan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Nama Pelanggan</strong></td>
                        <td>{{ $dataPelanggan['nama_pelanggan'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>ID Pelanggan / No. HP</strong></td>
                        <td>{{ $dataPelanggan['id_pelanggan'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Diagnosa</strong></td>
                        <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        {{-- Hasil Diagnosa --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-4">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center bg-primary text-white">Hasil Diagnosa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Gangguan yang Dipilih</strong></td>
                        <td>
                            @if($gangguan && $gangguan->count())
                                <ul class="mb-0">
                                    @foreach ($gangguan as $g)
                                        <li><strong>{{ $g->kode_gangguan }}</strong> - {{ $g->nama_gangguan }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Tidak ada gangguan yang dipilih</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Potensi Penyebab</strong></td>
                        <td>
                            @if (!empty($hasil))
                                @if ($kerusakanKuat)
                                    Kami menemukan <strong>1 penyebab kuat</strong> dan <strong>{{ count($hasil) - 1 }} potensi penyebab lainnya</strong>.
                                @else
                                    <span class="badge bg-warning">Potensi Kerusakan</span><br>
                                    Tidak ditemukan penyebab kuat, tapi ada <strong>{{ count($hasil) }} potensi penyebab</strong>.
                                @endif
                            @else
                                <span class="badge bg-danger">Tidak Ditemukan</span><br>
                                Tidak ditemukan potensi penyebab.
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Detail Hasil --}}
            @foreach ($hasil as $index => $aturan)
            <div class="card mb-3 {{ $loop->first ? 'border-success' : 'border-secondary' }}">
                <div class="card-header {{ $loop->first ? 'bg-success text-white' : 'bg-light' }}">
                    <h5 class="mb-0">
                        {{ $loop->first ? '✅ Solusi Terbaik - Hasil ' : '📌 Hasil ' }}{{ $loop->iteration }}
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-3">
                        <tbody>
                            <tr>
                                <td width="25%"><strong>Kode Kerusakan</strong></td>
                                <td>
                                    @if($aturan['penyebab'])
                                        <strong>{{ $aturan['penyebab']->kode_penyebab }} - {{ $aturan['penyebab']->nama_penyebab }}</strong>
                                    @else
                                        <em>Data penyebab tidak ditemukan</em>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Gangguan Terkait</strong></td>
                                <td>
                                    @if($aturan['gangguan'] && $aturan['gangguan']->count())
                                        <ol class="mb-0">
                                            @foreach ($aturan['gangguan'] as $g)
                                                <li><strong>{{ $g->kode_gangguan }}</strong> - {{ $g->nama_gangguan }}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        <p class="text-muted mb-0">Tidak ada data gangguan terkait</p>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Solusi Perbaikan</strong></td>
                                <td>
                                    @if (!empty($aturan['solusis']))
                                        <ol class="mb-0">
                                            @foreach ($aturan['solusis'] as $solusi)
                                                <li>{{ $solusi['deskripsi_solusi'] }}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Belum ada solusi spesifik. Konsultasikan ke teknisi.
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Persentase Kecocokan</strong></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar {{ $aturan['persentase'] == 100 ? 'bg-success' : 'bg-info' }}"
                                             role="progressbar"
                                             style="width: {{ $aturan['persentase'] }}%;">
                                            {{ $aturan['persentase'] }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Action --}}
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ route('diagnosa.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-redo"></i> Diagnosa Lagi
            </a>
            @if(!empty($hasil))
                <button type="button" class="btn btn-success btn-lg" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak Hasil
                </button>
            @endif
        </div>

        {{-- Print Styles --}}
        <style>
            @media print {
                .btn, .navbar, .footer {
                    display: none !important;
                }
                .card {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }
                .table {
                    font-size: 12px;
                }
                body {
                    font-size: 12px;
                }
                .badge {
                    border: 1px solid #000;
                    color: #000 !important;
                    background-color: transparent !important;
                }
            }
        </style>
    </div>
</form>

@if (session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif
@endsection

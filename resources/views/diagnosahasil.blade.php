@extends('layout.index')
<link href="css/styles.css" rel="stylesheet" />

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
                        <td>{{ date('d/m/Y H:i:s') }}</td>
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
                            @if($gangguan && $gangguan->count() > 0)
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
                                    Kami tidak menemukan penyebab kuat, tapi ada <strong>{{ count($hasil) }} potensi penyebab</strong>.
                                @endif
                            @else
                                <span class="badge bg-danger">Tidak Ditemukan</span><br>
                                Tidak ditemukan potensi penyebab. Silakan coba diagnosa ulang dengan memilih gangguan lain.
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Detail Hasil untuk setiap penyebab --}}
            @if(!empty($hasil))
                @foreach ($hasil as $index => $aturan)
                    <div class="card mb-3 {{ $loop->first && $kerusakanKuat ? 'border-success' : '' }}">
                        <div class="card-header {{ $loop->first && $kerusakanKuat ? 'bg-primary text-white' : 'bg-light' }}">
                            <h5 class="mb-0">
                                {{ $loop->first && $kerusakanKuat ? '🎯 ' : '🎯 ' }}
                                Hasil {{ $loop->iteration }}
                                @if ($loop->first && $kerusakanKuat)
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td width="25%"><strong>Kode Kerusakan</strong><br></td>
                                        <td>
                                            @if($aturan['penyebab'])
                                                <strong>{{ $aturan['penyebab']->kode_penyebab }} - {{ $aturan['penyebab']->nama_penyebab }}</strong><br>
                                            @else
                                                <strong>{{ $aturan['kode_penyebab'] }} - Data Penyebab Tidak Tersedia</strong><br>
                                            @endif
                                            <small>
                                               
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gangguan Terkait</strong></td>
                                        <td>
                                            @if($aturan['gangguan'] && $aturan['gangguan']->count() > 0)
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
                                            @if(!empty($aturan['solusis']) && count($aturan['solusis']) > 0)
                                                <ol class="mb-0">
                                                    @foreach ($aturan['solusis'] as $solusi)
                                                        <li>
                                                            @if(isset($solusi['kode_solusi']))
                                                            @endif
                                                            {{ $solusi['deskripsi_solusi'] }}
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @else
                                                <div class="alert alert-warning mb-0">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Belum ada solusi spesifik yang tersedia untuk penyebab ini. 
                                                    Silakan konsultasi dengan teknisi ahli.
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Action Buttons --}}
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
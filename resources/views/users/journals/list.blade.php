@push('scripts')
@endpush

<x-app-layout :options="['loading']">
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="header-title col-sm-12 col-md-7">
                                <h4 class="card-title">Daftar Jurnal</h4>
                                @if ($data->max_journals >= 0)
                                    <h6 class="text-muted"><small>{{ $data->journals->count()}} / {{$data->max_journals}}
                                        Jurnal, Tersedia {{ $data->max_journals - $data->journals->count() }} Jurnal yang bisa ditambahkan</small></h6>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-5 mt-3 mt-md-0">
                                <div class="row g-2">
                                    <div class="col-sm-12 col-md-6">
                                        <button type="button" class="btn btn-dark w-100">Lihat Laporan Metrik</button>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <button type="button" class="btn btn-primary w-100"
                                            @if ($data->max_journals > 0 and $data->journals->count() >= $data->max_journals) disabled @endif data-bs-toggle="modal"
                                            data-bs-target="#tambahJurnalModal">+ Tambah Jurnal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Total Saldo:</strong></td>
                                        <td><strong>$</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Catatan:</strong></td>
                                        <td><strong>Catatan</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">

                                    <tr>
                                        <td><strong>Catatan Tersisa:</strong></td>
                                        <td><strong>Catatan</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mb-5">
                            <table id="journals-list-table" class="table table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>Status</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Saldo</th>
                                        <th>Jumlah Catatan</th>
                                        <th>WR (%)</th>
                                        <th>Keuntungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->journals as $key => $journal)
                                        <tr onclick="window.location='{{ route('user.journal.detail', $journal->id) }}'"
                                            style="cursor: pointer;">
                                            <td>
                                                @if ($journal->deleted_at == '')
                                                    <span class="badge rounded-pill bg-primary">Aktif</span>
                                                @elseif($journal->deleted_at != '')
                                                    <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>{{ $journal->name }}</td>
                                            <td>{{ $journal->description == '' ? '-' : $journal->description }}</td>
                                            <td>${{ (float) $journal->balances }}</td>
                                            <td></td>
                                            <td>{{ (float) $journal->winrate }}%</td>
                                            <td class="@if($journal->pnl > 0) text-success @elseif($journal->pnl < 0) text-danger @endif">@if($journal->pnl < 0)-@endif${{ abs((float) $journal->pnl) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Persentase Keberhasilan:</strong></td>
                                        <td><strong>%</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Total Keuntungan:</strong></td>
                                        <td><strong>$</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambahJurnalModal" tabindex="-1" aria-labelledby="tambahJurnalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahJurnalTitle">Tambah Jurnal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{ route('user.journal.add') }}" method="post">
                            @csrf
                            <div class="form-group form-group-alt mb-2">
                                <label for="name" class="form-label text-dark">Nama Jurnal</label>
                                <input type="text" class="form-control" name="name" placeholder="Binance (Jem)">
                            </div>
                            <div class="form-group form-group-alt mb-2">
                                <label for="balances" class="form-label text-dark">Saldo</label>
                                <input type="text" class="form-control" name="balances" placeholder="0">
                            </div>
                            <div class="form-group form-group-alt row gx-2 gy-0">
                                <div class="col-sm-12 col-md-6">
                                    <label for="risk" class="form-label text-dark">Resiko Per Transaksi</label>
                                    <input type="text" class="form-control" name="risk"
                                    placeholder="0">
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <label for="target" class="form-label text-dark">Target Per Bulan</label>
                                    <input type="text" class="form-control" name="target"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="form-group form-group-alt">
                                <label for="description" class="form-label text-dark">Catatan</label>
                                <textarea name="description" class="form-control" style="height: 15vh; resize:none"></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-primary">Kumpul</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

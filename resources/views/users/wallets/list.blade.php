@push('scripts')
@endpush

<x-app-layout :options="['loading', 'datatable']">
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="header-title col-sm-12 col-md-7">
                                <h4 class="card-title">Daftar Dompet</h4>
                                @if ($data->max_wallets >= 0)
                                    <h6 class="text-muted"><small>{{ $data->wallets->count() }} /
                                            {{ $data->max_wallets }}
                                            Dompet, Tersedia {{ $data->max_wallets - $data->wallets->count() }} Dompet
                                            yang bisa ditambahkan</small></h6>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-5 justify-content-md-end mt-3 mt-md-0 row g-2">
                                <div class="col-sm-12 col-md-6">
                                    <button type="button" class="btn btn-dark w-100">Lihat Laporan</button>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <button type="button" class="btn btn-primary w-100"
                                        @if ($data->max_wallets > 0 and $data->wallets->count() >= $data->max_wallets) disabled @endif data-bs-toggle="modal"
                                        data-bs-target="#tambahDompetModal">+ Tambah Dompet</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-6">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Aset:</strong></td>
                                        <td><strong>${{ number_format((float) $data->wallets->sum('amount_of_assets'), 2, '.', ',') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        {{-- <td><small>Rp</small></td> --}}
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Total Keuntungan:</strong></td>
                                        <td
                                            class="@if ($data->wallets->sum('pnl') > 0) text-success @elseif($data->wallets->sum('pnl') < 0) text-danger @endif">
                                            <strong>
                                                @if ($data->wallets->sum('pnl') < 0)
                                                    -
                                                @endif
                                                ${{ number_format(abs((float) $data->wallets->sum('pnl')), 2, '.', ',') }}
                                            </strong></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        {{-- <td><small>Rp</small></td> --}}
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="wallets-list-table" class="table table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Saldo</th>
                                        <th>Aset</th>
                                        <th>Keuntungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->wallets as $key => $wallet)
                                        <tr onclick="window.location='{{ route('user.wallet.detail', $wallet->id) }}'"
                                            style="cursor: pointer;">
                                            <td>{{ $wallet->name }}
                                                @if ($wallet->binance_api_key != '')
                                                    <span class="badge rounded-pill"
                                                        style="background-color: #F3BA2F">Binance @if ($wallet->demo == 1)
                                                            (Demo)
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $wallet->description == '' ? '-' : $wallet->description }}</td>
                                            <td>
                                                @if ($wallet->deleted_at == '')
                                                    <span class="badge rounded-pill bg-primary">Aktif</span>
                                                @elseif($wallet->deleted_at != '')
                                                    <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format((float) $wallet->balance, 2, '.', ',') }}</td>
                                            <td>${{ number_format((float) $wallet->amount_of_assets, 2, '.', ',') }}
                                            </td>
                                            <td
                                                class="@if ($wallet->pnl > 0) text-success @elseif($wallet->pnl < 0) text-danger @endif">
                                                @if ($wallet->pnl < 0)
                                                    -
                                                @endif
                                                ${{ number_format(abs((float) $wallet->pnl), 2, '.', ',') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambahDompetModal" tabindex="-1" aria-labelledby="tambahDompetLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDompetTitle">Tambah Dompet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{ route('user.wallet.add') }}" method="post">
                            @csrf
                            <div class="form-group form-group-alt mb-2">
                                <label for="name" class="form-label text-dark">Nama Dompet</label>
                                <input type="text" class="form-control" name="name" placeholder="Binance (Jem)">
                            </div>
                            <div class="form-group form-group-alt mb-2">
                                <label for="balance" class="form-label text-dark">Saldo Dompet (Diisi apabila tidak ada
                                    Integrasi Binance)</label>
                                <input type="text" class="form-control" name="balance" placeholder="0">
                            </div>
                            <div class="form-group form-group-alt row gx-2 gy-0">
                                <label for="binance_api_key" class="form-label text-dark">Integrasi Binance
                                    (Opsional)</label>
                                <div class="col-sm-12 col-md-6">
                                    <input type="text" class="form-control" name="binance_api_key"
                                        placeholder="API Key">
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <input type="text" class="form-control" name="binance_secret_key"
                                        placeholder="Secret Key">
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

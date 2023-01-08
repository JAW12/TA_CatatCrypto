@push('scripts')
    <script></script>
@endpush

<x-app-layout :assets="$assets ?? []">
    <x-back-button>{{route('user.wallet.detail', $wallet->id)}}</x-back-button>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-md-flex justify-content-between">
                        <div class="header-title d-inline-flex align-items-center">
                            <img src="{{ $asset->thumb }}" alt="logo_crypto" class="img-thumbnail">
                            <h4 class="card-title ms-3 mt-2">
                                {{ $asset->name }}
                                <span class="h6">{{$asset_wallet->pnl}}$ (-50%)</span>
                                <span class="h6"><small>-Rp.500.000</small></span>
                                <a href="{{route('user.wallet.asset.detail.info', ['wallet' => $wallet->id, 'asset' => $asset->id])}}"" class="btn btn-light btn-sm">Lihat Info Koin</a>
                            </h4>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 mt-md-0">
                            <div>
                                <button type="button" class="btn btn-dark">Lihat Laporan</button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTransaksiModal">+ Tambah Transaksi</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Koin:</strong></td>
                                        <td><strong>{{ $asset_wallet->amount }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><small>$</small></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Harga Rata-Rata:</strong></td>
                                        <td><strong>${{ $asset_wallet->average_price }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Harga Sekarang:</strong></td>
                                        <td><strong>${{ $asset->current_price }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="assets-list-table" class="table table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Tipe</th>
                                        <th>Harga</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Aset</th>
                                        <th>Biaya Tambahan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambahTransaksiModal" tabindex="-1" aria-labelledby="tambahTransaksiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahTransaksiTitle">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bd-example">
                        <ul class="nav nav-pills nav-justified" data-toggle="slider-tab" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="beli-tab" data-bs-toggle="tab" data-bs-target="#pills-beli" type="button" role="tab" aria-controls="beli" aria-selected="true">Beli</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="jual-tab" data-bs-toggle="tab" data-bs-target="#pills-jual" type="button" role="tab" aria-controls="jual" aria-selected="false">Jual</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="transfer-tab" data-bs-toggle="tab" data-bs-target="#pills-transfer" type="button" role="tab" aria-controls="transfer" aria-selected="false">Transfer</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-beli" role="tabpanel"
                                aria-labelledby="pills-beli-tab1">
                                <form action="" method="post">
                                    @csrf
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Harga Koin</label>
                                        <input type="text" class="form-control" name="price" placeholder="0.000">
                                    </div>
                                    <div class="form-group form-group-alt row gx-2 gy-0">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                            <div class="form-group form-group-alt input-group mb-3">
                                                <input type="text" name="amount" class="form-control" placeholder="0" aria-label="Jumlah Koin" aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">SHIB</span>
                                            </div>

                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="fee" class="form-label text-dark">Biaya Tambahan (Diisi apabila manual)</label>
                                            <input type="text" class="form-control" name="fee"
                                                placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-alt">
                                        <label for="description" class="form-label text-dark">Catatan</label>
                                        <textarea name="description" class="form-control" style="height: 15vh; resize:none"></textarea>
                                    </div>
                                    <div class="form-group form-group-alt">
                                        <label for="total" class="form-label text-dark">Jumlah Transaksi</label>

                                        <div class="form-group input-group form-group-alt">
                                            <span class="input-group-text text-dark" style="background-color:#e9ecef; font-size: 2em" id="basic-addon1">$</span>
                                            <input type="text" class="form-control" name="total" value="0" aria-label="total" aria-describedby="basic-addon1" style="font-size: 2em" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                        <button type="submit" class="btn btn-primary">Kumpul</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-jual" role="tabpanel"
                                aria-labelledby="pills-jual-tab1">
                                <form action="" method="post">
                                    @csrf
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Harga Koin</label>
                                        <input type="text" class="form-control" name="price" placeholder="0.000">
                                    </div>
                                    <div class="form-group form-group-alt row gx-2 gy-0">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                            <div class="form-group form-group-alt input-group mb-3">
                                                <input type="text" name="amount" class="form-control" placeholder="0" aria-label="Jumlah Koin" aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">SHIB</span>
                                            </div>

                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="fee" class="form-label text-dark">Biaya Tambahan (Diisi apabila manual)</label>
                                            <input type="text" class="form-control" name="fee"
                                                placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-alt">
                                        <label for="description" class="form-label text-dark">Catatan</label>
                                        <textarea name="description" class="form-control" style="height: 15vh; resize:none"></textarea>
                                    </div>
                                    <div class="form-group form-group-alt">
                                        <label for="total" class="form-label text-dark">Jumlah Transaksi</label>

                                        <div class="form-group input-group form-group-alt">
                                            <span class="input-group-text text-dark" style="background-color:#e9ecef; font-size: 2em" id="basic-addon1">$</span>
                                            <input type="text" class="form-control" name="total" value="0" aria-label="total" aria-describedby="basic-addon1" style="font-size: 2em" disabled>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                        <button type="submit" class="btn btn-primary">Kumpul</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-transfer" role="tabpanel"
                                aria-labelledby="pills-transfer-tab1">
                                <form action="" method="post">
                                    @csrf
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Jenis Transfer</label>
                                        <select class="form-control form-select" name="transfer_type">
                                            <option value="3">Transfer Keluar</option>
                                            <option value="4">Transfer Masuk</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Harga Koin</label>
                                        <input type="text" class="form-control" name="price" placeholder="0.000">
                                    </div>
                                    <div class="form-group form-group-alt row gx-2 gy-0">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                            <div class="form-group form-group-alt input-group mb-3">
                                                <input type="text" name="amount" class="form-control" placeholder="0" aria-label="Jumlah Koin" aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">SHIB</span>
                                            </div>

                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="fee" class="form-label text-dark">Biaya Tambahan (Diisi apabila manual)</label>
                                            <input type="text" class="form-control" name="fee"
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
        </div>
    </div>
</x-app-layout>

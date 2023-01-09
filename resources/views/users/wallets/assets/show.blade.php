@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.min.js"
        integrity="sha512-6j+LxzZ7EO1Kr7H5yfJ8VYCVZufCBMNFhSMMzb2JRhlwQ/Ri7Zv8VfJ7YI//cg9H5uXT2lQpb14YMvqUAdGlcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var autoNumericPrice = null;
        var autoNumericAmount = null;
        var autoNumericFee = null;
        var autoNumericTotal = null;

        function loadTransaction(price, amount, fee) {
            price = price.replace(',', '');
            amount = amount.replace(',', '');
            fee = fee.replace(',', '');
            let gross = parseFloat(price) * parseFloat(amount);
            let nett = gross + parseFloat(fee);
            // console.log(gross);
            // console.log(nett);

            return nett;
        }

        $(function() {
            autoNumericPrice = new AutoNumeric('input[name=price]', {
                allowDecimalPadding: false,
                decimalPlaces: 16
            });
            autoNumericAmount = new AutoNumeric('input[name=amount]', {
                allowDecimalPadding: false,
                decimalPlaces: 16
            });
            autoNumericFee = new AutoNumeric('input[name=fee]', {
                allowDecimalPadding: false,
                decimalPlaces: 16
            });

            $("input[name=price]").on('change', function() {
                let price = $(this).val();
                $("input[name=price]").val(price);
                let amount = $("input[name=amount]").val();
                let fee = $("input[name=fee]").val();
                $("input[name=total]").val(loadTransaction(price, amount, fee));
                autoNumericTotal = new AutoNumeric('input[name=total]', {
                    allowDecimalPadding: false,
                    decimalPlaces: 16
                });
            });

            $("input[name=amount]").on('change', function() {
                let price = $("input[name=price]").val();
                let amount = $(this).val();
                $("input[name=amount]").val(amount);
                let fee = $("input[name=fee]").val();
                $("input[name=total]").val(loadTransaction(price, amount, fee));
                autoNumericTotal = new AutoNumeric('input[name=total]', {
                    allowDecimalPadding: false,
                    decimalPlaces: 16
                });
            });

            $("input[name=fee]").on('change', function() {
                let price = $("input[name=price]").val();
                let amount = $("input[name=amount]").val();
                let fee = $(this).val();
                $("input[name=fee]").val(fee);
                $("input[name=total]").val(loadTransaction(price, amount, fee));
                autoNumericTotal = new AutoNumeric('input[name=total]', {
                    allowDecimalPadding: false,
                    decimalPlaces: 16
                });
            });

            $("input[name=time]").on('change', function() {
                let time = $(this).val();
                $("input[name=time]").val(time);
            });

            $("button[type=reset]").on('click', function() {
                $("input[name=price]").val(0);
                $("input[name=amount]").val(0);
                $("input[name=fee]").val(0);
                $("input[name=total]").val(0);
                $("input[name=time]").val();
            });

            $('.btn-delete').click(function(e){
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan menghapus transaksi ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, hapus!',
                    cancelButtonText: 'Tidak',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $(e.target).closest('form').submit() // Post the surrounding form
                    }
                })
            });
        });
    </script>
@endpush

<x-app-layout :assets="$assets ?? []">
    <x-back-button>{{ route('user.wallet.detail', $wallet->id) }}</x-back-button>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-md-flex justify-content-between">
                        <div class="header-title d-inline-flex align-items-center">
                            <img src="{{ $asset->thumb }}" alt="logo_crypto" class="img-thumbnail">
                            <h4 class="card-title ms-3 mt-2">
                                {{ $asset->name }}
                                <span class="h6">{{ $asset_wallet->pnl }}$ (-50%)</span>
                                <span class="h6"><small>-Rp.500.000</small></span>
                                <a href="{{ route('user.wallet.asset.detail.info', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}""
                                    class="btn btn-light btn-sm">Lihat Info Koin</a>
                            </h4>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 mt-md-0">
                            <div>
                                <button type="button" class="btn btn-dark">Lihat Laporan</button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambahTransaksiModal">+ Tambah Transaksi</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Koin:</strong></td>
                                        <td><strong>{{ (float)$asset_wallet->amount }}</strong>
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
                                        <td><strong>${{ (float)$asset_wallet->average_price }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Harga Sekarang:</strong></td>
                                        <td><strong>${{ (float)$asset->current_price }}</strong>
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
                                        <th>Waktu</th>
                                        <th>Jumlah Aset</th>
                                        <th>Biaya Tambahan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asset_wallet->transactions as $key => $transaction)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($transaction->type == 0)
                                                    Beli
                                                @elseif($transaction->type == 1)
                                                    Jual
                                                @elseif($transaction->type == 2)
                                                    Transfer Keluar
                                                @elseif($transaction->type == 3)
                                                    Transfer Masuk
                                                @endif
                                            </td>
                                            <td>
                                                ${{ (float)$transaction->price }}
                                            </td>
                                            <td>
                                                {{ $transaction->time }}
                                            </td>
                                            <td>
                                                @if ($transaction->type == 0)
                                                    +{{ (float)$transaction->amount }}
                                                @elseif($transaction->type == 1)
                                                    -{{ (float)$transaction->amount }}
                                                @elseif($transaction->type == 2)
                                                    -{{ (float)$transaction->amount }}
                                                @elseif($transaction->type == 3)
                                                    +{{ (float)$transaction->amount }}
                                                @endif
                                            </td>
                                            <td>
                                                ${{ (float)$transaction->fee }}
                                            </td>
                                            <td>
                                                @if ($transaction->status == 1)
                                                    <span class="badge rounded-pill bg-primary">Terpenuhi</span>
                                                @elseif($wallet->status == 0)
                                                    <span class="badge rounded-pill bg-secondary">Belum Terpenuhi</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="flex align-items-center list-asset-transaction-action">
                                                    <a class="btn btn-sm btn-icon btn-success" data-toggle="tooltip"
                                                        data-placement="top" title="" data-original-title="Edit"
                                                        href="#">
                                                        <span class="btn-inner">
                                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                                <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    <form action="{{route('user.wallet.asset.detail.delete', ['wallet' => $wallet->id, 'asset' => $asset->id, 'asset_transaction' => $transaction->id])}}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-icon btn-danger btn-delete" data-toggle="tooltip"
                                                        data-placement="top" title="" data-original-title="Delete">
                                                        <span class="btn-inner">
                                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                stroke="currentColor">
                                                                <path
                                                                    d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                                <path d="M20.708 6.23975H3.75" stroke="currentColor"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                                <path
                                                                    d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    </form>

                                                </div>
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
    <div class="modal fade" id="tambahTransaksiModal" tabindex="-1" aria-labelledby="tambahTransaksiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahTransaksiTitle">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bd-example">
                        <ul class="nav nav-pills nav-justified" data-toggle="slider-tab" id="myTab"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="beli-tab" data-bs-toggle="tab"
                                    data-bs-target="#pills-beli" type="button" role="tab" aria-controls="beli"
                                    aria-selected="true">Beli</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="jual-tab" data-bs-toggle="tab"
                                    data-bs-target="#pills-jual" type="button" role="tab" aria-controls="jual"
                                    aria-selected="false">Jual</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="transfer-tab" data-bs-toggle="tab"
                                    data-bs-target="#pills-transfer" type="button" role="tab"
                                    aria-controls="transfer" aria-selected="false">Transfer</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-beli" role="tabpanel"
                                aria-labelledby="pills-beli-tab1">
                                <form
                                    action="{{ route('user.wallet.asset.detail.add', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}"
                                    method="post">
                                    @csrf
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Harga Koin</label>
                                        <input type="text" class="form-control" name="price"
                                            placeholder="0.000">
                                    </div>
                                    <div class="form-group form-group-alt row gx-2 gy-0">
                                        <div
                                            class="col-sm-12 @if ($wallet->binance_api_key == null) col-md-6 @else col @endif">
                                            <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                            <div class="form-group form-group-alt input-group mb-3">
                                                <input type="text" name="amount" class="form-control"
                                                    value="0" aria-label="Jumlah Koin"
                                                    aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">SHIB</span>
                                            </div>
                                        </div>
                                        @if ($wallet->binance_api_key == null)
                                            <div class="col-sm-12 col-md-6">
                                                <label for="fee" class="form-label text-dark">Biaya Tambahan
                                                    (Diisi
                                                    apabila manual)</label>
                                                <input type="text" class="form-control" name="fee"
                                                    value="0">
                                            </div>
                                        @endif
                                    </div>
                                    @if ($wallet->binance_api_key == null)
                                        <div class="form-group form-group-alt">
                                            <label for="time" class="form-label text-dark">Waktu</label>
                                            <input type="datetime-local" name="time" class="form-control">
                                        </div>
                                    @endif
                                    <div class="form-group form-group-alt">
                                        <label for="description" class="form-label text-dark">Catatan</label>
                                        <textarea name="description" class="form-control" style="height: 15vh; resize:none"></textarea>
                                    </div>
                                    <div class="form-group form-group-alt">
                                        <label for="total" class="form-label text-dark">Jumlah Transaksi</label>

                                        <div class="form-group input-group form-group-alt">
                                            <span class="input-group-text text-dark"
                                                style="background-color:#e9ecef; font-size: 2em"
                                                id="basic-addon1">$</span>
                                            <input type="text" class="form-control" name="total" value="0"
                                                aria-label="total" aria-describedby="basic-addon1"
                                                style="font-size: 2em" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                        <button type="submit" name="type" value="0"
                                            class="btn btn-primary">Kumpul</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-jual" role="tabpanel"
                                aria-labelledby="pills-jual-tab1">
                                <form
                                    action="{{ route('user.wallet.asset.detail.add', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}"
                                    method="post">
                                    @csrf
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Harga Koin</label>
                                        <input type="text" class="form-control" name="price"
                                            placeholder="0.000">
                                    </div>
                                    <div class="form-group form-group-alt row gx-2 gy-0">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                            <div class="form-group form-group-alt input-group mb-3">
                                                <input type="text" name="amount" class="form-control"
                                                    value="0" aria-label="Jumlah Koin"
                                                    aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">SHIB</span>
                                            </div>

                                        </div>
                                        @if ($wallet->binance_api_key == null)
                                            <div class="col-sm-12 col-md-6">
                                                <label for="fee" class="form-label text-dark">Biaya Tambahan
                                                    (Diisi
                                                    apabila manual)</label>
                                                <input type="text" class="form-control" name="fee"
                                                    value="0">
                                            </div>
                                        @endif
                                    </div>
                                    @if ($wallet->binance_api_key == null)
                                        <div class="form-group form-group-alt">
                                            <label for="time" class="form-label text-dark">Waktu</label>
                                            <input type="datetime-local" name="time" class="form-control">
                                        </div>
                                    @endif
                                    <div class="form-group form-group-alt">
                                        <label for="description" class="form-label text-dark">Catatan</label>
                                        <textarea name="description" class="form-control" style="height: 15vh; resize:none"></textarea>
                                    </div>
                                    <div class="form-group form-group-alt">
                                        <label for="total" class="form-label text-dark">Jumlah Transaksi</label>

                                        <div class="form-group input-group form-group-alt">
                                            <span class="input-group-text text-dark"
                                                style="background-color:#e9ecef; font-size: 2em"
                                                id="basic-addon1">$</span>
                                            <input type="text" class="form-control" name="total" value="0"
                                                aria-label="total" aria-describedby="basic-addon1"
                                                style="font-size: 2em" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                        <button type="submit" name="type" value="1"
                                            class="btn btn-primary">Kumpul</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-transfer" role="tabpanel"
                                aria-labelledby="pills-transfer-tab1">
                                <form
                                    action="{{ route('user.wallet.asset.detail.add', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}"
                                    method="post">
                                    @csrf
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Jenis Transfer</label>
                                        <select class="form-control form-select" name="type">
                                            <option value="3">Transfer Keluar</option>
                                            <option value="4">Transfer Masuk</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-group-alt mb-2">
                                        <label for="price" class="form-label text-dark">Harga Koin</label>
                                        <input type="text" class="form-control" name="price"
                                            placeholder="0.000">
                                    </div>
                                    <div class="form-group form-group-alt row gx-2 gy-0">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                            <div class="form-group form-group-alt input-group mb-3">
                                                <input type="text" name="amount" class="form-control"
                                                    value="0" aria-label="Jumlah Koin"
                                                    aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">SHIB</span>
                                            </div>

                                        </div>
                                        @if ($wallet->binance_api_key == null)
                                            <div class="col-sm-12 col-md-6">
                                                <label for="fee" class="form-label text-dark">Biaya Tambahan
                                                    (Diisi
                                                    apabila manual)</label>
                                                <input type="text" class="form-control" name="fee"
                                                    value="0">
                                            </div>
                                        @endif
                                    </div>
                                    @if ($wallet->binance_api_key == null)
                                        <div class="form-group form-group-alt">
                                            <label for="time" class="form-label text-dark">Waktu</label>
                                            <input type="datetime-local" name="time" class="form-control">
                                        </div>
                                    @endif
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

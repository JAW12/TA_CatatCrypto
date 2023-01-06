@push('scripts')
<script>
    $('#delete-wallet').click(function(e){
        e.preventDefault() // Don't post the form, unless confirmed
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Apakah anda yakin akan menonaktifkan dompet ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya, nonaktifkan!',
            cancelButtonText: 'Tidak',
            }).then((result) => {
            if (result.isConfirmed) {
                $(e.target).closest('form').submit() // Post the surrounding form

            }
        })
    });
</script>
@endpush

<x-app-layout :assets="$assets ?? []">
    <x-back-button />
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-md-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $wallet->name }}
                                @if ($wallet->deleted_at == '')
                                    <span class="badge rounded-pill bg-primary">Aktif</span>
                                @elseif($wallet->deleted_at != '')
                                    <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                @endif
                                @if ($wallet->binance_api_key != '')
                                    <span class="badge rounded-pill" style="background-color: #F3BA2F">Binance</span>
                                @endif
                                <a class="text-dark" data-bs-toggle="modal" data-bs-target="#ubahDompetModal" style="cursor:pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                    </svg>
                                </a>
                                @if ($wallet->binance_api_key != '')
                                    <a href="" class="text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                            <path
                                                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                        </svg>
                                    </a>
                                @endif
                            </h4>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 mt-md-0">
                            @if ($wallet->deleted_at == '')
                            <form action="{{route('user.wallet.delete', $wallet->id)}}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" id="delete-wallet" class="btn btn-secondary">Nonaktifkan</button>
                            </form>
                            @elseif($wallet->deleted_at != '')
                            <form action="{{route('user.wallet.restore', $wallet->id)}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-success">Aktifkan</button>
                            </form>
                            @endif
                            <button type="button" class="btn btn-dark">Lihat Laporan</button>
                            <a href="{{route('user.wallet.asset.add', $wallet->id)}}" class="btn btn-primary">+ Tambah Aset</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-6">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Aset:</strong></td>
                                        <td><strong>$</strong></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><small>Rp</small></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Total Keuntungan:</strong></td>
                                        <td><strong>$</strong></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><small>Rp</small></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table id="wallets-list-table" class="table table-striped table-hover" role="grid"
                                    data-toggle="data-table">
                                    <thead>
                                        <tr class="light">
                                            <th>Nama</th>
                                            <th>Harga Rata-Rata</th>
                                            <th>Harga Sekarang</th>
                                            <th>Jumlah Koin</th>
                                            <th>Keuntungan</th>
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
    </div>
    <div class="modal fade" id="ubahDompetModal" tabindex="-1" aria-labelledby="ubahDompetLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahDompetTitle">Ubah Dompet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{route('user.wallet.update', $wallet->id)}}" method="post">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="name" class="form-label text-dark">Nama Dompet</label>
                                <input type="text" class="form-control" name="name" value="{{$wallet->name}}">
                            </div>
                            <div class="form-group row gx-2 gy-0">
                                <label for="binance_api_key" class="form-label text-dark">Integrasi Binance (Opsional)</label>
                                <div class="col-sm-12 col-md-6">
                                    <input type="text" class="form-control" name="binance_api_key" placeholder="API Key" value={{$wallet->binance_api_key}}>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <input type="text" class="form-control" name="binance_secret_key" placeholder="Secret Key" value={{$wallet->binance_secret_key}}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label text-dark">Catatan</label>
                                <textarea name="description" class="form-control" style="height: 15vh; resize:none">{{$wallet->description}}</textarea>
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

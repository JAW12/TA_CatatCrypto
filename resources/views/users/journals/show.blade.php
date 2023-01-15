@push('scripts')
    <script>
        $(function() {
            $('#delete-journal').click(function(e) {
                e.preventDefault() // Don't post the form, unless confirmed
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan menonaktifkan jurnal ini?",
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
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    <x-back-button>{{ route('user.journal') }}</x-back-button>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="header-title col-sm-12 col-md-5">
                                <h4 class="card-title">{{ $journal->name }}
                                    @if ($journal->deleted_at == '')
                                        <span class="badge rounded-pill bg-primary">Aktif</span>
                                    @elseif($journal->deleted_at != '')
                                        <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                                    @endif
                                    <a class="text-dark" data-bs-toggle="modal" data-bs-target="#ubahjurnalModal"
                                        style="cursor:pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </a>
                                </h4>
                                <h6 class="text-muted row">
                                    <small class="col-sm-12 col-md-5">Resiko per Transaksi:
                                        {{ (float) $journal->risk }}%</small>
                                    <small class="col-sm-12 col-md-7">$200/$500 untuk bulan ini</small>
                                </h6>
                            </div>
                            <div class="col-sm-12 col-md-7 mt-3 mt-md-0">
                                <div class="row g-2">
                                    @if ($journal->deleted_at == '')
                                        <form action="{{ route('user.journal.delete', $journal->id) }}" method="post"
                                            class="col-sm-12 col-md-3">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" id="delete-journal"
                                                class="btn btn-secondary w-100">Nonaktifkan</button>
                                        </form>
                                    @elseif($journal->deleted_at != '')
                                        <form action="{{ route('user.journal.restore', $journal->id) }}" method="post"
                                            class="col-sm-12 col-md-3">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100">Aktifkan</button>
                                        </form>
                                    @endif
                                    <div class="col-sm-12 col-md-4">
                                        <button type="button" class="btn btn-dark w-100">Lihat Laporan Metrik</button>
                                    </div>
                                    <div class="col-sm-12 col-md-5">
                                        <button type="button" class="btn btn-dark w-100">Lihat Laporan Riwayat</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Total Saldo:</strong></td>
                                        <td id="amount_of_assets">
                                            <strong>${{ (float) $journal->balances }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Long:</strong></td>
                                        <td id="long_side"><strong>2 <span id="pnl_long_side" class="ms-2">+$0 (WR 100%)</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Short:</strong></td>
                                        <td id="short_side"><strong>2 <span id="pnl_short_side" class="ms-2">+$0 (WR 100%)</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row g-2  mb-3">
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Catatan:</strong></td>
                                        <td id="amount_trades">
                                            <strong>0</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Persentase Keberhasilan:</strong></td>
                                        <td id="win_rate"><strong>100%</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Total Keuntungan:</strong></td>
                                        <td id="total_pnl"><strong>$0</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row gy-2 gy-md-0 justify-content-center mb-4">
                            <form action="" method="post" class="col-sm-12 col-md-9 row gy-2 gy-md-0 gx-3 gx-md-2 ms-md-0">
                                <div class="col-sm-12 col-md-7 d-flex align-items-center">
                                    <div class="input-group">
                                        <input type="text" name="from" class="form-control vanila-datepicker"
                                            placeholder="Dari tanggal">
                                    </div>
                                    <span class="mx-3">-</span>
                                    <div class="input-group">
                                        <input type="text" name="to" class="form-control vanila-datepicker"
                                            placeholder="Sampai tanggal">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <svg width="18" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="11.7669" cy="11.7666" r="8.98856"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></circle>
                                                <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                            </svg>
                                        </span>
                                        <input name="search" type="text" class="form-control" placeholder="Cari">
                                    </div>
                                </div>
                            </form>
                            <div class="col-sm-12 col-md-3 d-flex justify-content-end">
                                <a href="" class="btn btn-primary @if ($journal->deleted_at != '') disabled @endif w-100">+ Tambah Catatan</a>
                            </div>
                        </div>
                        <h6><strong>Pending</strong></h6>
                        <div class="table-responsive my-3" id="table-container-pending">
                            <table id="pending-table" class="table table-sm table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Koin</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Lev</th>
                                        <th>Margin</th>
                                        <th>Hrg Entri</th>
                                        <th>Hrg SL 1</th>
                                        <th>P/L SL 1</th>
                                        <th>Hrg TP 1</th>
                                        <th>P/L TP 1</th>
                                        <th>Hrg TP 2</th>
                                        <th>P/L TP 2</th>
                                        <th>Hrg TP 3</th>
                                        <th>P/L TP 3</th>
                                        <th>Ratio Resiko</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="list-pending">
                                </tbody>
                            </table>
                        </div>
                        <h6><strong>Aktif</strong></h6>
                        <div class="table-responsive my-3" id="table-container-aktif">
                            <table id="aktif-table" class="table table-sm table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Koin</th>
                                        <th>Tipe</th>
                                        <th>Sisa Jmlh</th>
                                        <th>Lev</th>
                                        <th>Margin</th>
                                        <th>Hrg Entri</th>
                                        <th>Waktu Entri</th>
                                        <th>Hrg SL 1</th>
                                        <th>P/L SL 1</th>
                                        <th>Hrg TP 1</th>
                                        <th>P/L TP 1</th>
                                        <th>Hrg TP 2</th>
                                        <th>P/L TP 2</th>
                                        <th>Hrg TP 3</th>
                                        <th>P/L TP 3</th>
                                        <th>P/L Selesai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="list-aktif">
                                </tbody>
                            </table>
                        </div>
                        <h6><strong>Selesai</strong></h6>
                        <div class="table-responsive my-3" id="table-container-selesai">
                            <table id="selesai-table" class="table table-sm table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Koin</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Lev</th>
                                        <th>Margin</th>
                                        <th>Hrg Entri</th>
                                        <th>Waktu Entri</th>
                                        <th>Hrg Tutup</th>
                                        <th>Waktu Tutup</th>
                                        <th>Durasi</th>
                                        <th>P/L ($)</th>
                                        <th>P/L (%)</th>
                                        <th>W/L</th>
                                        <th>RR RIL</th>
                                        <th>@</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="list-selesai">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ubahjurnalModal" tabindex="-1" aria-labelledby="ubahjurnalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahjurnalTitle">Ubah jurnal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{ route('user.journal.update', $journal->id) }}" method="post">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="name" class="form-label text-dark">Nama Jurnal</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ $journal->name }}">
                            </div>
                            <div class="form-group form-group-alt mb-2">
                                <label for="balances" class="form-label text-dark">Saldo</label>
                                <input type="text" class="form-control" name="balances" placeholder="0"
                                    value={{ $journal->balances }}>
                            </div>
                            <div class="form-group form-group-alt row gx-2 gy-0">
                                <div class="col-sm-12 col-md-6">
                                    <label for="risk" class="form-label text-dark">Resiko Per Transaksi</label>
                                    <input type="text" class="form-control" name="risk" placeholder="0"
                                        value={{ $journal->risk }}>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <label for="target" class="form-label text-dark">Target Per Bulan</label>
                                    <input type="text" class="form-control" name="target" placeholder="0"
                                        value={{ $journal->target }}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label text-dark">Catatan</label>
                                <textarea name="description" class="form-control" style="height: 15vh; resize:none">{{ $journal->description }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button id="btnKumpul" type="submit" class="btn btn-primary">Kumpul</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

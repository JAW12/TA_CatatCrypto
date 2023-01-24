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

            let pending_table = $("#pending-table").DataTable({
                "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                    "destroy": true,
                }
            });
            let aktif_table = $("#aktif-table").DataTable({
                "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                    "destroy": true,
                }
            });
            let selesai_table = $("#selesai-table").DataTable({
                "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                    "destroy": true,
                }
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
                                        <td id="long_side"><strong>2 <span id="pnl_long_side" class="ms-2">+$0 (WR
                                                    100%)</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <table class="text-dark">
                                    <tr>
                                        <td><strong>Jumlah Short:</strong></td>
                                        <td id="short_side"><strong>2 <span id="pnl_short_side" class="ms-2">+$0 (WR
                                                    100%)</span></strong></td>
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
                            <form action="" method="post"
                                class="col-sm-12 col-md-9 row gy-2 gy-md-0 gx-3 gx-md-2 ms-md-0">
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
                                        <input name="search" type="text" class="form-control"
                                            placeholder="Cari">
                                    </div>
                                </div>
                            </form>
                            <div class="col-sm-12 col-md-3 d-flex justify-content-end">
                                <a href="{{ route('user.journal.trade.add', ['journal' => $journal->id]) }}"
                                    class="btn btn-primary @if ($journal->deleted_at != '') disabled @endif w-100">+
                                    Tambah Catatan</a>
                            </div>
                        </div>
                        <h6><strong>Pending</strong></h6>
                        <div class="table-responsive my-3" id="table-container-pending">
                            <table id="pending-table" class="table table-striped table-hover" role="grid"
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
                                    @foreach ($journal->trades as $trade)
                                        @if ($trade->status == 0)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <img src="{{ $trade->asset->thumb }}" alt="coin">
                                                    {{ $trade->asset->name }}
                                                </td>
                                                <td>
                                                    @if ($trade->type == 0)
                                                        <span class="text-danger">SHORT</span>
                                                    @elseif($trade->type == 1)
                                                        <span class="text-success">LONG</span>
                                                    @endif
                                                </td>
                                                <td>{{ (float) $trade->open_quantity }}</td>
                                                <td>{{ $trade->leverage }}</td>
                                                <td>${{ (float) $trade->open_margin }}</td>
                                                <td>${{ (float) $trade->open_price }}</td>
                                                @if (count($trade->targets->where('type', '0')) > 0)
                                                    <td>${{ (float) $trade->targets->where('type', '0')->first()->price }}
                                                    </td>
                                                    <td class="text-danger">
                                                        ${{ (float) $trade->targets->where('type', '0')->first()->pnl }}
                                                        @if ($journal->risk > 0 and $trade->targets->where('type', '0')->first()->pnl > ($journal->balances * $journal->risk) / 100)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-exclamation-triangle-fill mb-1"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                            </svg>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                @endif
                                                @if (count($trade->targets->where('type', '1')) > 0)
                                                    <td>${{ (float) $trade->targets->where('type', '1')->first()->price }}
                                                    </td>
                                                    <td class="text-success">
                                                        ${{ (float) $trade->targets->where('type', '1')->first()->pnl }}
                                                    </td>
                                                    @if (count($trade->targets->where('type', '1')) > 1)
                                                        <td>${{ (float) $trade->targets->where('type', '1')->skip(1)->first()->price }}
                                                        </td>
                                                        <td class="text-success">
                                                            ${{ (float) $trade->targets->where('type', '1')->skip(1)->first()->pnl }}
                                                        </td>
                                                        @if (count($trade->targets->where('type', '1')) > 2)
                                                            <td>${{ (float) $trade->targets->where('type', '1')->skip(2)->first()->price }}
                                                            </td>
                                                            <td class="text-success">
                                                                ${{ (float) $trade->targets->where('type', '1')->skip(2)->first()->pnl }}
                                                            </td>
                                                        @else
                                                            <td>-</td>
                                                            <td>-</td>
                                                        @endif
                                                    @else
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    @endif
                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                @endif
                                                <td>{{ $trade->rr_expected}}</td>
                                                <td>
                                                    <div class="flex align-items-center list-asset-transaction-action">
                                                        <a href="{{route('user.journal.trade.edit', ['journal' => $journal->id, 'trade' => $trade->id])}}" type="button" class="btn btn-sm btn-icon btn-success">
                                                            <span class="btn-inner">
                                                                <svg width="20" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                        <form action="{{route('user.journal.trade.delete', ['journal' => $journal->id, 'trade' => $trade->id])}}" method="post" class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-sm btn-icon btn-danger btn-delete"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="" data-original-title="Delete">
                                                                <span class="btn-inner">
                                                                    <svg width="20" viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        stroke="currentColor">
                                                                        <path
                                                                            d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path d="M20.708 6.23975H3.75"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path
                                                                            d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h6><strong>Aktif</strong></h6>
                        <div class="table-responsive my-3" id="table-container-aktif">
                            <table id="aktif-table" class="table table-striped table-hover" role="grid"
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
                                    @foreach ($journal->trades as $trade)
                                        @if ($trade->status == 1)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <img src="{{ $trade->asset->thumb }}" alt="coin">
                                                    {{ $trade->asset->name }}
                                                </td>
                                                <td>
                                                    @if ($trade->type == 0)
                                                        <span class="text-danger">SHORT</span>
                                                    @elseif($trade->type == 1)
                                                        <span class="text-success">LONG</span>
                                                    @endif
                                                </td>
                                                <td>{{ (float) $trade->quantity_remaining }}</td>
                                                <td>{{ $trade->leverage }}</td>
                                                <td>${{ (float) $trade->margin }}</td>
                                                <td>${{ (float) $trade->open_price }}</td>
                                                <td>{{ $trade->open_time }}</td>
                                                @if (count($trade->targets->where('type', '0')) > 0)
                                                    <td>${{ (float) $trade->targets->where('type', '0')->first()->price }}
                                                    </td>
                                                    <td class="text-danger">
                                                        ${{ (float) $trade->targets->where('type', '0')->first()->pnl }}
                                                        @if ($journal->risk > 0 and $trade->targets->where('type', '0')->first()->pnl > ($journal->balances * $journal->risk) / 100)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-exclamation-triangle-fill mb-1"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                            </svg>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                @endif
                                                @if (count($trade->targets->where('type', '1')) > 0)
                                                    <td>${{ (float) $trade->targets->where('type', '1')->first()->price }}
                                                    </td>
                                                    <td class="text-success">
                                                        ${{ (float) $trade->targets->where('type', '1')->first()->pnl }}
                                                    </td>
                                                    @if (count($trade->targets->where('type', '1')) > 1)
                                                        <td>${{ (float) $trade->targets->where('type', '1')->skip(1)->first()->price }}
                                                        </td>
                                                        <td class="text-success">
                                                            ${{ (float) $trade->targets->where('type', '1')->skip(1)->first()->pnl }}
                                                        </td>
                                                        @if (count($trade->targets->where('type', '1')) > 2)
                                                            <td>${{ (float) $trade->targets->where('type', '1')->skip(2)->first()->price }}
                                                            </td>
                                                            <td class="text-success">
                                                                ${{ (float) $trade->targets->where('type', '1')->skip(2)->first()->pnl }}
                                                            </td>
                                                        @else
                                                            <td>-</td>
                                                            <td>-</td>
                                                        @endif
                                                    @else
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    @endif
                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                @endif
                                                @if($trade->nett_pnl > 0)
                                                <td class="text-success">
                                                    ${{ (float) $trade->nett_pnl }}
                                                </td>
                                                @else
                                                <td class="text-danger">
                                                    -${{ abs((float) $trade->nett_pnl) }}
                                                </td>
                                                @endif
                                                <td>
                                                    <div class="flex align-items-center list-asset-transaction-action">
                                                        <a href="{{route('user.journal.trade.edit', ['journal' => $journal->id, 'trade' => $trade->id])}}" type="button" class="btn btn-sm btn-icon btn-success">
                                                            <span class="btn-inner">
                                                                <svg width="20" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                        <form action="{{route('user.journal.trade.delete', ['journal' => $journal->id, 'trade' => $trade->id])}}" method="post" class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-sm btn-icon btn-danger btn-delete"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="" data-original-title="Delete">
                                                                <span class="btn-inner">
                                                                    <svg width="20" viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        stroke="currentColor">
                                                                        <path
                                                                            d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path d="M20.708 6.23975H3.75"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path
                                                                            d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h6><strong>Selesai</strong></h6>
                        <div class="table-responsive my-3" id="table-container-selesai">
                            <table id="selesai-table" class="table table-striped table-hover" role="grid"
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
                                    @foreach ($journal->trades as $trade)
                                        @if ($trade->status == 2)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <img src="{{ $trade->asset->thumb }}" alt="coin">
                                                    {{ $trade->asset->name }}
                                                </td>
                                                <td>
                                                    @if ($trade->type == 0)
                                                        <span class="text-danger">SHORT</span>
                                                    @elseif($trade->type == 1)
                                                        <span class="text-success">LONG</span>
                                                    @endif
                                                </td>
                                                <td>{{ abs((float) $trade->transactions()->where('type', 1)->sum('quantity')) }}</td>
                                                <td>{{ $trade->leverage }}</td>
                                                <td>${{ abs($trade->transactions()->where('type', 0)->sum('total')) / $trade->leverage  }}</td>
                                                <td>${{ (float) $trade->open_price }}</td>
                                                <td>{{ $trade->open_time }}</td>
                                                <td>${{ (float) $trade->close_price }}</td>
                                                <td>{{ $trade->close_time }}</td>
                                                <td>{{$trade->diff_days > 0 ? $trade->diff_days . ' hari ' : ''}}  {{$trade->diff_hours > 0 ? $trade->diff_hours . ' jam ' : ''}} {{$trade->diff_minutes > 0 ? $trade->diff_minutes . ' menit ' : ''}} {{$trade->diff_seconds > 0 ? $trade->diff_seconds . ' detik ' : ''}}</td>
                                                @if($trade->nett_pnl > 0)
                                                <td class="text-success">
                                                    ${{ number_format((float) $trade->nett_pnl, 2) }}
                                                </td>
                                                <td class="text-success">
                                                    {{ number_format((float) $trade->roe, 2) }}%
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-success ">Win (Menang)</span>
                                                </td>
                                                @else
                                                <td class="text-danger">
                                                    -${{ number_format(abs((float) $trade->nett_pnl, 2) )}}
                                                </td>
                                                <td class="text-danger">
                                                    -{{ number_format(abs((float) $trade->roe), 2) }}%
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-danger ">Loss (Kalah)</span>
                                                </td>
                                                @endif
                                                <td>{{$trade->real_rr}}</td>
                                                <td>{{$trade->closed_at}}</td>
                                                <td>
                                                    <div class="flex align-items-center list-asset-transaction-action">
                                                        <a href="{{route('user.journal.trade.edit', ['journal' => $journal->id, 'trade' => $trade->id])}}" type="button" class="btn btn-sm btn-icon btn-success">
                                                            <span class="btn-inner">
                                                                <svg width="20" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                        <form action="{{route('user.journal.trade.delete', ['journal' => $journal->id, 'trade' => $trade->id])}}" method="post" class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-sm btn-icon btn-danger btn-delete"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="" data-original-title="Delete">
                                                                <span class="btn-inner">
                                                                    <svg width="20" viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        stroke="currentColor">
                                                                        <path
                                                                            d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path d="M20.708 6.23975H3.75"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path
                                                                            d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
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

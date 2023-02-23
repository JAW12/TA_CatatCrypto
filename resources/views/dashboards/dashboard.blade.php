@section('title', 'Dashbor User')
<x-app-layout :options="['loading']">

    @if (date('Y-m-d', strtotime('+3days')) >= date_format(date_create(auth()->user()->membership_till), 'Y-m-d'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 999999">
            <div class="toast fade show bg-primary text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice"
                        focusable="false">
                        <rect width="100%" height="100%" fill="#007aff"></rect>
                    </svg>
                    <strong class="me-auto">Catat Crypto</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Membership anda akan berakhir {{ now()->diffInDays(auth()->user()->membership_till)}} hari lagi
                </div>
            </div>
        </div>
    @endif
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @elseif(Auth::user()->user_type != 'admin')
        @push('scripts')
            <script>
                $(function() {
                    var pending_table = $("#pending-table").DataTable({
                        "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                            "destroy": true,
                        },
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            // Remove the formatting to get integer data for summation
                            var intVal = function(i) {
                                return typeof i === 'string' ? i.replace(/[\$,%]/g, '') * 1 : typeof i ===
                                    'number' ? i : 0;
                            };

                            totalMargin = api
                                .column(6, {
                                    filter: 'applied'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pageTotalMargin = api
                                .column(6, {
                                    filter: 'applied',
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            totalPNL_SL = api
                                .column(9, {
                                    filter: 'applied'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pagetotalPNL_SL = api
                                .column(9, {
                                    filter: 'applied',
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            htmlPNL_SL = "";
                            if (pagetotalPNL_SL > 0) {
                                htmlPNL_SL =
                                    `<span class="text-danger me-1">$${pagetotalPNL_SL.toFixed(2)}</span>`;
                            } else {
                                htmlPNL_SL = "<span class='me-1'>$0.00</span>";
                            }

                            if (totalPNL_SL > 0) {
                                htmlPNL_SL +=
                                    `<span class="text-danger">(Total: $${totalPNL_SL.toFixed(2)})</span>`;
                            } else {
                                htmlPNL_SL += "(Total: $0.00)";
                            }

                            totalPNL_TP1 = api
                                .column(11, {
                                    filter: 'applied'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pagetotalPNL_TP1 = api
                                .column(11, {
                                    filter: 'applied',
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            htmlPNL_TP1 = "";
                            if (pagetotalPNL_TP1 > 0) {
                                htmlPNL_TP1 =
                                    `<span class="text-success me-1">$${pagetotalPNL_TP1.toFixed(2)}</span>`;
                            } else {
                                htmlPNL_TP1 = "<span class='me-1'>$0.00</span>";
                            }

                            if (totalPNL_TP1 > 0) {
                                htmlPNL_TP1 +=
                                    `<span class="text-success">(Total: $${totalPNL_TP1.toFixed(2)})</span>`;
                            } else {
                                htmlPNL_TP1 += "(Total: $0.00)";
                            }

                            totalPNL_TP2 = api
                                .column(13, {
                                    filter: 'applied'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pagetotalPNL_TP2 = api
                                .column(13, {
                                    filter: 'applied',
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            htmlPNL_TP2 = "";
                            if (pagetotalPNL_TP2 > 0) {
                                htmlPNL_TP2 =
                                    `<span class="text-success me-1">$${pagetotalPNL_TP2.toFixed(2)}</span>`;
                            } else {
                                htmlPNL_TP2 = "<span class='me-1'>$0.00</span>";
                            }

                            if (totalPNL_TP2 > 0) {
                                htmlPNL_TP2 +=
                                    `<span class="text-success">(Total: $${totalPNL_TP2.toFixed(2)})</span>`;
                            } else {
                                htmlPNL_TP2 += "(Total: $0.00)";
                            }

                            totalPNL_TP3 = api
                                .column(15, {
                                    filter: 'applied'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pagetotalPNL_TP3 = api
                                .column(15, {
                                    filter: 'applied',
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            htmlPNL_TP3 = "";
                            if (pagetotalPNL_TP3 > 0) {
                                htmlPNL_TP3 =
                                    `<span class="text-success me-1">$${pagetotalPNL_TP3.toFixed(2)}</span>`;
                            } else {
                                htmlPNL_TP3 = "<span class='me-1'>$0.00</span>";
                            }

                            if (totalPNL_TP3 > 0) {
                                htmlPNL_TP3 +=
                                    `<span class="text-success">(Total: $${totalPNL_TP3.toFixed(2)})</span>`;
                            } else {
                                htmlPNL_TP3 += "(Total: $0.00)";
                            }

                            totalRR = api
                                .column(16, {
                                    filter: 'applied'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            totalRR = totalRR / api.column(16, {
                                filter: 'applied'
                            }).data().count();

                            pageTotalRR = api
                                .column(16, {
                                    filter: 'applied',
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pageTotalRR = pageTotalRR / api.column(16, {
                                filter: 'applied'
                            }).data().count();

                            htmlRR = "";
                            if (pageTotalRR > 0) {
                                htmlRR = `<span class="text-success me-1">${pageTotalRR.toFixed(2)}</span>`;
                            } else if (pageTotalRR < 0) {
                                htmlRR =
                                    `<span class="text-danger me-1">-${Math.abs(pageTotalRR).toFixed(2)}</span>`;
                            } else {
                                htmlRR = "<span class='me-1'>0.00</span>";
                            }

                            if (totalRR > 0) {
                                htmlRR += `<span class="text-success">(Total: ${totalRR.toFixed(2)})</span>`;
                            } else if (totalRR < 0) {
                                htmlRR +=
                                    `<span class="text-danger">(Total: -${Math.abs(totalRR).toFixed(2)})</span>`;
                            } else {
                                htmlRR += "(Total: 0.00)";
                            }


                            // Update footer
                            $(api.column(6).footer()).html(
                                `$${pageTotalMargin.toFixed(2)} (Total: $${totalMargin.toFixed(2)})`);

                            $(api.column(9).footer()).html(htmlPNL_SL);
                            $(api.column(11).footer()).html(htmlPNL_TP1);
                            $(api.column(13).footer()).html(htmlPNL_TP2);
                            $(api.column(15).footer()).html(htmlPNL_TP3);
                            $(api.column(16).footer()).html(htmlRR);
                        },
                    });
                });
            </script>
        @endpush
        <h2 class="mb-3"><strong>Dashbor</strong></h2>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="bg-primary text-white rounded p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-wallet" viewBox="0 0 16 16">
                                    <path
                                        d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z" />
                                </svg>
                            </div>
                            <div class="text-end">
                                Dompet
                                <h2 class="counter">{{ count(auth()->user()->wallets) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="bg-primary text-white rounded p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-journals" viewBox="0 0 16 16">
                                    <path
                                        d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2 2 2 0 0 1-2 2H3a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1H1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v9a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2z" />
                                    <path
                                        d="M1 6v-.5a.5.5 0 0 1 1 0V6h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V9h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 2.5v.5H.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2v-.5a.5.5 0 0 0-1 0z" />
                                </svg>
                            </div>
                            <div class="text-end">
                                Jurnal Trading
                                <h2 class="counter">{{ count(auth()->user()->journals) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="bg-primary text-white rounded p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-file-earmark-richtext" viewBox="0 0 16 16">
                                    <path
                                        d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                                    <path
                                        d="M4.5 12.5A.5.5 0 0 1 5 12h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 10h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm1.639-3.708 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047l1.888.974V8.5a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V8s1.54-1.274 1.639-1.208zM6.25 6a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5z" />
                                </svg>
                            </div>
                            <div class="text-end">
                                Catatan Trading
                                <h2 class="counter">{{ auth()->user()->journals->sum('count_of_trades') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3 text-muted">Dompet</h5>
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <h2 class="mb-2">
                                    ${{ number_format((float) auth()->user()->wallets->sum('amount_of_assets'),2,'.',',') }}
                                </h2>
                                <p class="mb-0 text-secondary">Jumlah Aset</p>
                            </div>
                            <hr class="hr-vertial">
                            <div class="bg-primary text-white rounded p-3 px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35"
                                    fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                    <path
                                        d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z" />
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path
                                        d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z" />
                                </svg>
                            </div>
                            <hr class="hr-vertial">
                            <div>
                                <h2
                                    class="mb-2 @if (auth()->user()->wallets->sum('pnl') > 0) text-success @elseif(auth()->user()->wallets->sum('pnl') < 0) text-danger @endif">
                                    @if (auth()->user()->wallets->sum('pnl') < 0)
                                        -
                                    @elseif (auth()->user()->wallets->sum('pnl') > 0)
                                        +
                                    @endif
                                    ${{ number_format(abs((float) auth()->user()->wallets->sum('pnl')),2,'.',',') }}
                                </h2>
                                <p class="mb-0 text-secondary">Keuntungan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3 text-muted">Jurnal Trading</h5>
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <h2 class="mb-2">
                                    ${{ number_format((float) auth()->user()->journals->sum('balances'),2) }}
                                </h2>
                                <p class="mb-0 text-secondary">Total Saldo</p>
                            </div>
                            <hr class="hr-vertial">
                            <div class="bg-primary text-white rounded p-3 px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35"
                                    fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z" />
                                </svg>
                            </div>
                            <hr class="hr-vertial">
                            <div>
                                <h2
                                    class="mb-2 @if (auth()->user()->journals->sum('pnl') > 0) text-success @elseif(auth()->user()->journals->sum('pnl') < 0) text-danger @endif">
                                    @if (auth()->user()->journals->sum('pnl') < 0)
                                        -
                                    @elseif (auth()->user()->journals->sum('pnl') > 0)
                                        +
                                    @endif
                                    ${{ number_format(abs((float) auth()->user()->journals->sum('pnl')),2,'.',',') }}
                                </h2>
                                <p class="mb-0 text-secondary">Keuntungan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="header-title">
                                <h4 class="card-title">Pending Trading</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive my-3" id="table-container-pending">
                            <table id="pending-table" class="table table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Jurnal</th>
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
                                    </tr>
                                </thead>
                                <tbody id="list-pending">
                                    @foreach (auth()->user()->journals as $k_journal => $journal)
                                        @foreach ($journal->trades->where('status', 0) as $trade)
                                            <tr onclick="window.location='{{ route('user.journal.trade.edit', ['journal' => $journal->id, 'trade' => $trade->id]) }}'"
                                                style="cursor: pointer;">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $trade->journal->name }}</td>
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
                                                        @if (
                                                            $journal->risk > 0 and
                                                                $trade->targets->where('type', '0')->first()->pnl > ($journal->balances * $journal->risk) / 100)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-exclamation-triangle-fill mb-1"
                                                                viewBox="0 0 16 16" data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Resiko diatas {{ $journal->risk }}%">
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
                                                <td>{{ $trade->rr_expected }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(Auth::user()->user_type == 'admin')
    @endif
    {{-- <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="row row-cols-1">
                <div class="d-slider1 overflow-hidden ">
                    <ul class="swiper-wrapper list-inline m-0 p-0 mb-2">
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-01"
                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                        data-min-value="0" data-max-value="100" data-value="90" data-type="percent">
                                        <svg class="card-slie-arrow " width="24" height="24px" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Total Sales</p>
                                        <h4 class="counter" style="visibility: visible;">$560K</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-02"
                                        class="circle-progress-01 circle-progress circle-progress-info text-center"
                                        data-min-value="0" data-max-value="100" data-value="80" data-type="percent">
                                        <svg class="card-slie-arrow " width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Total Profit</p>
                                        <h4 class="counter">$185K</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-03"
                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                        data-min-value="0" data-max-value="100" data-value="70" data-type="percent">
                                        <svg class="card-slie-arrow " width="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Total Cost</p>
                                        <h4 class="counter">$375K</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-04"
                                        class="circle-progress-01 circle-progress circle-progress-info text-center"
                                        data-min-value="0" data-max-value="100" data-value="60" data-type="percent">
                                        <svg class="card-slie-arrow " width="24px" height="24px" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Revenue</p>
                                        <h4 class="counter">$742K</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1100">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-05"
                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                        data-min-value="0" data-max-value="100" data-value="50" data-type="percent">
                                        <svg class="card-slie-arrow " width="24px" height="24px" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Net Income</p>
                                        <h4 class="counter">$150K</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1200">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-06"
                                        class="circle-progress-01 circle-progress circle-progress-info text-center"
                                        data-min-value="0" data-max-value="100" data-value="40" data-type="percent">
                                        <svg class="card-slie-arrow " width="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Today</p>
                                        <h4 class="counter">$4600</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1300">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-07"
                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                        data-min-value="0" data-max-value="100" data-value="30" data-type="percent">
                                        <svg class="card-slie-arrow " width="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Members</p>
                                        <h4 class="counter">11.2M</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="swiper-button swiper-button-next"></div>
                    <div class="swiper-button swiper-button-prev"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" data-aos="fade-up" data-aos-delay="800">
                        <div class="card-header d-flex justify-content-between flex-wrap">
                            <div class="header-title">
                                <h4 class="card-title">$855.8K</h4>
                                <p class="mb-0">Gross Sales</p>
                            </div>
                            <div class="d-flex align-items-center align-self-center">
                                <div class="d-flex align-items-center text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <g id="Solid dot2">
                                            <circle id="Ellipse 65" cx="12" cy="12" r="8"
                                                fill="currentColor"></circle>
                                        </g>
                                    </svg>
                                    <div class="ms-2">
                                        <span class="text-secondary">Sales</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center ms-3 text-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <g id="Solid dot3">
                                            <circle id="Ellipse 66" cx="12" cy="12" r="8"
                                                fill="currentColor"></circle>
                                        </g>
                                    </svg>
                                    <div class="ms-2">
                                        <span class="text-secondary">Cost</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="text-secondary dropdown-toggle" id="dropdownMenuButton2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    This Week
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="d-main" class="d-main"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card" data-aos="fade-up" data-aos-delay="1000">
                        <div class="card-header d-flex justify-content-between flex-wrap">
                            <div class="header-title">
                                <h4 class="card-title">Earnings</h4>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="text-secondary dropdown-toggle" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    This Week
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div id="myChart" class="col-md-8 col-lg-8 myChart"></div>
                                <div class="d-grid gap col-md-4 col-lg-4">
                                    <div class="d-flex align-items-start">
                                        <svg class="mt-2" xmlns="http://www.w3.org/2000/svg" width="14"
                                            viewBox="0 0 24 24" fill="#3a57e8">
                                            <g id="Solid dot">
                                                <circle id="Ellipse 67" cx="12" cy="12" r="8"
                                                    fill="#3a57e8"></circle>
                                            </g>
                                        </svg>
                                        <div class="ms-3">
                                            <span class="text-secondary">Fashion</span>
                                            <h6>251K</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <svg class="mt-2" xmlns="http://www.w3.org/2000/svg" width="14"
                                            viewBox="0 0 24 24" fill="#4bc7d2">
                                            <g id="Solid dot1">
                                                <circle id="Ellipse 68" cx="12" cy="12" r="8"
                                                    fill="#4bc7d2"></circle>
                                            </g>
                                        </svg>
                                        <div class="ms-3">
                                            <span class="text-secondary">Accessories</span>
                                            <h6>176K</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card" data-aos="fade-up" data-aos-delay="1200">
                        <div class="card-header d-flex justify-content-between flex-wrap">
                            <div class="header-title">
                                <h4 class="card-title">Conversions</h4>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="text-secondary dropdown-toggle" id="dropdownMenuButton3"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    This Week
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton3">
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="d-activity" class="d-activity"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="card overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-header d-flex justify-content-between flex-wrap">
                            <div class="header-title">
                                <h4 class="card-title mb-2">Enterprise Clients</h4>
                                <p class="mb-0">
                                    <svg class="me-2" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="#3a57e8"
                                            d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                                    </svg>
                                    15 new acquired this month
                                </p>
                            </div>
                            <div class="dropdown">
                                <span class="dropdown-toggle" id="dropdownMenuButton7" data-bs-toggle="dropdown"
                                    aria-expanded="false" role="button">
                                </span>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton7">
                                    <a class="dropdown-item " href="javascript:void(0);">Action</a>
                                    <a class="dropdown-item " href="javascript:void(0);">Another action</a>
                                    <a class="dropdown-item " href="javascript:void(0);">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive mt-4">
                                <table id="basic-table" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>COMPANIES</th>
                                            <th>CONTACTS</th>
                                            <th>ORDER</th>
                                            <th>COMPLETION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="bg-soft-primary rounded img-fluid avatar-40 me-3"
                                                        src="{{ asset('images/shapes/01.png') }}" alt="profile">
                                                    <h6>Addidis Sportwear</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="iq-media-group iq-media-group-1">
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">SP</div>
                                                    </a>
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">PP</div>
                                                    </a>
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">MM</div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>$14,000</td>
                                            <td>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h6>60%</h6>
                                                </div>
                                                <div class="progress bg-soft-primary shadow-none w-100"
                                                    style="height: 4px">
                                                    <div class="progress-bar bg-primary" data-toggle="progress-bar"
                                                        role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="bg-soft-primary rounded img-fluid avatar-40 me-3"
                                                        src="{{ asset('images/shapes/05.png') }}" alt="profile">
                                                    <h6>Netflixer Platforms</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="iq-media-group iq-media-group-1">
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">SP</div>
                                                    </a>
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">PP</div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>$30,000</td>
                                            <td>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h6>25%</h6>
                                                </div>
                                                <div class="progress bg-soft-primary shadow-none w-100"
                                                    style="height: 4px">
                                                    <div class="progress-bar bg-primary" data-toggle="progress-bar"
                                                        role="progressbar" aria-valuenow="25" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="bg-soft-primary rounded img-fluid avatar-40 me-3"
                                                        src="{{ asset('images/shapes/02.png') }}" alt="profile">
                                                    <h6>Shopifi Stores</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="iq-media-group iq-media-group-1">
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">PP</div>
                                                    </a>
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">TP</div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>$8,500</td>
                                            <td>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h6>100%</h6>
                                                </div>
                                                <div class="progress bg-soft-success shadow-none w-100"
                                                    style="height: 4px">
                                                    <div class="progress-bar bg-success" data-toggle="progress-bar"
                                                        role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="bg-soft-primary rounded img-fluid avatar-40 me-3"
                                                        src="{{ asset('images/shapes/03.png') }}" alt="profile">
                                                    <h6>Bootstrap Technologies</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="iq-media-group iq-media-group-1">
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">SP</div>
                                                    </a>
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">PP</div>
                                                    </a>
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">MM</div>
                                                    </a>
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">TP</div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>$20,500</td>
                                            <td>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h6>100%</h6>
                                                </div>
                                                <div class="progress bg-soft-success shadow-none w-100"
                                                    style="height: 4px">
                                                    <div class="progress-bar bg-success" data-toggle="progress-bar"
                                                        role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="bg-soft-primary rounded img-fluid avatar-40 me-3"
                                                        src="{{ asset('images/shapes/04.png') }}" alt="profile">
                                                    <h6>Community First</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="iq-media-group iq-media-group-1">
                                                    <a href="#" class="iq-media-1">
                                                        <div class="icon iq-icon-box-3 rounded-pill">MM</div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>$9,800</td>
                                            <td>
                                                <div class="d-flex align-items-center mb-2">
                                                    <h6>100%</h6>
                                                </div>
                                                <div class="progress bg-soft-success shadow-none w-100"
                                                    style="height: 4px">
                                                    <div class="progress-bar bg-success" data-toggle="progress-bar"
                                                        role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="row">
                <div class="col-md-6 col-lg-12">
                    <div class="card credit-card-widget" data-aos="fade-up" data-aos-delay="900">
                        <div class="card-header pb-4 border-0">
                            <div class="p-4 primary-gradient-card rounded border border-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="font-weight-bold">VISA </h5>
                                        <P class="mb-0">PREMIUM ACCOUNT</P>
                                    </div>
                                    <div class="master-card-content">
                                        <svg class="master-card-1" width="60" height="60"
                                            viewBox="0 0 24 24">
                                            <path fill="#ffffff"
                                                d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>
                                        <svg class="master-card-2" width="60" height="60"
                                            viewBox="0 0 24 24">
                                            <path fill="#ffffff"
                                                d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="my-4">
                                    <div class="card-number">
                                        <span class="fs-5 me-2">5789</span>
                                        <span class="fs-5 me-2">****</span>
                                        <span class="fs-5 me-2">****</span>
                                        <span class="fs-5">2847</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                    <p class="mb-0">Card holder</p>
                                    <p class="mb-0">Expire Date</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Mike Smith</h6>
                                    <h6 class="ms-5">06/11</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-itmes-center flex-wrap  mb-4">
                                <div class="d-flex align-itmes-center me-0 me-md-4">
                                    <div>
                                        <div class="p-3 mb-2 rounded bg-soft-primary">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16.9303 7C16.9621 6.92913 16.977 6.85189 16.9739 6.77432H17C16.8882 4.10591 14.6849 2 12.0049 2C9.325 2 7.12172 4.10591 7.00989 6.77432C6.9967 6.84898 6.9967 6.92535 7.00989 7H6.93171C5.65022 7 4.28034 7.84597 3.88264 10.1201L3.1049 16.3147C2.46858 20.8629 4.81062 22 7.86853 22H16.1585C19.2075 22 21.4789 20.3535 20.9133 16.3147L20.1444 10.1201C19.676 7.90964 18.3503 7 17.0865 7H16.9303ZM15.4932 7C15.4654 6.92794 15.4506 6.85153 15.4497 6.77432C15.4497 4.85682 13.8899 3.30238 11.9657 3.30238C10.0416 3.30238 8.48184 4.85682 8.48184 6.77432C8.49502 6.84898 8.49502 6.92535 8.48184 7H15.4932ZM9.097 12.1486C8.60889 12.1486 8.21321 11.7413 8.21321 11.2389C8.21321 10.7366 8.60889 10.3293 9.097 10.3293C9.5851 10.3293 9.98079 10.7366 9.98079 11.2389C9.98079 11.7413 9.5851 12.1486 9.097 12.1486ZM14.002 11.2389C14.002 11.7413 14.3977 12.1486 14.8858 12.1486C15.3739 12.1486 15.7696 11.7413 15.7696 11.2389C15.7696 10.7366 15.3739 10.3293 14.8858 10.3293C14.3977 10.3293 14.002 10.7366 14.002 11.2389Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h5>1153</h5>
                                        <small class="mb-0">Products</small>
                                    </div>
                                </div>
                                <div class="d-flex align-itmes-center">
                                    <div>
                                        <div class="p-3 mb-2 rounded bg-soft-info">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.1213 11.2331H16.8891C17.3088 11.2331 17.6386 10.8861 17.6386 10.4677C17.6386 10.0391 17.3088 9.70236 16.8891 9.70236H14.1213C13.7016 9.70236 13.3719 10.0391 13.3719 10.4677C13.3719 10.8861 13.7016 11.2331 14.1213 11.2331ZM20.1766 5.92749C20.7861 5.92749 21.1858 6.1418 21.5855 6.61123C21.9852 7.08067 22.0551 7.7542 21.9652 8.36549L21.0159 15.06C20.8361 16.3469 19.7569 17.2949 18.4879 17.2949H7.58639C6.25742 17.2949 5.15828 16.255 5.04837 14.908L4.12908 3.7834L2.62026 3.51807C2.22057 3.44664 1.94079 3.04864 2.01073 2.64043C2.08068 2.22305 2.47038 1.94649 2.88006 2.00874L5.2632 2.3751C5.60293 2.43735 5.85274 2.72207 5.88272 3.06905L6.07257 5.35499C6.10254 5.68257 6.36234 5.92749 6.68209 5.92749H20.1766ZM7.42631 18.9079C6.58697 18.9079 5.9075 19.6018 5.9075 20.459C5.9075 21.3061 6.58697 22 7.42631 22C8.25567 22 8.93514 21.3061 8.93514 20.459C8.93514 19.6018 8.25567 18.9079 7.42631 18.9079ZM18.6676 18.9079C17.8282 18.9079 17.1487 19.6018 17.1487 20.459C17.1487 21.3061 17.8282 22 18.6676 22C19.4969 22 20.1764 21.3061 20.1764 20.459C20.1764 19.6018 19.4969 18.9079 18.6676 18.9079Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h5>81K</h5>
                                        <small class="mb-0">Order Served</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between flex-wrap">
                                    <h2 class="mb-2">$405,012,300</h2>
                                    <div>
                                        <span class="badge bg-success rounded-pill">YoY 24%</span>
                                    </div>
                                </div>
                                <p class="text-info">Life time sales</p>
                            </div>
                            <div class="d-grid grid-cols-2 gap">
                                <button class="btn btn-primary text-uppercase p-2">SUMMARY</button>
                                <button class="btn btn-info text-uppercase p-2">ANALYTICS</button>
                            </div>
                        </div>
                    </div>
                    <div class="card" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-body d-flex justify-content-around text-center">
                            <div>
                                <h2 class="mb-2">750<small>K</small></h2>
                                <p class="mb-0 text-secondary">Website Visitors</p>
                            </div>
                            <hr class="hr-vertial">
                            <div>
                                <h2 class="mb-2">7,500</h2>
                                <p class="mb-0 text-secondary">New Customers</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="card" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-header d-flex justify-content-between flex-wrap">
                            <div class="header-title">
                                <h4 class="card-title mb-2">Activity overview</h4>
                                <p class="mb-0">
                                    <svg class="me-2" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="#17904b"
                                            d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" />
                                    </svg>
                                    16% this month
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class=" d-flex profile-media align-items-top mb-2">
                                <div class="profile-dots-pills border-primary mt-1"></div>
                                <div class="ms-4">
                                    <h6 class=" mb-1">$2400, Purchase</h6>
                                    <span class="mb-0">11 JUL 8:10 PM</span>
                                </div>
                            </div>
                            <div class=" d-flex profile-media align-items-top mb-2">
                                <div class="profile-dots-pills border-primary mt-1"></div>
                                <div class="ms-4">
                                    <h6 class=" mb-1">New order #8744152</h6>
                                    <span class="mb-0">11 JUL 11 PM</span>
                                </div>
                            </div>
                            <div class=" d-flex profile-media align-items-top mb-2">
                                <div class="profile-dots-pills border-primary mt-1"></div>
                                <div class="ms-4">
                                    <h6 class=" mb-1">Affiliate Payout</h6>
                                    <span class="mb-0">11 JUL 7:64 PM</span>
                                </div>
                            </div>
                            <div class=" d-flex profile-media align-items-top mb-2">
                                <div class="profile-dots-pills border-primary mt-1"></div>
                                <div class="ms-4">
                                    <h6 class=" mb-1">New user added</h6>
                                    <span class="mb-0">11 JUL 1:21 AM</span>
                                </div>
                            </div>
                            <div class=" d-flex profile-media align-items-top mb-1">
                                <div class="profile-dots-pills border-primary mt-1"></div>
                                <div class="ms-4">
                                    <h6 class=" mb-1">Product added</h6>
                                    <span class="mb-0">11 JUL 4:50 AM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</x-app-layout>

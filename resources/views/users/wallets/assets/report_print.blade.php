@section('report-title')
    <img src="{{ $asset->thumb }}" alt="logo_crypto" class="me-2 img-thumbnail" style="width: 3%;"> Laporan Aset
    {{ $asset->name }} Dompet {{ $wallet->name }}
@endsection
@push('styles')
    <style>
        @media print {
            .table-responsive {
                overflow: visible;
            }

            table {
                overflow: visible;
            }

            html,
            body {
                height: 99%;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        var options = {
            chart: {
                type: 'line',
                width: '100%',
                height: 350,
                stacked: false,
                zoom: {
                    type: 'x',
                    enabled: true,
                    autoScaleYaxis: true
                },
            },
            stroke: {
                curve: 'smooth',
            },
            markers: {
                size: 5,
            },
            series: [{
                    name: 'Kuantitas',
                    data: {!! json_encode($quantityData) !!}
                },
                {
                    name: 'Nilai (USD)',
                    data: {!! json_encode($valueData) !!}
                }
            ],
            xaxis: {
                type: 'datetime',
                title: {
                    text: 'Tanggal',
                }
            },
            yaxis: [{
                    opposite: true,

                    title: {
                        text: 'Kuantitas'
                    }
                },
                {
                    title: {
                        text: 'Nilai (USD)'
                    }
                }
            ]
        };

        var line = new ApexCharts(document.querySelector("#line"), options);
        line.render();
        // var value = new ApexCharts(document.querySelector("#value"), optionsValue);
        // value.render();
        setTimeout(function() {
            window.print();
        }, 1000);

        // console.log({!! json_encode($quantityData) !!}.pop()['x']);
        var beforePrint = function() {
            line.zoomX(
                new Date({!! json_encode($quantityData) !!}[0]['x']).getTime(),
                new Date({!! json_encode($quantityData) !!}.pop()['x']).getTime()
            )
            line.updateOptions({
                chart:{
                    toolbar: {
                        show: false,
                    },
                }
            });
        };

        var afterPrint = function() {
            line.updateOptions({
                chart:{
                    toolbar: {
                        show: true,
                    },
                }
            });
        };

        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    beforePrint();
                } else {
                    afterPrint();
                }
            });
        }

        window.onbeforeprint = beforePrint;
        window.onafterprint = afterPrint;
    </script>
@endpush
<x-print-layout>
    <h5 class="text-muted mb-2"><strong>Ringkasan</strong></h5>
    <hr>
    <div class="d-flex justify-content-between mb-3">
        <table class="text-dark">
            <tr>
                <td><strong>Jumlah Koin:</strong></td>
                <td><strong>
                    @if ($asset_wallet->amount > 999){{ number_format((float) $asset_wallet->amount, 2) }}
                    @else{{ (float) $asset_wallet->amount }}
                    @endif
                    {{ $asset->symbol }}
                </strong></td>
            </tr>
            <tr>
                <td></td>
                <td><small>$@if ($asset_wallet->total > 999){{ number_format((float) $asset_wallet->total, 2) }}
                    @else{{ (float) $asset_wallet->total }}
                    @endif</small></td>
            </tr>
        </table>
        <table class="text-dark">
            <tr>
                <td class="align-top"><strong>Harga Rata-Rata:</strong></td>
                <td class="align-top"><strong>$@if ($asset_wallet->average_price > 999){{ number_format((float) $asset_wallet->average_price, 2) }}
                    @else{{ (float) $asset_wallet->average_price }}
                    @endif</strong></td>
            </tr>
        </table>
        <table class="text-dark">
            <tr>
                <td class="align-top"><strong>Total Keuntungan:</strong></td>
                <td class="align-top"><strong><span
                            class="@if ($asset_wallet->pnl > 0) text-success @elseif($asset_wallet->pnl < 0) text-danger @endif">
                            @if ($asset_wallet->pnl < 0)
                                -
                            @endif
                            ${{ number_format(abs((float) $asset_wallet->pnl), 2, '.', ',') }}({{ (float) $asset_wallet->pnl_percentage }}%)
                        </span></strong></td>
            </tr>
        </table>
    </div>
    <div class="mb-3">
        <h5 class="text-muted mb-2"><strong>Grafik Perubahan Kumulatif</strong></h5>
        <hr>
        <div id="line"></div>
    </div>
    <div>
        <h5 class="text-muted mb-2"><strong>Daftar Transaksi</strong></h5>
        <hr>
        <div class="table-responsive">
            <table id="assets-list-table" class="table table-striped table-hover" role="grid"
                data-toggle="data-table">
                <thead>
                    <tr class="light">
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Jumlah Aset</th>
                        <th>Biaya Tambahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asset_wallet->transactions->sortByDesc('time') as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $transaction->time }}
                            </td>
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
                                @if ($transaction->type == 0 or $transaction->type == 1)
                                    $@if ($transaction->price > 1)
                                        {{ number_format((float) $transaction->price, 2, '.', ',') }}
                                    @elseif(strlen(substr(strrchr($transaction->price, '.'), 1)) > 8)
                                        {{ number_format((float) $transaction->price, 8, '.', ',') }}
                                        @else{{ (float) $transaction->price }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($transaction->type == 0 or $transaction->type == 3)
                                    +@if ($transaction->amount > 1)
                                        {{ number_format((float) $transaction->amount, 2, '.', ',') }}
                                    @elseif(strlen(substr(strrchr($transaction->amount, '.'), 1)) > 8)
                                        {{ number_format((float) $transaction->amount, 8, '.', ',') }}
                                        @else{{ (float) $transaction->amount }}
                                    @endif{{ $transaction->asset_wallet->asset->symbol }}
                                    <br>
                                    @if ($transaction->type == 0)
                                        +@if ($transaction->total > 1)
                                            {{ number_format((float) $transaction->total, 2, '.', ',') }}
                                        @elseif(strlen(substr(strrchr($transaction->total, '.'), 1)) > 8)
                                            {{ number_format((float) $transaction->total, 8, '.', ',') }}
                                            @else{{ (float) $transaction->total }}
                                        @endif$
                                    @elseif($transaction->type == 3)
                                        -
                                    @endif
                                @elseif($transaction->type == 1 or $transaction->type == 2)
                                    -@if (abs($transaction->amount) > 1)
                                        {{ number_format((float) abs($transaction->amount), 2, '.', ',') }}
                                    @elseif(strlen(substr(strrchr(abs($transaction->amount), '.'), 1)) > 8)
                                        {{ number_format((float) abs($transaction->amount), 8, '.', ',') }}
                                        @else{{ (float) abs($transaction->amount) }}
                                    @endif{{ $transaction->asset_wallet->asset->symbol }}<br>
                                    @if ($transaction->type == 1)
                                        -@if (abs($transaction->total) > 1)
                                            {{ number_format((float) abs($transaction->total), 2, '.', ',') }}
                                        @elseif(strlen(substr(strrchr(abs($transaction->total), '.'), 1)) > 8)
                                            {{ number_format((float) abs($transaction->total), 8, '.', ',') }}
                                            @else{{ (float) abs($transaction->total) }}
                                        @endif$
                                    @elseif($transaction->type == 2)
                                        -
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($transaction->fee > 1)
                                    ${{ number_format((float) $transaction->fee, 2, '.', ',') }}
                                @elseif(strlen(substr(strrchr($transaction->fee, '.'), 1)) > 8)
                                    ${{ number_format((float) $transaction->fee, 8, '.', ',') }}
                                @elseif($transaction->fee == 0)
                                    -
                                @else${{ (float) $transaction->fee }}
                                @endif
                            </td>
                            {{-- <td>{{number_format($cumulativeAmount, 0)}}</td>
                        <td>{{number_format($cumulativeTotal, 0)}}</td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Data tidak tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-print-layout>

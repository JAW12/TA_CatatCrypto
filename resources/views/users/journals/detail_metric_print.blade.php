@section('report-title')
    Laporan Metrik {{ $journal->name }}
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
                width: '100%',
                height: 500,
                type: 'area',
                stacked: true,
            },
            stroke: {
                curve: 'smooth',
            },
            markers: {
                size: 5,
            },
            xaxis: {
                type: 'datetime',
                categories: <?php echo json_encode($categories); ?>,
                title: {
                    text: 'Tanggal'
                }
            },
            yaxis: {
                title: {
                    text: 'Keuntungan'
                }
            },
            series: [{
                    name: 'Keuntungan per Hari',
                    data: <?php echo json_encode($dailyProfitArray); ?>
                },
                {
                    name: 'Total Keuntungan Kumulatif',
                    data: <?php echo json_encode($cumulativeProfitArray); ?>
                }
            ]
        };

        var line = new ApexCharts(document.querySelector("#line"), options);

        line.render();
        setTimeout(function() {
            window.print();
        }, 1000);

        var beforePrint = function() {
            line.zoomX(
                new Date({!! json_encode($categories) !!}[0]).getTime(),
                new Date({!! json_encode($categories) !!}.pop()).getTime()
            )
            line.updateOptions({
                chart: {
                    toolbar: {
                        show: false,
                    },
                }
            });
        };

        var afterPrint = function() {
            line.updateOptions({
                chart: {
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
    <div class="my-3">
        <h5 class="text-muted mb-2"><strong>Ringkasan</strong></h5>
        <hr>
        <div class="d-flex justify-content-between">
            <div style="width: 33%">
                <div style="width: 33%">
                    <table class="text-dark">
                        <tr>
                            <td><strong>Total Saldo:</strong></td>
                            <td><strong>${{ number_format((float) $journal->balances, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Jumlah Catatan:</strong></td>
                        <td><strong>{{ number_format($journal->count_of_trades, 0) }} Catatan</strong>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Persentase Keberhasilan:</strong></td>
                        <td><strong>{{ number_format((float) $journal->winrate, 2) }}%</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Total Keuntungan Sementara:</strong></td>
                        @if ((float) $journal->pnl > 0)
                            <td class="text-success">
                                <strong>${{ number_format((float) $journal->pnl, 2) }}</strong>
                            </td>
                        @elseif((float) $journal->pnl < 0)
                            <td class="text-danger">
                                <strong>-${{ abs(number_format((float) $journal->pnl, 2)) }}</strong>
                            </td>
                        @else
                            <td><strong>$0.00</strong></td>
                        @endif
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Total Margin yang Dipakai:</strong></td>
                        <td><strong>${{ number_format((float) $data['totalMargin'], 2) }}</strong>
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Rata-Rata Risk Ratio:</strong></td>
                        <td><strong>{{ number_format((float) $data['averageRR'], 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Jumlah Long / Short:</strong></td>
                        <td><strong>{{ number_format($data['long']['count'], 0) }} /
                                {{ number_format($data['short']['count'], 0) }}</strong></td>
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>P/L Long:</strong></td>
                        @if ((float) $data['long']['pnl'] > 0)
                            <td class="text-success">
                                <strong>${{ number_format((float) $data['long']['pnl'], 2) }}</strong>
                            </td>
                        @elseif((float) $data['long']['pnl'] < 0)
                            <td class="text-danger">
                                <strong>-${{ abs(number_format((float) $data['long']['pnl'], 2)) }}</strong>
                            </td>
                        @else
                            <td><strong>$0.00</strong></td>
                        @endif
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>P/L Short:</strong></td>
                        @if ((float) $data['short']['pnl'] > 0)
                            <td class="text-success">
                                <strong>${{ number_format((float) $data['short']['pnl'], 2) }}</strong>
                            </td>
                        @elseif((float) $data['short']['pnl'] < 0)
                            <td class="text-danger">
                                <strong>-${{ abs(number_format((float) $data['short']['pnl'], 2)) }}</strong>
                            </td>
                        @else
                            <td><strong>$0.00</strong></td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Rata-Rata Keuntungan per Hari:</strong></td>
                        @if ((float) $data['avgPNLPerDay'] > 0)
                            <td class="text-success">
                                <strong>${{ number_format((float) $data['avgPNLPerDay'], 2) }}</strong>
                            </td>
                        @elseif((float) $data['avgPNLPerDay'] < 0)
                            <td class="text-danger">
                                <strong>-${{ abs(number_format((float) $data['avgPNLPerDay'], 2)) }}</strong>
                            </td>
                        @else
                            <td><strong>$0.00</strong></td>
                        @endif
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Rata-Rata Keberhasilan per Hari:</strong></td>
                        @if ($user->journals->count() > 0)
                            <td><strong>{{ number_format((float) $data['avgWRPerDay'], 2) }}%</strong>
                            </td>
                        @else
                            <td><strong>0%</strong></td>
                        @endif
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Rata-Rata Durasi:</strong></td>
                        <td><strong>
                                @if ($data['avgDuration'] != null)
                                    {{ $data['avgDuration']['days'] > 0 ? $data['avgDuration']['days'] . ' hari ' : '' }}
                                    {{ $data['avgDuration']['hours'] > 0 ? $data['avgDuration']['hours'] . ' jam ' : '' }}
                                    {{ $data['avgDuration']['minutes'] > 0 ? $data['avgDuration']['minutes'] . ' menit ' : '' }}
                                    {{ $data['avgDuration']['seconds'] > 0 ? $data['avgDuration']['seconds'] . ' detik ' : '' }}@else-
                                @endif
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-5">
        <div class="d-flex justify-content-between">
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Rata-Rata Keuntungan per Trading:</strong></td>
                        @if ((float) $data['avgPNLPerTransaction'] > 0)
                            <td class="text-success">
                                <strong>${{ number_format((float) $data['avgPNLPerTransaction'], 2) }}</strong>
                            </td>
                        @elseif((float) $data['avgPNLPerTransaction'] < 0)
                            <td class="text-danger">
                                <strong>-${{ abs(number_format((float) $data['avgPNLPerTransaction'], 2)) }}</strong>
                            </td>
                        @else
                            <td><strong>$0.00</strong></td>
                        @endif
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Keuntungan Terbesar:</strong></td>
                        @if ((float) $data['pnl']['max'] > 0)
                            <td class="text-success">
                                <strong>${{ number_format((float) $data['pnl']['max'], 2) }}</strong>
                            </td>
                        @elseif((float) $data['pnl']['max'] < 0)
                            <td class="text-danger"><strong>-</strong>
                            </td>
                        @else
                            <td><strong>-</strong></td>
                        @endif
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Kerugian Terbesar:</strong></td>
                        @if ((float) $data['pnl']['min'] < 0)
                            <td class="text-danger">
                                <strong>-${{ number_format(abs((float) $data['pnl']['min']), 2) }}</strong>
                            </td>
                        @elseif((float) $data['pnl']['min'] > 0)
                            <td class="text-success"><strong>-</strong>
                            </td>
                        @else
                            <td><strong>-</strong></td>
                        @endif
                        </strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <h5 class="text-muted mb-2"><strong>Grafik Performa Keuntungan</strong></h5>
        <hr>
        <div id="line"></div>
    </div>
</x-print-layout>

@section('title', 'Laporan Metrik ' . $journal->name)
@push('scripts')
    <script>
        var options = {
            chart: {
                width: '100%',
                height: 350,
                type: 'area',
                stacked: true,
            },
            stroke: {
                curve: 'smooth',
            },
            title: {
                text: 'Performa Keuntungan'
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
    </script>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <x-back-button>{{ route('user.journal.detail', ['journal' => $journal->id]) }}</x-back-button>
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card" id="target-element">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-9">
                                    <h4 class="card-title">Laporan Metrik {{ $journal->name }}</h4>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <a href="{{route('user.journal.detail.metric.print', ['journal' => $journal->id])}}" target="_blank" class="btn btn-primary w-100" id="export-pdf">Cetak
                                        Laporan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Total Saldo:</strong></td>
                                            <td><strong>${{ number_format((float) $journal->balances, 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Jumlah Catatan:</strong></td>
                                            <td><strong>{{ number_format($journal->count_of_trades, 0) }} Catatan</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Persentase Keberhasilan:</strong></td>
                                            <td><strong>{{ number_format((float) $journal->winrate, 2) }}%</strong></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-4">
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
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Total Margin yang Dipakai:</strong></td>
                                            <td><strong>${{ number_format((float) $data['totalMargin'], 2) }}</strong>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Rata-Rata Risk Ratio:</strong></td>
                                            <td><strong>{{ number_format((float) $data['averageRR'], 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Jumlah Long / Short:</strong></td>
                                            <td><strong>{{ number_format($data['long']['count'], 0) }} /
                                                    {{ number_format($data['short']['count'], 0) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4">
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
                                <div class="col-sm-12 col-md-4">
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
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-4">
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
                                <div class="col-sm-12 col-md-4">
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
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Rata-Rata Durasi:</strong></td>
                                            <td><strong>
                                                @if($data['avgDuration'] != null){{ $data['avgDuration']['days'] > 0 ? $data['avgDuration']['days'] . ' hari ' : '' }}
                                                    {{ $data['avgDuration']['hours'] > 0 ? $data['avgDuration']['hours'] . ' jam ' : '' }}
                                                    {{ $data['avgDuration']['minutes'] > 0 ? $data['avgDuration']['minutes'] . ' menit ' : '' }}
                                                    {{ $data['avgDuration']['seconds'] > 0 ? $data['avgDuration']['seconds'] . ' detik ' : '' }}@else-@endif</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-4">
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
                                <div class="col-sm-12 col-md-4">
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
                                <div class="col-sm-12 col-md-4">
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
                            <div id="line" class="w-100 mb-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

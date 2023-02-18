@section('report-title')
    Laporan Riwayat {{ $journal->name }}
@endsection
@push('scripts')
    <script>
        var css = '@page { size: landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        setTimeout(function() {
            window.print();
        }, 1000);
    </script>
@endpush
@push('styles')
    <style>

        @media print {
            .table-responsive {
                overflow: visible;
            }

            table {
                overflow: visible;
            }

            #selesai-table {
                font-size: 0.4em;
            }

            table{
                font-size: 0.8em;
            }

            small{
                font-size: 0.6em;
            }

            #selesai-table  svg{
                margin-top: 5%;
                width: 15%;
                height: 15%;
            }

            #selesai-table td, #selesai-table th{
                padding: 8px 8px !important;
            }

            img {
                width: 25px;
            }

            html,
            body {
                height: 99%;
            }


        }
    </style>
@endpush
<x-print-layout>
    <div class="d-flex justify-content-between text-dark my-3">
        <div>
            <small>Resiko per Transaksi:
                {{ (float) $journal->risk }}%</small>
        </div>
        <div>
            <small>Tanggal: @if($data['start'] != null and $data['end'] != null)
                {{ date('d F Y', strtotime($data['start'])) }} - {{ date('d F Y', strtotime($data['end'])) }}
                @elseif($data['start'] != null)
                {{ date('d F Y', strtotime($data['start'])) }} - saat ini
                @elseif($data['end'] != null)
                awal - {{ date('d F Y', strtotime($data['end'])) }}
                @else

                @endif</small>
        </div>
    </div>
    <div class="mb-3">
        <div class="h5 text-muted mb-2"><strong>Daftar Transaksi</strong></div>
        <hr>
        <div class="table-responsive">
            <table id="selesai-table" class="table table-striped table-hover" role="grid" data-toggle="data-table">
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
                    </tr>
                </thead>
                <tbody id="list-selesai">
                    @forelse ($trades->sortByDesc('close_time') as $trade)
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
                            <td>{{ abs((float) $trade->transactions()->where('type', 1)->sum('quantity')) > 999? number_format(abs((float) $trade->transactions()->where('type', 1)->sum('quantity')),0): abs((float) $trade->transactions()->where('type', 1)->sum('quantity')) }}
                            </td>
                            <td>{{ $trade->leverage }}</td>
                            <td>${{ abs($trade->transactions()->where('type', 0)->sum('total')) /$trade->leverage >999? number_format(abs($trade->transactions()->where('type', 0)->sum('total')) / $trade->leverage,0): abs($trade->transactions()->where('type', 0)->sum('total')) / $trade->leverage }}
                            </td>
                            <td>${{ $trade->open_price > 999 ? number_format($trade->open_price, 0) : (float) $trade->open_price }}
                            </td>
                            <td>{{ $trade->open_time }}</td>
                            <td>${{ $trade->close_price > 999 ? number_format($trade->close_price, 0) : (float) $trade->close_price }}
                            </td>
                            <td>{{ $trade->close_time }}</td>
                            <td>{{ $trade->diff_days > 0 ? $trade->diff_days . ' hari ' : '' }}
                                {{ $trade->diff_hours > 0 ? $trade->diff_hours . ' jam ' : '' }}
                                {{ $trade->diff_minutes > 0 ? $trade->diff_minutes . ' menit ' : '' }}
                                {{ $trade->diff_seconds > 0 ? $trade->diff_seconds . ' detik ' : '' }}
                            </td>
                            @if ($trade->nett_pnl > 0)
                                <td class="text-success">
                                    ${{ $trade->nett_pnl > 999 ? number_format((float) $trade->nett_pnl, 2) : (float) $trade->nett_pnl }}
                                </td>
                                <td class="text-success">
                                    {{ number_format((float) $trade->roe, 2) }}%
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-success ">Win
                                        (Menang)
                                    </span>
                                </td>
                            @else
                                <td class="text-danger">
                                    -${{ abs((float) $trade->nett_pnl) > 999 ? number_format(abs((float) $trade->nett_pnl), 2) : abs((float) $trade->nett_pnl) }}
                                    @if ($journal->risk > 0 and abs((float) $trade->nett_pnl) > ($journal->balances * $journal->risk) / 100)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-exclamation-triangle-fill mb-1"
                                            viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Resiko diatas {{ $journal->risk }}%">
                                            <path
                                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="text-danger">
                                    -{{ number_format(abs((float) $trade->roe), 2) }}%
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-danger ">Loss
                                        (Kalah)</span>
                                </td>
                            @endif
                            <td class="@if ($trade->real_rr >= 2) text-success @else text-danger @endif">
                                {{ $trade->real_rr }}</td>
                            <td>{{ $trade->closed_at == '' ? '-' : $trade->closed_at }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="16">Data tidak tersedia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <h5 class="text-muted mb-2"><strong>Kesimpulan</strong></h5>
    <hr>
    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Jumlah Catatan:</strong></td>
                        <td><strong>{{ number_format($trades->count(), 0) }} Catatan</strong>                        </td>
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Total Keuntungan Sementara:</strong></td>
                        @if ((float) $trades->sum('pnl') > 0)
                            <td class="text-success">
                                <strong>${{ number_format((float) $trades->sum('pnl'), 2) }}</strong>
                            </td>
                        @elseif((float) $trades->sum('pnl') < 0)
                            <td class="text-danger">
                                <strong>-${{ abs(number_format((float) $trades->sum('pnl'), 2)) }}</strong>
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
                        <td><strong>Persentase Keberhasilan:</strong></td>
                        <td><strong>{{ number_format((float) $data['winRate'], 2) }}%</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between">
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
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Persentase Kepatuhan:</strong></td>
                        <td><strong>{{ number_format((float) $data['complianceRate'], 2) }}%</strong></td>
                    </tr>
                </table>
            </div>
        </div>
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
                        <td><strong>Target Tercapai Terbanyak:</strong></td>
                        <td><strong>
                            @if($data['mostAchievedTarget'] != null)
                                @if(str_contains($data['mostAchievedTarget']['target'], 'SL'))
                                <span class="text-danger">{{ $data['mostAchievedTarget']['target'] }} ({{$data['mostAchievedTarget']['percentage']}}%)</span>
                                @elseif (str_contains($data['mostAchievedTarget']['target'], 'TP'))
                                <span class="text-success">{{ $data['mostAchievedTarget']['target'] }} ({{$data['mostAchievedTarget']['percentage']}}%)</span>
                                @else-
                                @endif
                            @else-
                            @endif
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div style="width: 33%">
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
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Durasi Tercepat:</strong></td>
                        <td><strong>
                        @if($data['duration']['min'] != null){{ $data['duration']['min']['diff_days'] > 0 ? $data['duration']['min']['diff_days'] . ' hari ' : '' }}
                        {{ $data['duration']['min']['diff_hours'] > 0 ? $data['duration']['min']['diff_hours'] . ' jam ' : '' }}
                        {{ $data['duration']['min']['diff_minutes'] > 0 ? $data['duration']['min']['diff_minutes'] . ' menit ' : '' }}
                        {{ $data['duration']['min']['diff_seconds'] > 0 ? $data['duration']['min']['diff_seconds'] . ' detik ' : '' }}@else-@endif</strong>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="width: 33%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Durasi Terlama:</strong></td>
                        <td><strong>
                            @if($data['duration']['max'] != null){{ $data['duration']['max']['diff_days'] > 0 ? $data['duration']['max']['diff_days'] . ' hari ' : '' }}
                            {{ $data['duration']['max']['diff_hours'] > 0 ? $data['duration']['max']['diff_hours'] . ' jam ' : '' }}
                            {{ $data['duration']['max']['diff_minutes'] > 0 ? $data['duration']['max']['diff_minutes'] . ' menit ' : '' }}
                            {{ $data['duration']['max']['diff_seconds'] > 0 ? $data['duration']['max']['diff_seconds'] . ' detik ' : '' }}@else-@endif</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
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
</x-print-layout>

@section('title', 'Laporan Trading Berdasarkan Timeframe')
<x-app-layout :options="['loading', 'datatable']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card" id="target-element">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-9">
                                    <h4 class="card-title">Laporan Trading Berdasarkan Timeframe</h4>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <a href="{{route('user.journal.timeframe.print')}}" target="_blank" class="btn btn-primary w-100" id="export-pdf">Cetak
                                        Laporan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="timeframes-list-table" class="table table-striped table-hover" role="grid" data-toggle="data-table">
                                    <thead>
                                        <tr class="light">
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Jumlah Catatan</th>
                                            <th>W/L (%)</th>
                                            <th>P/L ($)</th>
                                            <th>Rata2 P/L ($)</th>
                                            <th>Rata2 Durasi</th>
                                            <th>Durasi Tercepat</th>
                                            <th>Durasi Terlama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration}}</td>
                                                <td>{{ $item['nama'] }}</td>
                                                <td>{{ number_format($item['jumlah_trades'], 0) }} Catatan</td>
                                                <td>{{ number_format((float) $item['winning_trades'] / $item['jumlah_trades'] * 100, 2) }}%</td>
                                                @if ($item['profit'] > 0)
                                                    <td class="text-success">
                                                        ${{ $item['profit'] > 999 ? number_format((float) $item['profit'], 2) : (float) $item['profit'] }}
                                                    </td>
                                                @else
                                                    <td class="text-danger">
                                                        -${{ abs((float) $item['profit']) > 999 ? number_format(abs((float) $item['profit']), 2) : abs((float) $item['profit']) }}
                                                    </td>
                                                @endif
                                                @if ($item['rata_rata_profit'] > 0)
                                                    <td class="text-success">
                                                        ${{ $item['rata_rata_profit'] > 999 ? number_format((float) $item['rata_rata_profit'], 2) : (float) $item['rata_rata_profit'] }}
                                                    </td>
                                                @else
                                                    <td class="text-danger">
                                                        -${{ abs((float) $item['rata_rata_profit']) > 999 ? number_format(abs((float) $item['rata_rata_profit']), 2) : abs((float) $item['rata_rata_profit']) }}
                                                    </td>
                                                @endif
                                                <td>
                                                    @if($item['rata_rata_durasi'] != null){{ $item['rata_rata_durasi']['days'] > 0 ? $item['rata_rata_durasi']['days'] . ' hari ' : '' }}
                                                    {{ $item['rata_rata_durasi']['hours'] > 0 ? $item['rata_rata_durasi']['hours'] . ' jam ' : '' }}
                                                    {{ $item['rata_rata_durasi']['minutes'] > 0 ? $item['rata_rata_durasi']['minutes'] . ' menit ' : '' }}
                                                    {{ $item['rata_rata_durasi']['seconds'] > 0 ? $item['rata_rata_durasi']['seconds'] . ' detik ' : '' }}@else-@endif
                                                </td>
                                                <td>
                                                    @if($item['durasi_tercepat'] != null){{ $item['durasi_tercepat']['days'] > 0 ? $item['durasi_tercepat']['days'] . ' hari ' : '' }}
                                                    {{ $item['durasi_tercepat']['hours'] > 0 ? $item['durasi_tercepat']['hours'] . ' jam ' : '' }}
                                                    {{ $item['durasi_tercepat']['minutes'] > 0 ? $item['durasi_tercepat']['minutes'] . ' menit ' : '' }}
                                                    {{ $item['durasi_tercepat']['seconds'] > 0 ? $item['durasi_tercepat']['seconds'] . ' detik ' : '' }}@else-@endif
                                                </td>
                                                <td>
                                                    @if($item['durasi_terlama'] != null){{ $item['durasi_terlama']['days'] > 0 ? $item['durasi_terlama']['days'] . ' hari ' : '' }}
                                                    {{ $item['durasi_terlama']['hours'] > 0 ? $item['durasi_terlama']['hours'] . ' jam ' : '' }}
                                                    {{ $item['durasi_terlama']['minutes'] > 0 ? $item['durasi_terlama']['minutes'] . ' menit ' : '' }}
                                                    {{ $item['durasi_terlama']['seconds'] > 0 ? $item['durasi_terlama']['seconds'] . ' detik ' : '' }}@else-@endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9">Data tidak tersedia</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

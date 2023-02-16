@section('title', 'Laporan Trading Berdasarkan Koin')
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
                                    <h4 class="card-title">Laporan Trading Berdasarkan Koin</h4>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <a href="{{route('user.journal.coin.print')}}" target="_blank" class="btn btn-primary w-100" id="export-pdf">Cetak
                                        Laporan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="coins-list-table" class="table table-striped table-hover" role="grid" data-toggle="data-table">
                                    <thead>
                                        <tr class="light">
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Jumlah Catatan</th>
                                            <th>W/L (%)</th>
                                            <th>P/L ($)</th>
                                            <th>Rata2 P/L ($)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    <img src="{{ $item->thumb }}" alt="coin">
                                                    {{ $item->name }}
                                                </td>
                                                <td>{{ number_format($item->jumlah_trades, 0) }} Catatan</td>
                                                <td>{{ number_format((float) $item->win_loss_percent, 2) }}%</td>
                                                @if ($item->total_profit > 0)
                                                    <td class="text-success">
                                                        ${{ $item->total_profit > 999 ? number_format((float) $item->total_profit, 2) : (float) $item->total_profit }}
                                                    </td>
                                                @else
                                                    <td class="text-danger">
                                                        -${{ abs((float) $item->total_profit) > 999 ? number_format(abs((float) $item->total_profit), 2) : abs((float) $item->total_profit) }}
                                                    </td>
                                                @endif
                                                @if ($item->avg_profit > 0)
                                                    <td class="text-success">
                                                        ${{ $item->avg_profit > 999 ? number_format((float) $item->avg_profit, 2) : (float) $item->avg_profit }}
                                                    </td>
                                                @else
                                                    <td class="text-danger">
                                                        -${{ abs((float) $item->avg_profit) > 999 ? number_format(abs((float) $item->avg_profit), 2) : abs((float) $item->avg_profit) }}
                                                    </td>
                                                @endif
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
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

@section('title', 'Laporan Aset')
@push('scripts')
    <script>
        var options = {
            chart: {
                type: 'line',
                width: '100%',
                height: 350,
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
    </script>
@endpush
<x-app-layout :options="['loading', 'datatable']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <x-back-button>{{ route('user.wallet.asset.detail', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}
        </x-back-button>
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card" id="target-element">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-9 d-inline-flex align-items-center">
                                    <img src="{{ $asset->thumb }}" alt="logo_crypto" class="img-thumbnail">
                                    <h4 class="card-title ms-3 mt-2">
                                        {{ $asset->name }}
                                    </h4>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <a href="{{route('user.wallet.asset.report.print', ['wallet' => $wallet->id, 'asset' => $asset->id])}}" target="_blank" class="btn btn-primary w-100"
                                        id="export-pdf">Cetak
                                        Laporan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-4">
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
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Harga Rata-Rata:</strong></td>
                                            <td><strong>$@if ($asset_wallet->average_price > 999){{ number_format((float) $asset_wallet->average_price, 2) }}
                                            @else{{ (float) $asset_wallet->average_price }}
                                            @endif</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Total Keuntungan:</strong></td>
                                            <td><strong><span
                                                        class="@if ($asset_wallet->pnl > 0) text-success @elseif($asset_wallet->pnl < 0) text-danger @endif">
                                                        @if ($asset_wallet->pnl < 0)-@endif${{ number_format(abs((float) $asset_wallet->pnl), 2, '.', ',') }}({{ (float) $asset_wallet->pnl_percentage }}%)
                                                    </span></strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="line" class="w-100 mb-3"></div>
                            <div class="table-responsive">
                                <table id="asset-transaction-list-table" class="table table-striped table-hover"
                                    role="grid" data-toggle="data-table">
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
                                        @php
                                            $cumulativeAmount = $asset_wallet->amount;
                                            $cumulativeTotal = $asset_wallet->total;
                                            // $cumulativeAmount = 0;
                                            // $cumulativeTotal = 0;
                                        @endphp
                                        @forelse($asset_wallet->transactions->sortByDesc('time') as $transaction)
                                            @php
                                                // $cumulativeAmount += $transaction->amount;
                                                // $cumulativeTotal += $transaction->total;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $transaction->time }}
                                                </td>
                                                <td>
                                                    @if($transaction->type == 0)
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
                                                    @if($transaction->type == 0 or $transaction->type == 1)
                                                    $@if($transaction->price > 1){{number_format((float) $transaction->price, 2, '.', ',') }}
                                                    @elseif(strlen(substr(strrchr($transaction->price, "."), 1)) > 8){{ number_format((float) $transaction->price, 8, '.', ',') }}
                                                    @else{{ (float) $transaction->price }}
                                                    @endif
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>@if($transaction->type == 0 or $transaction->type == 3)+@if($transaction->amount > 1){{number_format((float) $transaction->amount, 2, '.', ',') }}
                                                    @elseif(strlen(substr(strrchr($transaction->amount, "."), 1)) > 8){{ number_format((float) $transaction->amount, 8, '.', ',') }}
                                                    @else{{ (float) $transaction->amount }}
                                                    @endif{{$transaction->asset_wallet->asset->symbol}}<br>
                                                    @if($transaction->type == 0)
                                                    +@if($transaction->total > 1){{number_format((float) $transaction->total, 2, '.', ',') }}
                                                    @elseif(strlen(substr(strrchr($transaction->total, "."), 1)) > 8){{ number_format((float) $transaction->total, 8, '.', ',') }}
                                                    @else{{ (float) $transaction->total }}
                                                    @endif
                                                    $
                                                    @elseif($transaction->type == 3)-@endif
                                                @elseif($transaction->type == 1 or $transaction->type == 2)-@if(abs($transaction->amount) > 1){{number_format((float) abs($transaction->amount), 2, '.', ',') }}
                                                @elseif(strlen(substr(strrchr(abs($transaction->amount), "."), 1)) > 8){{ number_format((float) abs($transaction->amount), 8, '.', ',') }}
                                                @else{{ (float) abs($transaction->amount) }}
                                                @endif{{$transaction->asset_wallet->asset->symbol}}<br>
                                                @if($transaction->type == 1)
                                                -@if(abs($transaction->total) > 1){{number_format((float) abs($transaction->total), 2, '.', ',') }}
                                                @elseif(strlen(substr(strrchr(abs($transaction->total), "."), 1)) > 8){{ number_format((float) abs($transaction->total), 8, '.', ',') }}
                                                @else{{ (float) abs($transaction->total) }}
                                                @endif
                                                $
                                                @elseif($transaction->type == 2)-@endif
                                                @endif</td>
                                                <td>
                                                    @if($transaction->fee > 1)${{number_format((float) $transaction->fee, 2, '.', ',') }}
                                                    @elseif(strlen(substr(strrchr($transaction->fee, "."), 1)) > 8)${{ number_format((float) $transaction->fee, 8, '.', ',') }}
                                                    @elseif($transaction->fee == 0)-
                                                    @else${{ (float) $transaction->fee }}
                                                    @endif
                                                </td>
                                                {{-- <td>{{number_format($cumulativeAmount, 0)}}</td>
                                                <td>{{number_format($cumulativeTotal, 0)}}</td> --}}
                                            </tr>
                                            @php
                                                // $cumulativeAmount -= $transaction->amount;
                                                // if($cumulativeAmount > 0){
                                                //     $cumulativeTotal -= $transaction->total;
                                                // }
                                                // else{
                                                //     $cumulativeTotal = 0;
                                                // }
                                            @endphp
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

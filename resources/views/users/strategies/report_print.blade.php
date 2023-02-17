@section('report-title')
    Laporan Trading Berdasarkan Pustaka
@endsection
@push('scripts')
    <script>
        var seriesBarTerbaik = <?php echo json_encode($seriesBarTerbaik); ?>;
        var labelsBarTerbaik = <?php echo json_encode($labelsBarTerbaik); ?>;
        var seriesBarTerburuk = <?php echo json_encode($seriesBarTerburuk); ?>;
        var labelsBarTerburuk = <?php echo json_encode($labelsBarTerburuk); ?>;
        var optionsBarTerbaik = {
            series: [{
                nama: 'PNL',
                data: seriesBarTerbaik
            }],
            chart: {
                type: 'bar',
                width: '45%',
                redrawOnWindowResize: true,
                redrawOnParentResize: true,
                toolbar: {
                    show: false,
                },
            },
            colors: ['#3a57e8', '#f16a1b', '#6f42c1'],
            dataLabels: {
                formatter: function(val, opt) {
                    if (parseInt(val) >= 1000) {
                        return val.toString().replace(
                            /\B(?=(\d{3})+(?!\d))/g, ",");
                    } else {
                        return val;
                    }
                },
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    distributed: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                    columnWidth: '50%',
                }
            },
            xaxis: {
                categories: labelsBarTerbaik,
                labels: {
                    show: false,
                }
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: true
                },
                y: {
                    title: {
                        formatter: function() {
                            return ''
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
            }
        };

        var barTerbaik = new ApexCharts(document.querySelector("#barTerbaik"), optionsBarTerbaik);
        barTerbaik.render();

        var optionsBarTerburuk = {
            series: [{
                nama: 'PNL',
                data: seriesBarTerburuk
            }],
            chart: {
                type: 'bar',
                width: '45%',
                redrawOnWindowResize: true,
                redrawOnParentResize: true,
                toolbar: {
                    show: false,
                },
            },
            colors: ['#3a57e8', '#f16a1b', '#6f42c1'],
            dataLabels: {
                formatter: function(val, opt) {
                    if (parseInt(val) >= 1000) {
                        return val.toString().replace(
                            /\B(?=(\d{3})+(?!\d))/g, ",");
                    } else {
                        return val;
                    }
                },
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    distributed: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                    columnWidth: '50%',
                }
            },
            xaxis: {
                categories: labelsBarTerburuk,
                labels: {
                    show: false,
                }
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: true
                },
                y: {
                    title: {
                        formatter: function() {
                            return ''
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
            }
        };

        var barTerburuk = new ApexCharts(document.querySelector("#barTerburuk"), optionsBarTerburuk);
        barTerburuk.render();

        $(function() {
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

            var beforePrint = function() {};


            var afterPrint = function() {};

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

            setTimeout(function() {
                window.print();
            }, 1000);

        });
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
                font-size: 0.4em;
            }

            th,
            td {
                padding: 6px !important;
            }

            html,
            body {
                height: 99%;
            }
        }
    </style>
@endpush
<x-print-layout>
    <div class="d-flex justify-content-between my-3">
        <div class="w-50">
            <h5 class="text-muted mb-2"><strong>3 Pustaka Performa Terbaik</strong></h5>
            <hr>
            <div id="barTerbaik"></div>
        </div>
        <div class="w-50">
            <h5 class="text-muted mb-2"><strong>3 Pustaka Performa Terburuk</strong></h5>
            <hr>
            <div id="barTerburuk"></div>
        </div>
    </div>
    @if ($data->where('category_name', 'Strategi Entri')->count() > 0)
        <div class="mb-3">
            <div class="h5 text-muted mb-2"><strong>Strategi Entri</strong></div>
            <hr>
            <div class="table-responsive">
                <table id="entry-strategies-list-table" class="table table-striped table-hover" role="grid"
                    data-toggle="data-table">
                    <thead>
                        <tr class="light">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Jumlah Catatan</th>
                            <th>W/L (%)</th>
                            <th>P/L ($)</th>
                            <th>Rata2 P/L ($)</th>
                            <th>P/L Terbesar($)</th>
                            <th>P/L Terkecil($)</th>
                            <th>Rata2 Durasi</th>
                            <th>Durasi Tercepat</th>
                            <th>Durasi Terlama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->where('category_name', 'Strategi Entri')->sortBy([['jumlah_trades', 'desc'],[ 'win_loss_percent', 'desc']]) as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->strategy_name }}</td>
                                <td>{{ number_format($item->jumlah_trades, 0) }} Catatan</td>
                                <td>{{ number_format((float) $item->win_loss_percent, 2) }}%
                                </td>
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
                                @if ($item->max_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->max_profit > 999 ? number_format((float) $item->max_profit, 2) : (float) $item->max_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->max_profit) > 999 ? number_format(abs((float) $item->max_profit), 2) : abs((float) $item->max_profit) }}
                                    </td>
                                @endif
                                @if ($item->min_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->min_profit > 999 ? number_format((float) $item->min_profit, 2) : (float) $item->min_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->min_profit) > 999 ? number_format(abs((float) $item->min_profit), 2) : abs((float) $item->min_profit) }}
                                    </td>
                                @endif
                                <td>
                                    @php
                                        $averageDuration = [
                                            'days' => floor($item->avg_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->avg_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->avg_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->avg_duration % 60),
                                        ];
                                    @endphp
                                    @if ($averageDuration != null)
                                        {{ $averageDuration['days'] > 0 ? $averageDuration['days'] . ' hari ' : '' }}
                                        {{ $averageDuration['hours'] > 0 ? $averageDuration['hours'] . ' jam ' : '' }}
                                        {{ $averageDuration['minutes'] > 0 ? $averageDuration['minutes'] . ' menit ' : '' }}
                                        {{ $averageDuration['seconds'] > 0 ? $averageDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $minDuration = [
                                            'days' => floor($item->min_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->min_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->min_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->min_duration % 60),
                                        ];
                                    @endphp
                                    @if ($minDuration != null)
                                        {{ $minDuration['days'] > 0 ? $minDuration['days'] . ' hari ' : '' }}
                                        {{ $minDuration['hours'] > 0 ? $minDuration['hours'] . ' jam ' : '' }}
                                        {{ $minDuration['minutes'] > 0 ? $minDuration['minutes'] . ' menit ' : '' }}
                                        {{ $minDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $maxDuration = [
                                            'days' => floor($item->max_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->max_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->max_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->max_duration % 60),
                                        ];
                                    @endphp
                                    @if ($maxDuration != null)
                                        {{ $maxDuration['days'] > 0 ? $maxDuration['days'] . ' hari ' : '' }}
                                        {{ $maxDuration['hours'] > 0 ? $maxDuration['hours'] . ' jam ' : '' }}
                                        {{ $maxDuration['minutes'] > 0 ? $maxDuration['minutes'] . ' menit ' : '' }}
                                        {{ $maxDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    @if (
        $data->filter(function ($item) {
                return strstr($item->category_name, 'Pola');
            })->count() > 0)
        <div class="mb-3">
            <div class="h5 text-muted mb-2"><strong>Pola</strong></div>
            <hr>
            <div class="table-responsive">
                <table id="patterns-list-table" class="table table-striped table-hover" role="grid"
                    data-toggle="data-table">
                    <thead>
                        <tr class="light">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Jumlah Catatan</th>
                            <th>W/L (%)</th>
                            <th>P/L ($)</th>
                            <th>Rata2 P/L ($)</th>
                            <th>P/L Terbesar($)</th>
                            <th>P/L Terkecil($)</th>
                            <th>Rata2 Durasi</th>
                            <th>Durasi Tercepat</th>
                            <th>Durasi Terlama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->sortBy([['jumlah_trades', 'desc'],[ 'win_loss_percent', 'desc']])->filter(function($item){
                        return strstr($item->category_name, 'Pola');
                    }) as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->strategy_name }}</td>
                                <td>{{ number_format($item->jumlah_trades, 0) }} Catatan</td>
                                <td>{{ number_format((float) $item->win_loss_percent, 2) }}%
                                </td>
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
                                @if ($item->max_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->max_profit > 999 ? number_format((float) $item->max_profit, 2) : (float) $item->max_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->max_profit) > 999 ? number_format(abs((float) $item->max_profit), 2) : abs((float) $item->max_profit) }}
                                    </td>
                                @endif
                                @if ($item->min_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->min_profit > 999 ? number_format((float) $item->min_profit, 2) : (float) $item->min_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->min_profit) > 999 ? number_format(abs((float) $item->min_profit), 2) : abs((float) $item->min_profit) }}
                                    </td>
                                @endif
                                <td>
                                    @php
                                        $averageDuration = [
                                            'days' => floor($item->avg_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->avg_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->avg_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->avg_duration % 60),
                                        ];
                                    @endphp
                                    @if ($averageDuration != null)
                                        {{ $averageDuration['days'] > 0 ? $averageDuration['days'] . ' hari ' : '' }}
                                        {{ $averageDuration['hours'] > 0 ? $averageDuration['hours'] . ' jam ' : '' }}
                                        {{ $averageDuration['minutes'] > 0 ? $averageDuration['minutes'] . ' menit ' : '' }}
                                        {{ $averageDuration['seconds'] > 0 ? $averageDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $minDuration = [
                                            'days' => floor($item->min_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->min_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->min_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->min_duration % 60),
                                        ];
                                    @endphp
                                    @if ($minDuration != null)
                                        {{ $minDuration['days'] > 0 ? $minDuration['days'] . ' hari ' : '' }}
                                        {{ $minDuration['hours'] > 0 ? $minDuration['hours'] . ' jam ' : '' }}
                                        {{ $minDuration['minutes'] > 0 ? $minDuration['minutes'] . ' menit ' : '' }}
                                        {{ $minDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $maxDuration = [
                                            'days' => floor($item->max_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->max_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->max_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->max_duration % 60),
                                        ];
                                    @endphp
                                    @if ($maxDuration != null)
                                        {{ $maxDuration['days'] > 0 ? $maxDuration['days'] . ' hari ' : '' }}
                                        {{ $maxDuration['hours'] > 0 ? $maxDuration['hours'] . ' jam ' : '' }}
                                        {{ $maxDuration['minutes'] > 0 ? $maxDuration['minutes'] . ' menit ' : '' }}
                                        {{ $maxDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    @if ($data->where('category_name', 'Indikator')->count() > 0)
        <div class="mb-3">
            <div class="h5 text-muted mb-2"><strong>Indikator</strong></div>
            <hr>
            <div class="table-responsive">
                <table id="indicators-list-table" class="table table-striped table-hover" role="grid"
                    data-toggle="data-table">
                    <thead>
                        <tr class="light">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Jumlah Catatan</th>
                            <th>W/L (%)</th>
                            <th>P/L ($)</th>
                            <th>Rata2 P/L ($)</th>
                            <th>P/L Terbesar($)</th>
                            <th>P/L Terkecil($)</th>
                            <th>Rata2 Durasi</th>
                            <th>Durasi Tercepat</th>
                            <th>Durasi Terlama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->where('category_name', 'Indikator')->sortBy([['jumlah_trades', 'desc'],[ 'win_loss_percent', 'desc']]) as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->strategy_name }}</td>
                                <td>{{ number_format($item->jumlah_trades, 0) }} Catatan</td>
                                <td>{{ number_format((float) $item->win_loss_percent, 2) }}%
                                </td>
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
                                @if ($item->max_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->max_profit > 999 ? number_format((float) $item->max_profit, 2) : (float) $item->max_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->max_profit) > 999 ? number_format(abs((float) $item->max_profit), 2) : abs((float) $item->max_profit) }}
                                    </td>
                                @endif
                                @if ($item->min_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->min_profit > 999 ? number_format((float) $item->min_profit, 2) : (float) $item->min_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->min_profit) > 999 ? number_format(abs((float) $item->min_profit), 2) : abs((float) $item->min_profit) }}
                                    </td>
                                @endif
                                <td>
                                    @php
                                        $averageDuration = [
                                            'days' => floor($item->avg_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->avg_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->avg_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->avg_duration % 60),
                                        ];
                                    @endphp
                                    @if ($averageDuration != null)
                                        {{ $averageDuration['days'] > 0 ? $averageDuration['days'] . ' hari ' : '' }}
                                        {{ $averageDuration['hours'] > 0 ? $averageDuration['hours'] . ' jam ' : '' }}
                                        {{ $averageDuration['minutes'] > 0 ? $averageDuration['minutes'] . ' menit ' : '' }}
                                        {{ $averageDuration['seconds'] > 0 ? $averageDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $minDuration = [
                                            'days' => floor($item->min_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->min_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->min_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->min_duration % 60),
                                        ];
                                    @endphp
                                    @if ($minDuration != null)
                                        {{ $minDuration['days'] > 0 ? $minDuration['days'] . ' hari ' : '' }}
                                        {{ $minDuration['hours'] > 0 ? $minDuration['hours'] . ' jam ' : '' }}
                                        {{ $minDuration['minutes'] > 0 ? $minDuration['minutes'] . ' menit ' : '' }}
                                        {{ $minDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $maxDuration = [
                                            'days' => floor($item->max_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->max_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->max_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->max_duration % 60),
                                        ];
                                    @endphp
                                    @if ($maxDuration != null)
                                        {{ $maxDuration['days'] > 0 ? $maxDuration['days'] . ' hari ' : '' }}
                                        {{ $maxDuration['hours'] > 0 ? $maxDuration['hours'] . ' jam ' : '' }}
                                        {{ $maxDuration['minutes'] > 0 ? $maxDuration['minutes'] . ' menit ' : '' }}
                                        {{ $maxDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    @if ($data->where('strategy_author', Auth::id())->count() > 0)
        <div class="mb-3">
            <div class="h5 text-muted mb-2"><strong>Strategi Pribadi</strong></div>
            <hr>
            <div class="table-responsive">
                <table id="private-strategies-list-table" class="table table-striped table-hover" role="grid"
                    data-toggle="data-table">
                    <thead>
                        <tr class="light">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Jumlah Catatan</th>
                            <th>W/L (%)</th>
                            <th>P/L ($)</th>
                            <th>Rata2 P/L ($)</th>
                            <th>P/L Terbesar($)</th>
                            <th>P/L Terkecil($)</th>
                            <th>Rata2 Durasi</th>
                            <th>Durasi Tercepat</th>
                            <th>Durasi Terlama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->where('strategy_author', Auth::id())->sortBy([['jumlah_trades', 'desc'],[ 'win_loss_percent', 'desc']]) as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->strategy_name }}</td>
                                <td>{{ number_format($item->jumlah_trades, 0) }} Catatan</td>
                                <td>{{ number_format((float) $item->win_loss_percent, 2) }}%
                                </td>
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
                                @if ($item->max_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->max_profit > 999 ? number_format((float) $item->max_profit, 2) : (float) $item->max_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->max_profit) > 999 ? number_format(abs((float) $item->max_profit), 2) : abs((float) $item->max_profit) }}
                                    </td>
                                @endif
                                @if ($item->min_profit > 0)
                                    <td class="text-success">
                                        ${{ $item->min_profit > 999 ? number_format((float) $item->min_profit, 2) : (float) $item->min_profit }}
                                    </td>
                                @else
                                    <td class="text-danger">
                                        -${{ abs((float) $item->min_profit) > 999 ? number_format(abs((float) $item->min_profit), 2) : abs((float) $item->min_profit) }}
                                    </td>
                                @endif
                                <td>
                                    @php
                                        $averageDuration = [
                                            'days' => floor($item->avg_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->avg_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->avg_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->avg_duration % 60),
                                        ];
                                    @endphp
                                    @if ($averageDuration != null)
                                        {{ $averageDuration['days'] > 0 ? $averageDuration['days'] . ' hari ' : '' }}
                                        {{ $averageDuration['hours'] > 0 ? $averageDuration['hours'] . ' jam ' : '' }}
                                        {{ $averageDuration['minutes'] > 0 ? $averageDuration['minutes'] . ' menit ' : '' }}
                                        {{ $averageDuration['seconds'] > 0 ? $averageDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $minDuration = [
                                            'days' => floor($item->min_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->min_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->min_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->min_duration % 60),
                                        ];
                                    @endphp
                                    @if ($minDuration != null)
                                        {{ $minDuration['days'] > 0 ? $minDuration['days'] . ' hari ' : '' }}
                                        {{ $minDuration['hours'] > 0 ? $minDuration['hours'] . ' jam ' : '' }}
                                        {{ $minDuration['minutes'] > 0 ? $minDuration['minutes'] . ' menit ' : '' }}
                                        {{ $minDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $maxDuration = [
                                            'days' => floor($item->max_duration / (24 * 60 * 60)),
                                            'hours' => floor(($item->max_duration % (24 * 60 * 60)) / (60 * 60)),
                                            'minutes' => floor(($item->max_duration % (60 * 60)) / 60),
                                            'seconds' => floor($item->max_duration % 60),
                                        ];
                                    @endphp
                                    @if ($maxDuration != null)
                                        {{ $maxDuration['days'] > 0 ? $maxDuration['days'] . ' hari ' : '' }}
                                        {{ $maxDuration['hours'] > 0 ? $maxDuration['hours'] . ' jam ' : '' }}
                                        {{ $maxDuration['minutes'] > 0 ? $maxDuration['minutes'] . ' menit ' : '' }}
                                        {{ $maxDuration['seconds'] > 0 ? $maxDuration['seconds'] . ' detik ' : '' }}@else-
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-print-layout>

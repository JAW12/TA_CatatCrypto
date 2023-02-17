@section('report-title')
    Laporan Trading Berdasarkan Timeframe
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
                width: '50%',
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
                categories: labelsBarTerbaik
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
                position: 'right',
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
                width: '50%',
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
                categories: labelsBarTerburuk
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
                position: 'right',
            }
        };

        var barTerburuk = new ApexCharts(document.querySelector("#barTerburuk"), optionsBarTerburuk);
        barTerburuk.render();

        $(function() {
            var beforePrint = function() {
            };


            var afterPrint = function() {
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
            }

            #timeframes-list-table {
                font-size: 0.8em;
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
            <h5 class="text-muted mb-2"><strong>3 Timeframe Performa Terbaik</strong></h5>
            <hr>
            <div id="barTerbaik"></div>
        </div>
        <div class="w-50">
            <h5 class="text-muted mb-2"><strong>3 Timeframe Performa Terburuk</strong></h5>
            <hr>
            <div id="barTerburuk"></div>
        </div>
    </div>
    <div class="mb-3">
        <div class="h5 text-muted mb-2"><strong>Daftar Timeframe</strong></div>
        <hr>
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
                    @forelse ($data->sortBy([['jumlah_trades', 'desc'],[ 'win_loss_percent', 'desc']]) as $item)
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
</x-print-layout>

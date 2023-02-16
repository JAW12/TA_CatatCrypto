@section('report-title')
    Laporan Trading Berdasarkan Koin
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
                position: 'bottom',
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

            #coins-list-table {
                font-size: 0.8em;
            }

            #coins-list-table img {
                width: 30px;
                margin-right: 5px;
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
            <h5 class="text-muted mb-2"><strong>3 Koin Performa Terbaik</strong></h5>
            <hr>
            <div id="barTerbaik"></div>
        </div>
        <div class="w-50">
            <h5 class="text-muted mb-2"><strong>3 Koin Performa Terburuk</strong></h5>
            <hr>
            <div id="barTerburuk"></div>
        </div>
    </div>
    <div class="mb-3">
        <div class="h5 text-muted mb-2"><strong>Daftar Koin</strong></div>
        <hr>
        <div class="table-responsive">
            <table id="coins-list-table" class="table table-striped table-hover" role="grid"
                data-toggle="data-table">
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
                    @forelse ($data->sortByDesc('jumlah_trades')->sortByDesc('total_profit') as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
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
</x-print-layout>

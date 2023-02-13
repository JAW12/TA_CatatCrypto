@section('report-title', 'Laporan Demografi Aset')
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
        var optionsPie = <?php echo json_encode($optionsPie); ?>;

        var pie = new ApexCharts(document.querySelector("#pie"), optionsPie);
        pie.render();

        pie.updateOptions({
            legend: {
                formatter: function(seriesName, opts) {
                    var label = opts.w.globals.labels[opts.seriesIndex];
                    var percent = opts.w.globals.series[opts.seriesIndex] / opts.w.globals.seriesTotals.reduce(
                        function(
                            acc, val) {
                            return acc + val;
                        }, 0) * 100;
                    var value = opts.w.globals.series[opts.seriesIndex];
                    if (parseInt(value) >= 1000) {
                        return label + ' ' + percent.toFixed(0) + '% - ' + '$' + value.toString().replace(
                            /\B(?=(\d{3})+(?!\d))/g, ",");
                    } else {
                        return label + ' ' + percent.toFixed(0) + '% - ' + '$' + value;
                    }
                }
            }
        });

        var seriesBarTerbaik = <?php echo json_encode($seriesBarTerbaik); ?>;
        var labelsBarTerbaik = <?php echo json_encode($labelsBarTerbaik); ?>;
        var optionsBarTerbaik = {
            series: [{
                nama: 'PNL',
                data: seriesBarTerbaik
            }],
            chart: {
                type: 'bar',
                width: '60%',
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
            }
        };

        var barTerbaik = new ApexCharts(document.querySelector("#barTerbaik"), optionsBarTerbaik);
        barTerbaik.render();

        var seriesBarTerburuk = <?php echo json_encode($seriesBarTerburuk); ?>;
        var labelsBarTerburuk = <?php echo json_encode($labelsBarTerburuk); ?>;
        var optionsBarTerburuk = {
            series: [{
                nama: 'PNL',
                data: seriesBarTerburuk
            }],
            chart: {
                type: 'bar',
                width: '60%',
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
            }
        };

        var barTerburuk = new ApexCharts(document.querySelector("#barTerburuk"), optionsBarTerburuk);
        barTerburuk.render();

        var table = $("#wallets-list-table").DataTable({
            dom: '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
            language: {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                "destroy": true,
            },
            lengthChange: false,
            pageLength: 3,
            ordering: false,
            responsive: true,
            searching: false,
            paging: false,
            info: false,
        });

        window.print();

        var beforePrint = function() {
            pie.updateOptions({});
        };

        var afterPrint = function() {
            pie.updateOptions({});
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
        <p class="text-dark"><strong>Jumlah Aset:
                ${{ number_format((float) $data->active_wallets->sum('amount_of_assets'), 2, '.', ',') }}</strong>
        </p>
        <p class="text-dark"><strong>Total Keuntungan: <span
                    class="@if ($data->active_wallets->sum('pnl') > 0) text-success @elseif($data->active_wallets->sum('pnl') < 0) text-danger @endif">
                    @if ($data->active_wallets->sum('pnl') < 0)
                        -
                    @endif
                    ${{ number_format(abs((float) $data->active_wallets->sum('pnl')), 2, '.', ',') }}
                </span></strong></p>
    </div>
    <div class="d-flex justify-content-between mb-3">
        <div style="width: 37%">
            <h5 class="text-muted mb-2"><strong>Alokasi Aset</strong></h5>
            <hr>
            <div id="pie" class="mb-3" style="width: 100%"></div>
        </div>
        <div style="width: 63%">
            <h5 class="text-muted mb-2"><strong>Daftar Aset</strong></h5>
            <hr>
            <div class="table-responsive">
                <table id="wallets-list-table" class="table table-striped table-hover" role="grid"
                    data-toggle="data-table">
                    <thead>
                        <tr class="light">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Nilai</th>
                            <th>Keuntungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terbanyak as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['asset_name'] }}</td>
                                <td>{{ number_format((float) $item['amount'], 2, '.', ',') }}
                                <td>${{ number_format((float) $item['total'], 2, '.', ',') }}
                                <td
                                    class="@if ($item['pnl'] > 0) text-success @elseif($item['pnl'] < 0) text-danger @endif">
                                    @if ($item['pnl'] < 0)
                                        -
                                    @endif
                                    ${{ number_format(abs((float) $item['pnl']), 2, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div class="w-50">
            <h5 class="text-muted mb-2"><strong>3 Aset Performa Terbaik</strong></h5>
            <hr>
            <div id="barTerbaik" class="mb-3"></div>
        </div>
        <div class="w-50">
            <h5 class="text-muted mb-2"><strong>3 Aset Performa Terburuk</strong></h5>
            <hr>
            <div id="barTerburuk" class="mb-3"></div>
        </div>
    </div>
</x-print-layout>

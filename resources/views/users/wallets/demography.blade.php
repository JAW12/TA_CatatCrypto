@section('title', 'Demografi Dompet')
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
                type: 'bar'
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
                type: 'bar'
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
    </script>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <x-back-button>{{ route('user.wallet') }}</x-back-button>
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card" id="target-element">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-9">
                                    <h4 class="card-title">Demografi Dompet</h4>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <a href="{{ route('user.wallet.demography.print') }}" target="_blank"
                                        class="btn btn-primary w-100" id="export-pdf">Cetak
                                        Laporan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-5">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Jumlah Aset:</strong></td>
                                            <td><strong>${{ number_format((float) $data->active_wallets->sum('amount_of_assets'), 2, '.', ',') }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Total Keuntungan:</strong></td>
                                            <td
                                                class="@if ($data->active_wallets->sum('pnl') > 0) text-success @elseif($data->active_wallets->sum('pnl') < 0) text-danger @endif">
                                                <strong>
                                                    @if ($data->active_wallets->sum('pnl') < 0)
                                                        -
                                                    @endif
                                                    ${{ number_format(abs((float) $data->active_wallets->sum('pnl')), 2, '.', ',') }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <p class="text-dark mb-2"><strong>Alokasi Dompet</strong></p>
                                    <div id="pie" class="mb-3"></div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <p class="text-dark mb-2"><strong>3 Dompet Performa Terbaik</strong></p>
                                            <div id="barTerbaik" class="mb-3"></div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <p class="text-dark mb-2"><strong>3 Dompet Performa Terburuk</strong></p>
                                            <div id="barTerburuk" class="mb-3"></div>

                                        </div>
                                    </div>
                                    <p class="text-dark mb-2"><strong>Daftar Dompet</strong></p>
                                    <div class="table-responsive">
                                        <table id="wallets-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Jumlah Aset</th>
                                                    <th>Keuntungan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($walletAssetTerbanyak as $wallet)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $wallet->name }}</td>
                                                        <td>${{ number_format((float) $wallet->amount_of_assets, 2, '.', ',') }}
                                                        <td
                                                            class="@if ($wallet->pnl > 0) text-success @elseif($wallet->pnl < 0) text-danger @endif">
                                                            @if ($wallet->pnl < 0)
                                                                -
                                                            @endif
                                                            ${{ number_format(abs((float) $wallet->pnl), 2, '.', ',') }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4">Data tidak tersedia</td>
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
            </div>
        </div>
    @endif
</x-app-layout>

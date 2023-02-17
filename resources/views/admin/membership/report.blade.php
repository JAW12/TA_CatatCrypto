@section('title', 'Laporan Pendapatan')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.2.0/dist/js/datepicker-full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.2.0/dist/js/locales/id.js"></script>
    <script>
         // Date range filter
        var minDateFilter = null;
        var maxDateFilter = null;

        loadData();
        function loadData(){
            $.ajax({
                url: "{{ route('admin.transactions.report.load') }}",
                type: 'get',
                data: {
                    minDate: minDateFilter,
                    maxDate: maxDateFilter,
                },
                success: function(data) {
                    $("#total_count").html(`<strong>${data.total.count.toLocaleString('en-US')}</strong>`);
                    $("#total_income").html(`<strong>Rp ${data.total.income.toLocaleString('en-US')}</strong>`);

                    $("#basic").html(`<strong>${data.basic.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.basic.income.toLocaleString('en-US')})</span></strong>`);
                    $("#home").html(`<strong>${data.home.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.home.income.toLocaleString('en-US')})</span></strong>`);
                    $("#professional").html(`<strong>${data.professional.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.professional.income.toLocaleString('en-US')})</span></strong>`);
                    $("#business").html(`<strong>${data.business.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.business.income.toLocaleString('en-US')})</span></strong>`);
                    $("#additional").html(`<strong>${data.additional.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.additional.income.toLocaleString('en-US')})</span></strong>`);

                    $("#manual_bank").html(`<strong>${data.manual_bank.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.manual_bank.income.toLocaleString('en-US')})</span></strong>`);
                    $("#manual_ewallet").html(`<strong>${data.manual_ewallet.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.manual_ewallet.income.toLocaleString('en-US')})</span></strong>`);
                    $("#credit_card").html(`<strong>${data.credit_card.count.toLocaleString('en-US')} <span class="text-muted small ms-1">(Rp ${data.credit_card.income.toLocaleString('en-US')})</span></strong>`);
                }
            });
        }

        $(function() {

            var transaction_table = $("#transactions-list-table").DataTable({
                "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                    "destroy": true,
                },
            });


            const elem = document.getElementById('date_range');
            const range_picker = new DateRangePicker(elem, {
                buttonClass: 'btn',
                allowOneSidedRange: true,
                todayBtn: true,
                todayBtnMode: 1,
                language: 'id',
            });

            const startElem = document.getElementById('start');
            const endElem = document.getElementById('end');

            startElem.addEventListener('changeDate', function(e) {
                if (e.detail.date != null) {
                    minDateFilter = new Date(e.detail.date);
                } else {
                    minDateFilter = null;
                }
                transaction_table.draw();
                loadData();
            });

            endElem.addEventListener('changeDate', function(e) {
                if (e.detail.date != null) {
                    maxDateFilter = new Date(e.detail.date);
                } else {
                    maxDateFilter = null;
                }
                transaction_table.draw();
                loadData();
            });

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = minDateFilter;
                    var max = maxDateFilter;
                    var maxPlusOne = null;
                    if (max != null) {
                        var maxPlusOne = new Date(max.getTime() + 86400000);
                    }
                    var date = new Date(data[6]);

                    if ((min === null && maxPlusOne === null) || (min === null && date <= maxPlusOne) || (min <= date &&
                    maxPlusOne === null) || (min <= date && date <= maxPlusOne)) {
                        return true;
                    }
                    return false;
                }
            );

            $("#export-pdf").click(function(e){
                var start = $("#start").val();
                var end = $("#end").val();
                var url = $("#export-pdf").attr("href");
                window.open(url + "?start=" + start + "&end=" + end, '_blank');
            })
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card" id="target-element">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-3">
                                    <h4 class="card-title">Laporan Pendapatan</h4>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div id="date_range" class="d-flex align-items-center">
                                        <input type="text" name="start" id="start" class="form-control"
                                            placeholder="Dari tanggal">
                                        <span class="mx-3">-</span>
                                        <input type="text" name="end" id="end" class="form-control"
                                            placeholder="Sampai tanggal">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <button href="{{route('admin.transactions.report.print')}}" class="btn btn-primary w-100"
                                        id="export-pdf">Cetak
                                        Laporan</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <p class="text-muted mb-2"><strong>Ringkasan Membership</strong></p>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Paket Basic:</strong></td>
                                                <td id="basic"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Paket Home:</strong></td>
                                                <td id="home"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Paket Professional:</strong></td>
                                                <td id="professional"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Paket Business:</strong></td>
                                                <td id="business"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Tambahan Catatan:</strong></td>
                                                <td id="additional"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <p class="text-muted mb-2"><strong>Ringkasan Metode Pembayaran</strong></p>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Transfer Bank Manual:</strong></td>
                                                <td id="manual_bank"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Transfer E-Wallet Manual:</strong></td>
                                                <td id="manual_ewallet"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-4">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Credit Card (Midtrans):</strong></td>
                                                <td id="credit_card"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <p class="text-muted mb-2"><strong>Daftar Transaksi</strong></p>
                                <hr>
                                <div class="table-responsive mb-3">
                                    <table id="transactions-list-table" class="table table-striped table-hover"
                                        role="grid" data-toggle="data-table">
                                        <thead>
                                            <tr class="light">
                                                <th>#</th>
                                                <th>Kode Order</th>
                                                <th>Nama Pengguna</th>
                                                <th>Pembayaran Untuk</th>
                                                <th>Jumlah Pembayaran</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Waktu Pembayaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transactions as $transaction)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $transaction->id_order }}</td>
                                                    <td>{{ $transaction->user->full_name }}</td>
                                                    @if ($transaction->membership_id != null)
                                                        <td>Paket {{ ucwords($transaction->membership->name) }}</td>
                                                    @else
                                                        <td>Penambahan 100 Catatan Trading</td>
                                                    @endif
                                                    <td>Rp {{ number_format($transaction->gross_amount, 2) }}</td>
                                                    @if ($transaction->payment_type == 'credit_card')
                                                        <td>Credit Card (Midtrans)</td>
                                                    @else
                                                        <td>{{ $transaction->payment_type }}</td>
                                                    @endif
                                                    <td>{{ date_format(date_create($transaction->payment_time), 'd F Y H:i:s') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark">
                                            <tr>
                                                <td><strong>Jumlah Transaksi:</strong></td>
                                                <td id="total_count"><strong>0</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <table class="text-dark ms-auto">
                                            <tr>
                                                <td><strong>Total Pendapatan:</strong></td>
                                                <td id="total_income"><strong>$0.00</strong></td>
                                            </tr>
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

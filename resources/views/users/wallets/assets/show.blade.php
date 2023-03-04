@section('title', 'Detail Aset')

@push('styles')
    <style>
        .nav.nav-tabs>li.disabled {
            pointer-events: none;
        }

        .nav.nav-tabs>li.disabled>button {
            color: silver !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.min.js"
        integrity="sha512-6j+LxzZ7EO1Kr7H5yfJ8VYCVZufCBMNFhSMMzb2JRhlwQ/Ri7Zv8VfJ7YI//cg9H5uXT2lQpb14YMvqUAdGlcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var autoNumericPriceBeli = null;
        var autoNumericPriceJual = null;
        var autoNumericAmountBeli = null;
        var autoNumericAmountJual = null;
        var autoNumericAmountTransfer = null;
        var autoNumericFeeBeli = null;
        var autoNumericFeeJual = null;
        var autoNumericFeeTransfer = null;
        var autoNumericTotalBeli = null;
        var autoNumericTotalJual = null;

        autoNumericPriceBeli = new AutoNumeric('#beli_price', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });
        autoNumericPriceJual = new AutoNumeric('#jual_price', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });

        autoNumericAmountBeli = new AutoNumeric('#beli_amount', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });
        autoNumericAmountJual = new AutoNumeric('#jual_amount', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });
        autoNumericAmountTransfer = new AutoNumeric('#transfer_amount', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });

        autoNumericFeeBeli = new AutoNumeric('#beli_fee', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });
        autoNumericFeeJual = new AutoNumeric('#jual_fee', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });
        autoNumericFeeTransfer = new AutoNumeric('#transfer_fee', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });

        autoNumericTotalBeli = new AutoNumeric('#beli_total', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });
        autoNumericTotalJual = new AutoNumeric('#jual_total', {
            allowDecimalPadding: false,
            decimalPlaces: 16
        });

        loadData();

        function loadData() {
            $.ajax({
                url: "{{ route('user.wallet.asset.detail.load', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}",
                type: "get",
                success: function(data) {
                    // console.log(data);
                    let transactions = data.transactions;
                    let symbol = "<?php echo $asset->symbol; ?>";
                    let current_price = "<?php echo $asset->current_price; ?>";

                    let table = document.createElement('table');
                    table.setAttribute("id", "assets-list-table");
                    table.setAttribute("class", "table table-striped table-hover");
                    table.setAttribute("role", "grid");
                    table.setAttribute("data-toggle", "data-table");

                    let thead = document.createElement("thead");
                    thead.innerHTML = `
                        <tr class='light'>
                            <th>#</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Waktu</th>
                            <th>Jumlah Aset</th>
                            <th>Biaya Tambahan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>`;

                    let tbody = document.createElement("tbody");
                    if (transactions != null) {
                        $ctr = 1;
                        transactions.forEach(transaction => {
                            let typeTd = "Beli";
                            let priceTd = "-";
                            let amountTd = "";
                            let totalTd = "";

                            let priceString = '';
                            if (transaction.price > 0 && transaction.price < 1) {
                                priceString = `${parseFloat(transaction.price)}`;
                            } else {
                                priceString =
                                    `${parseFloat(transaction.price).toLocaleString('en-US')}`;
                            }

                            let amountString = '';
                            if (Math.abs(transaction.amount) > 0 && Math.abs(transaction.amount) < 1) {
                                amountString = `${parseFloat(Math.abs(transaction.amount))}`;
                            } else {
                                amountString =
                                    `${parseFloat(Math.abs(transaction.amount)).toLocaleString('en-US')}`;
                            }

                            let totalString = '';
                            if (Math.abs(transaction.total) > 0 && Math.abs(transaction.total) < 1) {
                                totalString = `${parseFloat(Math.abs(transaction.total))}`;
                            } else {
                                totalString =
                                    `${parseFloat(Math.abs(transaction.total)).toLocaleString('en-US')}`;
                            }


                            if (transaction.type == 0) {
                                typeTd = "Beli";
                                priceTd = "$".concat(priceString);
                                amountTd = "+".concat(amountString).concat(' ').concat(
                                    symbol);
                                totalTd = "+".concat(totalString).concat('$');
                            } else if (transaction.type == 1) {
                                typeTd = "Jual";
                                priceTd = "$".concat(priceString);
                                amountTd = "-".concat(amountString).concat(' ').concat(
                                    symbol);
                                totalTd = "-".concat(totalString).concat('$');
                            } else if (transaction.type == 2) {
                                typeTd = "Transfer Keluar";
                                amountTd = "-".concat(amountString).concat(' ').concat(
                                    symbol);
                                totalTd = "-"
                            } else if (transaction.type == 3) {
                                typeTd = "Transfer Masuk";
                                amountTd = "+".concat(amountString).concat(' ').concat(
                                    symbol);
                                totalTd = "-";
                            }

                            let feeString = '';
                            if (transaction.fee > 0 && transaction.fee < 1) {
                                feeString = `${parseFloat(transaction.fee)}`;
                            } else {
                                feeString =
                                    `${parseFloat(transaction.fee).toLocaleString('en-US')}`;
                            }

                            let fee = '-';
                            if (transaction.fee > 0) {
                                fee = "$" + feeString;
                            }

                            let status = '';
                            if (transaction.status == 1) {
                                status = '<span class="badge rounded-pill bg-primary">Terpenuhi</span>';
                            } else if (transaction.status == 0) {
                                status =
                                    '<span class="badge rounded-pill bg-secondary">Belum Terpenuhi</span>';
                            }

                            let aksi = '';
                            let binance_api_key = "<?php echo $wallet->binance_api_key; ?>";
                            let route = `<?php echo route('user.wallet.asset.detail.delete', ['wallet' => $wallet->id, 'asset' => $asset->id, 'asset_transaction' => ':asset_transaction']); ?>`;
                            route = route.replace(':asset_transaction', transaction.id);
                            if (binance_api_key != '') {
                                if (transaction.status == 0) {
                                    aksi = `
                                        <div class="flex align-items-center list-asset-transaction-action">
                                                <form action="${route}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-icon btn-danger btn-delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                                            <span class="btn-inner">
                                                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                                    <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5"  stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    </svg>
                                                                    </span>
                                                                    </button>
                                                                    </form>`;
                                }
                            } else {
                                aksi = `
                                    <div class="flex align-items-center list-asset-transaction-action">
                                        <button type="button" class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-bs-toggle="modal" data-bs-target="#tambahTransaksiModal" data-transaction-id='${JSON.stringify(transaction)}'>
                                            <span class="btn-inner">
                                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    </span>
                                                    </button>
                                                    <form action="${route}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-icon btn-danger btn-delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                                            <span class="btn-inner">
                                                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                                    <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5"  stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    </svg>
                                                                    </span>
                                                                    </button>
                                                                    </form>`;
                            }
                            let tr = document.createElement('tr');
                            tr.innerHTML = `<td>${$ctr++}</td>
                                    <td>${typeTd}</td>
                                    <td>${priceTd}</td>
                                    <td>${transaction.time}</td>
                                    <td>${amountTd}<br>${totalTd}</td>
                                    <td>${fee}</td>
                                    <td>${status}</td>
                                    <td>${aksi}</td>`;

                            tbody.append(tr);
                        });
                        table.append(thead);
                        table.append(tbody);
                    }

                    let change = '';
                    if (data.pnl > 0) {
                        if (data.pnl > 999) {
                            change =
                                `<span class="h6 text-success">$${parseFloat(data.pnl).toFixed(2)} (+${parseFloat(data.pnl_percentage).toFixed(2).toLocaleString('en-US')}%)</span>`;
                        } else {
                            change =
                                `<span class="h6 text-success">$${parseFloat(data.pnl).toFixed(2)} (+${parseFloat(data.pnl_percentage).toFixed(2)}%)</span>`;
                        }
                    } else if (data.pnl < 0) {
                        change =
                            `<span class="h6 text-danger">-$${Math.abs(parseFloat(data.pnl)).toFixed(2)} (${-parseFloat(data.pnl_percentage).toFixed(2)}%)</span>`;
                    } else {
                        change =
                            `<span class="h6">$${parseFloat(data.pnl).toFixed(2)} (${parseFloat(data.pnl_percentage).toFixed(2)}%)</span>`;
                    }
                    $("#change").html(change);

                    let amountString = '';
                    if (data.amount > 0 && data.amount < 1) {
                        amountString = `${parseFloat(data.amount)}`;
                    } else {
                        amountString =
                            `${parseFloat(data.amount).toLocaleString('en-US')}`;
                    }
                    $("#amount").html(`<strong>${amountString} ${symbol}</strong>`);

                    let totalString = '';
                    if (data.total > 0 && data.total < 1) {
                        totalString = `${parseFloat(data.total)}`;
                    } else {
                        totalString =
                            `${parseFloat(data.total).toLocaleString('en-US')}`;
                    }
                    $("#total").html(`<small>$ ${totalString}</small>`);

                    let averagePriceString = '';
                    if (data.average_price > 0 && data.average_price < 1) {
                        averagePriceString = `${parseFloat(data.average_price)}`;
                    } else {
                        averagePriceString =
                            `${parseFloat(data.average_price).toLocaleString('en-US')}`;
                    }
                    $("#average_price").html(`<strong>$ ${averagePriceString}</strong>`);

                    let currentPriceString = '';
                    if (current_price > 0 && current_price < 1) {
                        currentPriceString = `${parseFloat(current_price)}`;
                    } else {
                        currentPriceString =
                            `${parseFloat(current_price).toLocaleString('en-US')}`;
                    }
                    $("#current_price").html(`<strong>$ ${currentPriceString}</strong>`);

                    $("#table-container").html(table);
                    let tables = $('[data-toggle="data-table"]').DataTable({
                        "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                        },
                    });
                    loaderInit();

                    $('.btn-delete').click(function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Apakah anda yakin akan menghapus transaksi ini?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Iya, hapus!',
                            cancelButtonText: 'Tidak',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $(e.target).closest('form')
                                .submit() // Post the surrounding form
                            }
                        })
                    });
                },
                error: function(request, status, error) {
                    let symbol = "<?php echo $asset->symbol; ?>";
                    let current_price = "<?php echo $asset->current_price; ?>";

                    let table = document.createElement('table');
                    table.setAttribute("id", "assets-list-table");
                    table.setAttribute("class", "table table-striped table-hover");
                    table.setAttribute("role", "grid");
                    table.setAttribute("data-toggle", "data-table");

                    let thead = document.createElement("thead");
                    thead.innerHTML = `
                    <tr class='light'>
                        <th>#</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Waktu</th>
                        <th>Jumlah Aset</th>
                        <th>Biaya Tambahan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>`;

                    let tbody = document.createElement("tbody");
                    table.append(thead);
                    table.append(tbody);

                    $("#table-container").html(table);
                    let tables = $('[data-toggle="data-table"]').DataTable({
                        "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                        },
                    });
                    loaderInit();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!!!',
                        text: 'Load gagal',
                        confirmButtonColor: "#3a57e8"
                    });
                }
            });
        }

        function loadTransaction(price, amount, fee) {
            if (fee == null) {
                fee = 0;
            }
            let gross = parseFloat(price) * parseFloat(amount);
            let nett = gross + parseFloat(fee);
            // console.log(gross);
            // console.log(price, amount, fee, gross, nett);

            return nett;
        }

        function datetimeLocal(datetime) {
            const dt = new Date(datetime);
            dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
            return dt.toISOString().slice(0, 16);
        }

        function reset() {
            setPrice(0);
            setAmount(0);
            setFee(0);
            setTotal(0);
        }

        function setPrice(value) {
            autoNumericPriceBeli.set(value);
            autoNumericPriceJual.set(value);
        }

        function setAmount(value) {
            value = Math.abs(value);
            autoNumericAmountBeli.set(value);
            autoNumericAmountJual.set(value);
            autoNumericAmountTransfer.set(value);
        }

        function setFee(value) {
            autoNumericFeeBeli.set(value);
            autoNumericFeeJual.set(value);
            autoNumericFeeTransfer.set(value);
        }

        function setTotal(value) {
            value = Math.abs(value);
            autoNumericTotalBeli.set(value);
            autoNumericTotalJual.set(value);
        }

        $(function() {
            $('#tambahTransaksiModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('transaction-id');

                var modal = $(this);
                if (recipient != null) {
                    modal.find('.modal-title').text('Ubah Transaksi');
                    console.log(recipient);
                    var dt = Date.parse(recipient.time);

                    setPrice(recipient.price);
                    setAmount(recipient.amount);
                    setFee(recipient.fee);
                    setTotal(recipient.total);

                    $("input[name=time]").val(datetimeLocal(dt));
                    $("input[name=transaction_id]").val(parseFloat(recipient.id));

                    if (recipient.type == 0) {
                        $('#beli-tab').tab('show');
                    } else if (recipient.type == 1) {
                        $('#jual-tab').tab('show');
                    } else if (recipient.type > 1) {
                        $('#transfer-tab').tab('show');
                    }
                } else {
                    modal.find('.modal-title').text('Tambah Transaksi');
                    reset();
                    $("input[name=time]").val(null);
                    $("input[name=transaction_id]").val(null);
                }

            });

            $("input[name=price]").on('change', function() {
                let price = 0;
                let amount = 0;
                let fee = 0;

                if ($(this).attr('id') == 'beli_price') {
                    price = autoNumericPriceBeli.get();
                    amount = autoNumericAmountBeli.get();
                    fee = autoNumericFeeBeli.get();

                    autoNumericPriceJual.set(price);
                }
                if ($(this).attr('id') == 'jual_price') {
                    price = autoNumericPriceJual.get();
                    amount = autoNumericAmountJual.get();
                    fee = autoNumericFeeJual.get();

                    autoNumericPriceBeli.set(price);
                }
                setTotal(loadTransaction(price, amount, fee));
            });

            $("input[name=amount]").on('change', function() {
                let price = 0;
                let amount = 0;
                let fee = 0;

                if ($(this).attr('id') == 'beli_amount') {
                    price = autoNumericPriceBeli.get();
                    amount = autoNumericAmountBeli.get();
                    fee = autoNumericFeeBeli.get();

                    autoNumericAmountJual.set(amount);
                    autoNumericAmountTransfer.set(amount);
                }
                if ($(this).attr('id') == 'jual_amount') {
                    price = autoNumericPriceJual.get();
                    amount = autoNumericAmountJual.get();
                    fee = autoNumericFeeJual.get();

                    autoNumericAmountBeli.set(amount);
                    autoNumericAmountTransfer.set(amount);
                }
                if ($(this).attr('id') == 'transfer_amount') {
                    price = autoNumericPriceJual.get();
                    amount = autoNumericAmountJual.get();
                    fee = autoNumericFeeJual.get();

                    autoNumericAmountBeli.set(amount);
                    autoNumericAmountJual.set(price);
                }
                setTotal(loadTransaction(price, amount, fee));
            });

            $("input[name=fee]").on('change', function() {
                let price = 0;
                let amount = 0;
                let fee = 0;

                if ($(this).attr('id') == 'beli_fee') {
                    price = autoNumericPriceBeli.get();
                    amount = autoNumericAmountBeli.get();
                    fee = autoNumericFeeBeli.get();

                    autoNumericFeeJual.set(fee);
                    autoNumericFeeTransfer.set(fee);
                }
                if ($(this).attr('id') == 'jual_fee') {
                    price = autoNumericPriceJual.get();
                    amount = autoNumericAmountJual.get();
                    fee = autoNumericFeeJual.get();

                    autoNumericFeeBeli.set(fee);
                    autoNumericFeeTransfer.set(fee);
                }
                if ($(this).attr('id') == 'transfer_fee') {
                    price = autoNumericPriceJual.get();
                    amount = autoNumericAmountJual.get();
                    fee = autoNumericFeeJual.get();

                    autoNumericFeeBeli.set(fee);
                    autoNumericFeeJual.set(fee);
                }
                setTotal(loadTransaction(price, amount, fee));
            });

            $("input[name=time]").on('change', function() {
                let time = $(this).val();
                $("input[name=time]").val(time);
            });

            $("#btnHapus").click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan menghapus aset ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, hapus!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(e.target).closest('form').submit() // Post the surrounding form
                    }
                })
            });

            // $("button[type=reset]").on('click', function() {
            //     $("input[name=price]").val(0);
            //     $("input[name=amount]").val(0);
            //     $("input[name=fee]").val(0);
            //     $("input[name=total]").val(0);
            //     $("input[name=time]").val();
            // });
        });
    </script>
@endpush

<x-app-layout>
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <x-back-button>{{ route('user.wallet.detail', $wallet->id) }}</x-back-button>
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-5 d-inline-flex align-items-center">
                                    <img src="{{ $asset->thumb }}" alt="logo_crypto" class="img-thumbnail">
                                    <h4 class="card-title ms-3 mt-2">
                                        {{ $asset->name }}
                                        <span id="change">
                                            <span
                                                class="h6 @if ($asset_wallet->pnl > 0) text-success @elseif($asset_wallet->pnl < 0) text-danger @endif">{{ (float) $asset_wallet->pnl }}$
                                                ({{ (float) $asset_wallet->pnl_percentage }}%)</span>
                                        </span>
                                        <a href="{{ route('user.wallet.asset.detail.info', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}""
                                            class="btn btn-light btn-sm">Lihat Info Koin</a>
                                    </h4>
                                </div>
                                <div class="col-sm-12 col-md-7 justify-content-md-end mt-3 mt-md-0">
                                    <div class="row g-2">
                                        <div class="col-sm-12 col-md-4">
                                            @if ($wallet->binance_api_key == null or count($asset_wallet->transactions) == 0)
                                                <form
                                                    action="{{ route('user.wallet.asset.delete', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}"
                                                    method="post" class="w-100">
                                                    @csrf
                                                    @method('delete')
                                                    <button id="btnHapus" type="submit"
                                                        class="btn btn-danger w-100">Hapus Aset Ini</button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <a href="{{route('user.wallet.asset.report', ['wallet' => $wallet->id, 'asset' => $asset->id])}}" type="button" class="btn btn-dark w-100">Laporan Aset</a>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                                data-bs-target="#tambahTransaksiModal">+ Tambah Transaksi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Jumlah Koin:</strong></td>
                                            <td id="amount"><strong>{{ (float) $asset_wallet->amount }}
                                                    {{ $asset->symbol }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td id="total"><small>$ {{ (float) $asset_wallet->total }}</small></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Harga Rata-Rata:</strong></td>
                                            <td id="average_price"><strong>$
                                                    {{ (float) $asset_wallet->average_price }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <table class="text-dark">
                                        <tr>
                                            <td><strong>Harga Sekarang:</strong></td>
                                            <td id="current_price"><strong>$
                                                    {{ (float) $asset->current_price }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="table-responsive" id="table-container">
                                {{-- <table id="assets-list-table" class="table table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Tipe</th>
                                        <th>Harga</th>
                                        <th>Waktu</th>
                                        <th>Jumlah Aset</th>
                                        <th>Biaya Tambahan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asset_wallet->transactions as $key => $transaction)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($transaction->type == 0)
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
                                                @if ($transaction->type == 0 or $transaction->type == 1)
                                                    ${{ (float) $transaction->price }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ $transaction->time }}
                                            </td>
                                            <td>
                                                @if ($transaction->type == 0)
                                                    +{{ (float) $transaction->amount }}
                                                @elseif($transaction->type == 1)
                                                    {{ (float) $transaction->amount }}
                                                @elseif($transaction->type == 2)
                                                    {{ (float) $transaction->amount }}
                                                @elseif($transaction->type == 3)
                                                    +{{ (float) $transaction->amount }}
                                                @endif

                                                {{ $asset->symbol }}

                                                <br>

                                                @if ($transaction->type == 0)
                                                    <small>+{{ (float) $transaction->total }}$</small>
                                                @elseif($transaction->type == 1)
                                                    <small>{{ (float) $transaction->total }}$</small>
                                                @elseif($transaction->type == 2)
                                                    <small>{{ (float) $transaction->total }}$</small>
                                                @elseif($transaction->type == 3)
                                                    <small>+{{ (float) $transaction->total }}$</small>
                                                @endif

                                            </td>
                                            <td>
                                                @if ($transaction->fee > 0)
                                                    ${{ (float) $transaction->fee }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction->status == 1)
                                                    <span class="badge rounded-pill bg-primary">Terpenuhi</span>
                                                @elseif($transaction->status == 0)
                                                    <span class="badge rounded-pill bg-secondary">Belum Terpenuhi</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($wallet->binance_api_key != null)
                                                    @if ($transaction->status == 0)
                                                        <div
                                                            class="flex align-items-center list-asset-transaction-action">
                                                            <button type="button"
                                                                class="btn btn-sm btn-icon btn-success"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="" data-original-title="Edit"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#tambahTransaksiModal"
                                                                data-transaction-id="{{ $transaction }}">
                                                                <span class="btn-inner">
                                                                    <svg width="20" viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                        </path>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                        </path>
                                                                        <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                            <form
                                                                action="{{ route('user.wallet.asset.detail.delete', ['wallet' => $wallet->id, 'asset' => $asset->id, 'asset_transaction' => $transaction->id]) }}"
                                                                method="post" class="d-inline">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    class="btn btn-sm btn-icon btn-danger btn-delete"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" data-original-title="Delete">
                                                                    <span class="btn-inner">
                                                                        <svg width="20" viewBox="0 0 24 24"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            stroke="currentColor">
                                                                            <path
                                                                                d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                                                stroke="currentColor" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round">
                                                                            </path>
                                                                            <path d="M20.708 6.23975H3.75"
                                                                                stroke="currentColor" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"></path>
                                                                            <path
                                                                                d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                                stroke="currentColor" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                            </form>
                                                    @endif
                                                @else
                                                    <div class="flex align-items-center list-asset-transaction-action">
                                                        <button type="button" class="btn btn-sm btn-icon btn-success"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Edit" data-bs-toggle="modal"
                                                            data-bs-target="#tambahTransaksiModal"
                                                            data-transaction-id="{{ $transaction }}">
                                                            <span class="btn-inner">
                                                                <svg width="20" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                    </path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                    </path>
                                                                    <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        <form
                                                            action="{{ route('user.wallet.asset.detail.delete', ['wallet' => $wallet->id, 'asset' => $asset->id, 'asset_transaction' => $transaction->id]) }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-sm btn-icon btn-danger btn-delete"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="" data-original-title="Delete">
                                                                <span class="btn-inner">
                                                                    <svg width="20" viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        stroke="currentColor">
                                                                        <path
                                                                            d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                        </path>
                                                                        <path d="M20.708 6.23975H3.75"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path
                                                                            d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                            stroke="currentColor" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </form>
                                                @endif
                        </div>
                        </td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="tambahTransaksiModal" tabindex="-1" aria-labelledby="tambahTransaksiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahTransaksiTitle">Tambah Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="bd-example">
                            <ul class="nav nav-tabs nav-justified" id="myTab " role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="beli-tab" data-bs-toggle="tab"
                                        data-bs-target="#pills-beli" type="button" role="tab" aria-controls="beli"
                                        aria-selected="true">Beli</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="jual-tab" data-bs-toggle="tab"
                                        data-bs-target="#pills-jual" type="button" role="tab" aria-controls="jual"
                                        aria-selected="false">Jual</button>
                                </li>
                                <li class="nav-item @if ($wallet->binance_api_key != null) disabled @endif"
                                    role="presentation">
                                    <button class="nav-link " id="transfer-tab" data-bs-toggle="tab"
                                        data-bs-target="#pills-transfer" type="button" role="tab"
                                        aria-controls="transfer" aria-selected="false">Transfer</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-beli" role="tabpanel"
                                    aria-labelledby="pills-beli-tab1">
                                    <form
                                        action="{{ route('user.wallet.asset.detail.add', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" name="transaction_id" id="beli_transaction_id">
                                        <div class="form-group form-group-alt mb-2">
                                            <label for="price" class="form-label text-dark">Harga Koin</label>
                                            <input type="text" class="form-control" name="price"
                                                placeholder="0.000" id="beli_price">
                                        </div>
                                        <div class="form-group form-group-alt row gx-2 gy-0">
                                            <div
                                                class="col-sm-12 @if ($wallet->binance_api_key == null) col-md-6 @else col @endif">
                                                <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                                <div class="form-group form-group-alt input-group mb-3">
                                                    <input type="text" name="amount" class="form-control"
                                                        aria-label="Jumlah Koin" aria-describedby="basic-addon2"
                                                        id="beli_amount">
                                                    <span class="input-group-text"
                                                        id="basic-addon2">{{ $asset->symbol }}</span>
                                                </div>
                                            </div>
                                            <div
                                                class="col-sm-12 col-md-6 @if ($wallet->binance_api_key != null) d-none @endif">
                                                <label for="fee" class="form-label text-dark">Biaya
                                                    Tambahan</label>
                                                <input type="text" class="form-control" name="fee"
                                                    id="beli_fee">
                                            </div>
                                        </div>
                                        <div
                                            class="form-group form-group-alt @if ($wallet->binance_api_key != null) d-none @endif">
                                            <label for="time" class="form-label text-dark">Waktu</label>
                                            <input type="datetime-local" id="beli_time" name="time"
                                                class="form-control">
                                        </div>
                                        <div class="form-group form-group-alt">
                                            <label for="description" class="form-label text-dark">Catatan</label>
                                            <textarea name="description" class="form-control" style="height: 15vh; resize:none" id="beli_description"></textarea>
                                        </div>
                                        <div class="form-group form-group-alt">
                                            <label for="total" class="form-label text-dark">Jumlah
                                                Transaksi</label>

                                            <div class="form-group input-group form-group-alt">
                                                <span class="input-group-text text-dark"
                                                    style="background-color:#e9ecef; font-size: 2em"
                                                    id="basic-addon1">$</span>
                                                <input type="text" class="form-control" name="total"
                                                    aria-label="total" aria-describedby="basic-addon1"
                                                    style="font-size: 2em" readonly="readonly" id="beli_total">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                            <button type="submit" name="type" class="btn btn-primary"
                                                value="0">Kumpul</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="pills-jual" role="tabpanel"
                                    aria-labelledby="pills-jual-tab1">
                                    <form
                                        action="{{ route('user.wallet.asset.detail.add', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" name="transaction_id" id="jual_transaction_id">
                                        <div class="form-group form-group-alt mb-2">
                                            <label for="price" class="form-label text-dark">Harga Koin</label>
                                            <input type="text" class="form-control" name="price"
                                                placeholder="0.000" id="jual_price">
                                        </div>
                                        <div class="form-group form-group-alt row gx-2 gy-0">
                                            <div
                                                class="col-sm-12 @if ($wallet->binance_api_key == null) col-md-6 @else col @endif">
                                                <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                                <div class="form-group form-group-alt input-group mb-3">
                                                    <input type="text" name="amount" class="form-control"
                                                        aria-label="Jumlah Koin" aria-describedby="basic-addon2"
                                                        id="jual_amount">
                                                    <span class="input-group-text"
                                                        id="basic-addon2">{{ $asset->symbol }}</span>
                                                </div>

                                            </div>
                                            <div
                                                class="col-sm-12 col-md-6 @if ($wallet->binance_api_key != null) d-none @endif">
                                                <label for="fee" class="form-label text-dark">Biaya
                                                    Tambahan</label>
                                                <input type="text" class="form-control" name="fee"
                                                    id="jual_fee">
                                            </div>
                                        </div>
                                        <div
                                            class="form-group form-group-alt @if ($wallet->binance_api_key != null) d-none @endif">
                                            <label for="time" class="form-label text-dark">Waktu</label>
                                            <input type="datetime-local" name="time" class="form-control"
                                                id="jual_time">
                                        </div>
                                        <div class="form-group form-group-alt">
                                            <label for="description" class="form-label text-dark">Catatan</label>
                                            <textarea name="description" class="form-control" style="height: 15vh; resize:none" id="jual_description"></textarea>
                                        </div>
                                        <div class="form-group form-group-alt">
                                            <label for="total" class="form-label text-dark">Jumlah
                                                Transaksi</label>

                                            <div class="form-group input-group form-group-alt">
                                                <span class="input-group-text text-dark"
                                                    style="background-color:#e9ecef; font-size: 2em"
                                                    id="basic-addon1">$</span>
                                                <input type="text" class="form-control" name="total"
                                                    aria-label="total" aria-describedby="basic-addon1"
                                                    style="font-size: 2em" readonly="readonly" id="jual_total">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                            <button type="submit" name="type" value="1"
                                                class="btn btn-primary">Kumpul</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="pills-transfer" role="tabpanel"
                                    aria-labelledby="pills-transfer-tab1">
                                    <form
                                        action="{{ route('user.wallet.asset.detail.add', ['wallet' => $wallet->id, 'asset' => $asset->id]) }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" name="transaction_id" id="transfer_transaction_id">
                                        <div class="form-group form-group-alt mb-2">
                                            <label for="price" class="form-label text-dark">Jenis Transfer</label>
                                            <select class="form-control form-select" name="type">
                                                <option value="2">Transfer Keluar</option>
                                                <option value="3">Transfer Masuk</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-alt row gx-2 gy-0">
                                            <div
                                                class="col-sm-12 @if ($wallet->binance_api_key == null) col-md-6 @else col @endif">
                                                <label for="amount" class="form-label text-dark">Jumlah Koin</label>
                                                <div class="form-group form-group-alt input-group mb-3">
                                                    <input type="text" name="amount" class="form-control"
                                                        aria-label="Jumlah Koin" aria-describedby="basic-addon2"
                                                        id="transfer_amount">
                                                    <span class="input-group-text"
                                                        id="basic-addon2">{{ $asset->symbol }}</span>
                                                </div>

                                            </div>
                                            <div
                                                class="col-sm-12 col-md-6 @if ($wallet->binance_api_key != null) d-none @endif">
                                                <label for="fee" class="form-label text-dark">Biaya
                                                    Tambahan</label>
                                                <input type="text" class="form-control" name="fee"
                                                    id="transfer_fee">
                                            </div>
                                        </div>
                                        <div
                                            class="form-group form-group-alt @if ($wallet->binance_api_key != null) d-none @endif">
                                            <label for="time" class="form-label text-dark">Waktu</label>
                                            <input type="datetime-local" name="time" class="form-control"
                                                id="transfer_time">
                                        </div>
                                        <div class="form-group form-group-alt">
                                            <label for="description" class="form-label text-dark">Catatan</label>
                                            <textarea name="description" class="form-control" style="height: 15vh; resize:none" id="transfer_description"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                            <button type="submit" class="btn btn-primary">Kumpul</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

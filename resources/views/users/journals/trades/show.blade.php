@push('styles')
    <style>
        .ui-autocomplete {
            max-height: 25vh;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: ;
        }

        * html .ui-autocomplete {
            height: 25vh;
        }

        .img_input {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .img_input+label {
            margin-right: 1em;
            font-size: 1em;
            display: inline-block;
        }

        .img_input+label:hover {
            color: gray
        }

        .img_input+label {
            cursor: pointer;
            /* "hand" cursor */
        }

        .img_input+label * {
            pointer-events: none;
        }

        .img-wrap {
            position: relative;
        }

        .img-wrap .img-delete {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 100;
        }

        .img-wrap .img-delete:hover {
            filter: brightness(85%);
        }

        .select2 {
            width: 100% !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"
        integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"
        integrity="sha512-he8U4ic6kf3kustvJfiERUpojM8barHoz0WYpAUDWQVn61efpm3aVAD8RWL8OloaDDzMZ1gZiubF9OSdYBqHfQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.min.js"
        integrity="sha512-6j+LxzZ7EO1Kr7H5yfJ8VYCVZufCBMNFhSMMzb2JRhlwQ/Ri7Zv8VfJ7YI//cg9H5uXT2lQpb14YMvqUAdGlcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script> --}}
    <script>
        var risk = "<?php echo $journal->risk; ?>";
        var balances = "<?php echo $journal->balances; ?>";

        $("#asset").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('user.journal.asset.autocomplete', ['journal' => $journal->id]) }}",
                        type: "get",
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(el) {
                                return {
                                    thumb: el.thumb,
                                    label: el.label,
                                    value: el.value,
                                };
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    // console.log(ui.item)
                    $('#asset_id').val(ui.item.value);
                    $('#asset').val(ui.item.label);
                    $("#asset").attr("style",
                        `background-image: url('${ui.item.thumb}'); background-position: 3% 50%; padding-left: 3em; background-size: 2em; background-repeat: no-repeat; border-radius: 8px;`
                    );
                    return false;
                },


                open: function() {
                    $('ul.ui-autocomplete').hide().fadeIn("fast")
                },
                close: function() {
                    $('ul.ui-autocomplete').show().fadeOut("fast")
                }
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                // console.log(item.thumb);
                return $("<li>")
                    .append(`<div><img src="${item.thumb}" class="me-2" style="width: 15%">${item.label}</div>`)
                    .appendTo(ul);
            };

        $("#open_price").keyup(function() {
            let open_price = $("#open_price").val();
            calculateInitialMargin();
            calculateRisk();
            calculateProfit();
            calculateLoss();
            calculateRR();
        });

        $("#open_quantity").keyup(function() {
            let qty = $("#open_quantity").val();
            calculateInitialMargin();
            calculateProfit();
            calculateLoss();
            calculateRR();
        });

        $("#leverage").keyup(function() {
            let leverage = $("#leverage").val();
            if (leverage != null) {
                leverage = parseInt(leverage);
            }
            $("#leverage").text(leverage);
            calculateInitialMargin();
            calculateProfit();
            calculateLoss();
            calculateRR();
        });

        function calculateInitialMargin() {
            let leverage = $("#leverage").val();
            let open_price = $("#open_price").val();
            let qty = $("#open_quantity").val();

            let initial_margin = qty * open_price / leverage;
            let initialMarginString = '';
            if (initial_margin != null) {
                if (initial_margin > 0 && initial_margin < 1) {
                    initialMarginString = parseFloat(initial_margin);
                } else {
                    initialMarginString = parseFloat(initial_margin).toLocaleString('en-US');
                }
            }
            $("#input_initial_margin").val(initial_margin);
            $("#initial_margin").attr("value", initial_margin);
            $("#initial_margin").text(initialMarginString);
        }

        function calculateRisk() {
            let open_price = $("#open_price").val();
            let input_average_price = $("#input_average_price");
            console.log(input_average_price);
            if (input_average_price != null) {
                let average_price = $("#input_average_price").val();
                if (average_price != "") {
                    open_price == average_price;
                }
            }
            let sl1 = $("#sl1").val();
            let direction = $("#type").val();
            if (risk > 0 && leverage != "" && open_price != "" && sl1 != "" && direction != "") {
                let right_direction = false;
                if (direction == "1" && parseFloat(open_price) > parseFloat(sl1)) {
                    right_direction = true;
                } else if (direction == "0" && parseFloat(sl1) > parseFloat(open_price)) {
                    right_direction = true;
                }
                if (right_direction == true) {
                    let max_loss = risk / 100 * balances;
                    let quantity = Math.round(max_loss / Math.abs(sl1 - open_price));
                    $("#openQuantityInline").html(`Disarankan <strong>${quantity}</strong> sesuai risk ${risk}%`);
                    $("#openQuantityInline").fadeIn();
                } else {
                    $("#openQuantityInline").fadeOut();
                }
            } else {
                $("#openQuantityInline").fadeOut();
            }
        }

        $("#add_tp").click(function() {
            let ctr_tp = $('.tp_container').length + 1;
            let container = $("#tp_container");
            let new_tp = `<div class="row gx-2 gy-2 gy-md-0 mb-2 align-items-center tp_container">
                            <div class="col-sm-12 col-md-2">
                                <span class="txt">TP ${ctr_tp}</span>
                                <a href="javascript:void(0);" value="${ctr_tp}" class="text-dark delete_tp" id="delete_tp${ctr_tp}"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                    </svg></a>
                            </div>
                            <div class="col-sm-12 col-md-3"><input type="number" step="any" name="tp[]"
                                    id="tp${ctr_tp}" no="${ctr_tp}" class="form-control input_tp"></div>
                            <div class="col-sm-12 col-md-7">akan mendapatkan keuntungan <span
                                    class="text-success pnl_tp" id="pnl_tp${ctr_tp}">$0 (0%)</span></div>
                                    <input type="hidden" name="tp_pnl[]" class="tp_pnl" id="tp_pnl${ctr_tp}">
                                    <input type="hidden" name="tp_roe[]" class="tp_roe" id="tp_roe${ctr_tp}">
                        </div>`;
            container.append(new_tp);
        });

        $("#add_sl").click(function() {
            let ctr_sl = $('.sl_container').length + 1;
            let container = $("#sl_container");
            let new_sl = `<div class="row gx-2 gy-2 gy-md-0 mb-2 align-items-center sl_container">
                            <div class="col-sm-12 col-md-2">
                                <span class="txt">SL ${ctr_sl}</span>
                                <a href="javascript:void(0);" value="${ctr_sl}" class="text-dark delete_sl" id="delete_sl${ctr_sl}"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                    </svg></a>
                            </div>
                            <div class="col-sm-12 col-md-3"><input type="number" step="any" name="sl[]"
                                    id="sl${ctr_sl}" no="${ctr_sl}" class="form-control input_sl"></div>
                            <div class="col-sm-12 col-md-7">akan mendapatkan kerugian <span
                                    class="text-danger pnl_sl" id="pnl_sl${ctr_sl}">$0 (0%)</span></div>
                                    <input type="hidden" name="sl_pnl[]" class="sl_pnl" id="sl_pnl${ctr_sl}">
                                    <input type="hidden" name="sl_roe[]" class="sl_roe" id="sl_roe${ctr_sl}">
                        </div>`;
            container.append(new_sl);
        });

        function calculateProfit() {
            let initial_margin = $("#input_initial_margin").val();
            let qty = $("#open_quantity").val();
            let open_price = $("#open_price").val();
            // let average_price = $("#input_average_price").val();
            // if (average_price != "") {
            //     open_price == average_price;
            // }

            let input_tp = $(".input_tp");
            input_tp.each(function() {
                let no = $(this).attr("no");
                let tp_price = $(this).val();
                if (tp_price != "" && initial_margin != "" && qty != "" && open_price != "") {
                    let direction = $("#type").val();
                    let profit = 0;
                    if (direction == "1" && parseFloat(tp_price) > parseFloat(open_price)) {
                        profit = Math.abs((open_price - tp_price) * qty);
                    } else if (direction == "0" && parseFloat(tp_price) < parseFloat(open_price)) {
                        profit = Math.abs((tp_price - open_price) * qty);
                    }
                    let profit_percentage = (profit / initial_margin * 100);
                    let profitString = '';
                    if (profit > 0 && profit < 1) {
                        profitString = parseFloat(profit).toFixed(2);
                    } else {
                        profitString = parseFloat(profit).toFixed(2).toLocaleString('en-US');
                    }

                    $(`#pnl_tp${no}`).html(`$${profitString} (${profit_percentage.toFixed(2)}%)`);
                    $(`#tp_pnl${no}`).val(profit);
                    $(`#tp_roe${no}`).val(profit_percentage);
                }
            });
        }

        function calculateLoss() {
            let initial_margin = $("#input_initial_margin").val();
            let qty = $("#open_quantity").val();
            // let qty_remaining = $("#input_quantity_remaining").val();
            // if (qty_remaining != "") {
            //     qty = qty_remaining;
            // }
            let open_price = $("#open_price").val();
            // let average_price = $("#input_average_price").val();
            // if (average_price != "") {
            //     open_price == average_price;
            // }
            let input_sl = $(".input_sl");
            input_sl.each(function() {
                let no = $(this).attr("no");
                let sl_price = $(this).val();
                if (sl_price != "" && initial_margin != "" && qty != "" && open_price != "") {
                    let direction = $("#type").val();
                    let loss = 0;
                    if (direction == "1" && parseFloat(sl_price) < parseFloat(open_price)) {
                        loss = Math.abs((sl_price - open_price) * qty);
                    } else if (direction == "0" && parseFloat(sl_price) > parseFloat(open_price)) {
                        loss = Math.abs((open_price - sl_price) * qty);
                    }
                    let loss_percentage = (loss / initial_margin * 100);

                    let lossString = '';
                    if (loss > 0 && loss < 1) {
                        lossString = parseFloat(loss).toFixed(2);
                    } else {
                        lossString = parseFloat(loss).toFixed(2).toLocaleString('en-US');
                    }

                    $(`#pnl_sl${no}`).html(`$${lossString} (${loss_percentage.toFixed(2)}%)`);
                    $(`#sl_pnl${no}`).val(loss);
                    $(`#sl_roe${no}`).val(loss_percentage);
                }
            });
        }

        function calculateRR() {
            let tp1_pnl = $("#tp_pnl1").val();
            let sl1_pnl = $("#sl_pnl1").val();

            if (tp1_pnl != "" && sl1_pnl != "") {
                let rr = (parseFloat(tp1_pnl) / parseFloat(sl1_pnl));
                $("#rr_expected").html(rr.toFixed(2));
                $("#input_rr_expected").val(rr);
            }
        }

        function datetimeLocal(datetime) {
            const dt = new Date(datetime);
            dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
            return dt.toISOString().slice(0, 16);
        }

        $(function() {
            $(document).on('click', '.delete_tp', function() {
                let value = $(this).attr("value");
                // console.log("Value : ", value);
                let parent = $(this).parent().parent();
                let siblings = parent.nextAll();
                siblings.each(function(index) {
                    // console.log($(this).find('.txt'));
                    $(this).find('.txt').text(`TP ${value}`);
                    $(this).find('.delete_tp').attr('value', value);
                    $(this).find('.delete_tp').attr('id', `delete_tp${value}`);
                    $(this).find('.input_tp').attr('id', `tp${value}`);
                    $(this).find('.input_tp').attr('no', value);
                    $(this).find('.pnl_tp').attr('id', `pnl_tp${value}`);
                    $(this).find('.tp_pnl').attr('id', `tp_pnl${value}`);
                    $(this).find('.tp_roe').attr('id', `tp_roe${value}`);
                    value++;
                })
                parent.remove();
            });

            $(document).on('click', '.delete_sl', function() {
                let value = $(this).attr("value");
                // console.log("Value : ", value);
                let parent = $(this).parent().parent();
                let siblings = parent.nextAll();
                siblings.each(function(index) {
                    // console.log($(this).find('.txt'));
                    $(this).find('.txt').text(`SL ${value}`);
                    $(this).find('.delete_sl').attr('value', value);
                    $(this).find('.delete_sl').attr('id', `delete_sl${value}`);
                    $(this).find('.input_sl').attr('id', `sl${value}`);
                    $(this).find('.input_sl').attr('no', value);
                    $(this).find('.pnl_sl').attr('id', `pnl_sl${value}`);
                    $(this).find('.sl_pnl').attr('id', `sl_pnl${value}`);
                    $(this).find('.sl_roe').attr('id', `sl_roe${value}`);
                    value++;
                })
                parent.remove();
            });

            $(document).on('keyup', '.input_sl', function() {

                let open_price = $("#open_price").val();
                let this_input = $(this);
                let no = this_input.attr("no");
                if (no == "1") {
                    calculateRisk(open_price, this_input.val());
                }

                calculateLoss();
                calculateRR();
            });

            $(document).on('keyup', '.input_tp', function() {
                calculateProfit();
                calculateRR();
            });

            $(document).on('change', '#type', function() {
                calculateProfit();
                calculateLoss();
                calculateRisk();
                calculateRR();
            });

            $(document).on('keypress keyup', '.tv_input', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13 || e.which == 13) {
                    e.preventDefault();
                    let this_input = $(this);
                    if (this_input.val() != "") {
                        let img_input_container = this_input.closest('.img-input');
                        let img_wrap_container = $(img_input_container).prev();
                        let img = img_wrap_container.find('img');
                        img.attr("src", this_input.val());
                        $(img_input_container).fadeOut();
                        img_wrap_container.fadeIn();
                    }
                }
            });

            $(document).on('change', '.img_input', function(e) {
                let this_input = $(this);
                if (this_input.length > 0) {
                    this_input = this_input[0];
                }
                if (this_input.files.length > 0) {
                    let img_input_container = this_input.closest('.img-input');
                    let img_wrap_container = $(img_input_container).prev();
                    let img = img_wrap_container.find('img');
                    let src = URL.createObjectURL(this_input.files[0]);
                    img.attr("src", src);
                    $(img_input_container).fadeOut();
                    img_wrap_container.fadeIn();
                }
            });

            $(document).on('click', '.strategy-delete', function() {
                let this_input = $(this);
                let img_wrap_container = this_input.closest('.img-wrap');
                let img = img_wrap_container.find('img');
                let img_input_container = img_wrap_container.next();
                let img_input = img_input_container.find('.img_input');
                let tv_input = img_input_container.find('.tv_input');
                let empty_image = '{{ URL::asset('/images/no-image.webp') }}'
                img_input.val("");
                tv_input.val("");
                img_wrap_container.fadeOut(function() {
                    img.attr("src", empty_image);
                });
                img_input_container.fadeIn();
            });

            $(document).on('click', '.screenshot-delete', function() {
                let this_input = $(this);
                let img_wrap_container = this_input.closest('.img-wrap');
                let img = img_wrap_container.find('img');
                let img_input_container = img_wrap_container.next();
                let img_input = img_input_container.find('.img_input');
                let hidden_img_input = img_input_container.find('.hidden_img_input');
                let empty_image = '{{ URL::asset('/images/no-image.webp') }}'
                img_input.val("");
                hidden_img_input.val("http://127.0.0.1:8000/storage/uploads");
                img_wrap_container.fadeOut(function() {
                    img.attr("src", empty_image);
                });
                img_input_container.fadeIn();
            });

            $('#timeframe').select2();
            $('#entry_strategy').select2();
            $('#fibonacci_strategy').select2();
            $('#candlestick_strategy').select2();
            $('#chart_strategy').select2();
            $('#indicator_strategy').select2();

            $('#timeframe').on('select2:select', function(e) {
                var data = e.params.data;

                let container = $("#strategy_screenshot");
                let html = `<div class="col mt-0 mb-3 text-dark" id="${data.text}_container" style="order: ${data.id}">
                                    <strong>${data.text}</strong>
                                    <div class="img-wrap" style="display: none">
                                        <a href="javascript:void(0);" class="img-delete text-danger strategy-delete"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                            alt="">
                                    </div>
                                    <div class="img-input" style="display: block">
                                        <div class="input-group py-2">
                                            <input type="file" name="ss[]" id="img_${data.text}" tf="${data.text}" class="img_input">
                                            <label for="img_${data.text}">Pilih gambar...</label> /
                                        </div>
                                        <input type="url" name="tv[]" id="img_${data.text}" class="form-control tv_input"
                                            placeholder="Isi URL Trading View Disini..">
                                    </div>
                                </div>`;
                if (container.find(`#${data.text}_container`).length == 0) {
                    container.append(html);
                }
            });

            $('#timeframe').on('select2:unselect', function(e) {
                var data = e.params.data;

                let container = $("#strategy_screenshot");
                if (container.find(`#${data.text}_container`).length > 0) {
                    let child_container = container.find(`#${data.text}_container`);
                    child_container.remove();
                }
            });

            let transactions_table = $("#transactions-list-table").DataTable({
                "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                    "destroy": true,
                }
            });

            $('#tambahTransaksiModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('transaction-id');

                var modal = $(this);
                if (recipient != null) {
                    console.log(recipient);
                    var dt = Date.parse(recipient.time);
                    console.log("Date Time : ", dt);

                    modal.find('.modal-title').text('Ubah Transaksi');
                    $("#transaction_id").val(recipient.id);
                    $("#transaction_price").val(recipient.price);
                    $("#transaction_quantity").val(Math.abs(recipient.quantity));
                    $("#transaction_fee").val(recipient.fee);
                    $("#transaction_type").val(recipient.type.toString()).change();
                    $("#transaction_time").val(datetimeLocal(dt));

                } else {
                    modal.find('.modal-title').text('Tambah Transaksi');
                    $("#transaction_id").val(null);
                    $("#transaction_price").val(null);
                    $("#transaction_quantity").val(null);
                    $("#transaction_fee").val(null);
                    $("#transaction_type").val("0").change();
                    $("#transaction_time").val(null);
                }
            });
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    <x-back-button>{{ route('user.journal.detail', ['journal' => $journal->id]) }}</x-back-button>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title d-flex align-items-center">
                                <span class="me-2">Detail Catatan</span>
                                @if ($trade->status == 0)
                                    <span class="badge bg-secondary rounded-pill">Pending</span>
                                @elseif($trade->status == 1)
                                    <span class="badge bg-primary rounded-pill">Aktif</span>
                                @elseif($trade->status == 2)
                                    <span class="badge bg-primary rounded-pill ms-2">Selesai</span>
                                    @if($trade->wl == 1)
                                    <span class="badge bg-success rounded-pill ms-2">Win (Menang)</span>
                                    @elseif($trade->wl == -1)
                                    <span class="badge bg-danger rounded-pill ms-2">Loss (Kalah)</span>
                                    @endif
                                @endif
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <h6 class="text-muted"><strong>Informasi Koin</strong></h6>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group row gx-1">
                                        <label for="asset" class="col-2 col-form-label text-dark">Koin</label>
                                        <div class="col-10">
                                            <div class="ui-widget">
                                                <input type="hidden" name="asset_id" id="asset_id"
                                                    class="form-control" value="{{ $trade->asset->id }}"
                                                    @if ($trade->status != 0) readonly="readonly" @endif />
                                                <input type="text" name="asset" id="asset" class="form-control"
                                                    value="{{ $trade->asset->name }} ({{ $trade->asset->symbol }})"
                                                    style="background-image: url('{{ $trade->asset->thumb }}'); background-position: 3% 50%; padding-left: 3em; background-size: 2em; background-repeat: no-repeat; border-radius: 8px;"
                                                    @if ($trade->status != 0) readonly="readonly" @endif />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group row gx-1">
                                        <label for="type" class="col-3 col-form-label text-dark">Tipe</label>
                                        <div class="col-9">
                                            <select name="type" id="type" class="form-control"
                                                @if ($trade->status != 0) readonly="readonly" @endif>
                                                <option value="1"
                                                    @if ($trade->type == 1) selected @endif>LONG</option>
                                                <option value="0"
                                                    @if ($trade->type == 0) selected @endif>SHORT</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group row gx-1">
                                        <label for="leverage" class="col-6 col-form-label text-dark">Leverage</label>
                                        <div class="col-6">
                                            <input type="number" min="1" step="1" name="leverage" id="leverage"
                                                class="form-control" value="{{ $trade->leverage }}"
                                                @if ($trade->status != 0) readonly="readonly" @endif>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group row gx-1">
                                        <label for="open_price" class="col-4 col-form-label text-dark">Harga
                                            Entri</label>
                                        <div class="col-8">
                                            <input type="number" min="0" step="any" name="open_price" id="open_price"
                                                class="form-control" value="{{ (float) $trade->open_price }}"
                                                @if ($trade->status != 0) readonly="readonly" @endif>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group row gx-1">
                                        <label for="open_quantity"
                                            class="col-3 col-md-2 col-form-label text-dark">Jumlah</label>
                                        <div class="col-9 col-md-5">
                                            <input type="number" min="0" step="any" name="open_quantity" id="open_quantity"
                                                class="form-control" value="{{ (float) $trade->open_quantity }}"
                                                @if ($trade->status != 0) readonly="readonly" @endif>
                                        </div>
                                        <span id="openQuantityInline"
                                            class="col-12 col-md-5 col-form-label form-text text-center"
                                            style="display: none;"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group row gx-1">
                                        <div class="col-12 col-form-label text-dark">Margin Awal: $<span
                                                id="initial_margin">{{ (float) $trade->open_margin }}</span></div>
                                        <input type="hidden" name="initial_margin" id="input_initial_margin"
                                            value="{{ (float) $trade->open_margin }}"
                                            @if ($trade->status != 0) readonly="readonly" @endif>
                                    </div>
                                </div>
                            </div>
                            @if ($trade->status != 0)
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        Harga Entri Rata-Rata: $<span
                                            id="average_price">{{ (float) $trade->average_price }}</span>
                                        <input type="hidden" name="average_price" id="input_average_price"
                                            value="{{ $trade->average_price }}">
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        Sisa Jumlah: <span
                                            id="quantity_remaining">{{ (float) $trade->quantity_remaining }}</span>
                                        <input type="hidden" name="quantity_remaining" id="input_quantity_remaining"
                                            value="{{ $trade->quantity_remaining }}">
                                    </div>
                                </div>
                            @endif
                            <hr />
                            <h6 class="text-muted mb-3"><strong>Informasi TP & SL</strong></h6>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 justify-content-start align-items-start">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span>Harga TP</span>
                                        <button id="add_tp" type="button" class="btn btn-sm btn-primary" @if($trade->status == 2) disabled @endif>+ Tambah
                                            Harga TP</button>
                                    </div>
                                    <div id="tp_container" class="h-100">
                                        @foreach ($trade->targets->where('type', 1) as $target)
                                            <div class="row gx-2 gy-2 gy-md-0 mb-2 align-items-center tp_container">
                                                <div class="col-sm-12 col-md-2">
                                                    <span class="txt">TP {{ $loop->iteration }}</span>
                                                    @if ($loop->iteration > 1 and $trade->status < 2)
                                                        <a href="javascript:void(0);" value="{{ $loop->iteration }}"
                                                            class="text-dark delete_tp"
                                                            id="delete_tp{{ $loop->iteration }}"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="18"
                                                                height="18" fill="currentColor"
                                                                class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                            </svg></a>
                                                    @endif
                                                </div>
                                                <div class="col-sm-12 col-md-3"><input type="number" step="any"
                                                        name="tp[]" id="tp{{ $loop->iteration }}"
                                                        no="{{ $loop->iteration }}" class="form-control input_tp"
                                                        value="{{ (float) $target->price }}" @if ($trade->status == 2) readonly="readonly" @endif></div>
                                                <div class="col-sm-12 col-md-7">akan mendapatkan keuntungan <span
                                                        class="text-success pnl_tp"
                                                        id="pnl_tp{{ $loop->iteration }}">${{ number_format((float) $target->pnl, 2) }}
                                                        ({{ number_format((float) $target->roe, 2) }}%)
                                                    </span></div>
                                                <input type="hidden" class="tp_pnl" name="tp_pnl[]"
                                                    id="tp_pnl{{ $loop->iteration }}"
                                                    value="{{ (float) $target->pnl }}">
                                                <input type="hidden" class="tp_roe" name="tp_roe[]"
                                                    id="tp_roe{{ $loop->iteration }}"
                                                    value="{{ (float) $target->roe }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 justify-content-start align-items-start">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span>Harga SL</span>
                                        <button id="add_sl" type="button" class="btn btn-sm btn-primary" @if($trade->status == 2) disabled @endif>+ Tambah
                                            Harga SL</button>
                                    </div>
                                    <div id="sl_container" class="h-100">
                                        @foreach ($trade->targets->where('type', 0) as $target)
                                            <div class="row gx-2 gy-2 gy-md-0 align-items-center mb-2 sl_container"
                                                no="1">
                                                <div class="col-sm-12 col-md-2">
                                                    <span class="txt">SL {{ $loop->iteration }}</span>
                                                    @if ($loop->iteration > 1 and $trade->status < 2)
                                                        <a href="javascript:void(0);" value="{{ $loop->iteration }}"
                                                            class="text-dark delete_sl"
                                                            id="delete_sl{{ $loop->iteration }}"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="18"
                                                                height="18" fill="currentColor"
                                                                class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                            </svg></a>
                                                    @endif
                                                </div>
                                                <div class="col-sm-12 col-md-3"><input type="number" step="any"
                                                        name="sl[]" id="sl{{ $loop->iteration }}"
                                                        no="{{ $loop->iteration }}" class="form-control input_sl"
                                                        value="{{ (float) $target->price }}" @if ($trade->status == 2) readonly="readonly" @endif></div>
                                                <div class="col-sm-12 col-md-7">akan mendapatkan kerugian <span
                                                        class="text-danger pnl_sl"
                                                        id="pnl_sl1">${{ number_format((float) $target->pnl, 2) }}
                                                        ({{ number_format((float) $target->roe, 2) }}%)
                                                    </span></div>
                                                <input type="hidden" class="sl_pnl" name="sl_pnl[]"
                                                    id="sl_pnl{{ $loop->iteration }}"
                                                    value="{{ (float) $target->pnl }}">
                                                <input type="hidden" class="sl_roe" name="sl_roe[]"
                                                    id="sl_roe{{ $loop->iteration }}"
                                                    value="{{ (float) $target->roe }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                Risk Ratio Terdekat: <span id="rr_expected">{{ $trade->rr_expected }}</span>
                                <input type="hidden" name="rr_expected" id="input_rr_expected"
                                    value="{{ $trade->rr_expected }}">
                            </div>
                            <hr />
                            <h6 class="text-muted mb-3"><strong>Analisa dan Strategi yang Digunakan</strong></h6>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group row gx-1">
                                        <label for="timeframe"
                                            class="col-4 col-md-3 col-form-label text-dark">Timeframe</label>
                                        <div class="col-8 col-md-9">
                                            <select name="timeframe[]" id="timeframe" class="form-control"
                                                multiple="multiple">
                                                @foreach ($timeframes as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        @if ($trade->timeframes->find($value->id) != null) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <div class="form-group row gx-1">
                                        <label for="entry_strategy"
                                            class="col-4 col-md-2 col-form-label text-dark">Strategi Entry</label>
                                        <div class="col-8 col-md-10">
                                            <select name="entry_strategy[]" id="entry_strategy" class="form-control"
                                                multiple="multiple">
                                                @foreach ($entry_strategies as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        @if ($trade->strategies->find($value->id) != null) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group row gx-1">
                                        <label for="fibonacci_strategy"
                                            class="col-4 col-md-2 col-form-label text-dark">Fibonacci</label>
                                        <div class="col-8 col-md-10">
                                            <select name="fibonacci_strategy[]" id="fibonacci_strategy"
                                                class="form-control" multiple="multiple">
                                                @foreach ($fibonacci_strategies as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        @if ($trade->strategies->find($value->id) != null) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group row gx-1">
                                        <label for="candlestick_strategy"
                                            class="col-4 col-md-2 col-form-label text-dark text-start text-md-end">Candlestick</label>
                                        <div class="col-8 col-md-10">
                                            <select name="candlestick_strategy[]" id="candlestick_strategy"
                                                class="form-control" multiple="multiple">
                                                @foreach ($candlestick_strategies as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        @if ($trade->strategies->find($value->id) != null) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group row gx-1">
                                        <label for="chart_strategy"
                                            class="col-3 col-md-2 col-form-label text-dark">Chart</label>
                                        <div class="col-9 col-md-10">
                                            <select name="chart_strategy[]" id="chart_strategy" class="form-control"
                                                multiple="multiple">
                                                @foreach ($chart_strategies as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        @if ($trade->strategies->find($value->id) != null) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group row gx-1">
                                        <label for="indicator_strategy"
                                            class="col-3 col-md-2 col-form-label text-dark text-start text-md-end">Indikator</label>
                                        <div class="col-9 col-md-10">
                                            <select name="indicator_strategy[]" id="indicator_strategy"
                                                class="form-control" multiple="multiple">
                                                @foreach ($indicator_strategies as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        @if ($trade->strategies->find($value->id) != null) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4" id="strategy_screenshot">
                                @foreach ($trade->timeframes as $timeframe)
                                    <div class="col mt-0 mb-3 text-dark" id="{{ $timeframe->name }}_container"
                                        style="order: {{ $timeframe->id }}">
                                        <strong>{{ $timeframe->name }}</strong>
                                        <div class="img-wrap" style="display: block">
                                            <a href="javascript:void(0);"
                                                class="img-delete text-danger strategy-delete"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                </svg></a>
                                            @if ($timeframe->pivot->picture_type == 0)
                                                <img src="{{ $timeframe->pivot->url_picture }}" class="img-fluid"
                                                    alt="">
                                            @elseif($timeframe->pivot->picture_type == 1)
                                                <img src="{{ asset('storage/uploads/' . $timeframe->pivot->url_picture) }}"
                                                    class="img-fluid" alt="">
                                            @endif
                                        </div>
                                        <div class="img-input" style="display: none">
                                            <div class="input-group py-2">
                                                <input type="file" name="ss[]" id="img_{{ $timeframe->name }}"
                                                    tf="{{ $timeframe->name }}" class="img_input"
                                                    @if ($timeframe->pivot->picture_type == 1) value="{{ URL::asset('storage/uploads/' . $timeframe->pivot->url_picture) }}" @endif>
                                                <label for="img_{{ $timeframe->name }}">Pilih gambar...</label> /
                                            </div>
                                            <input type="url" name="tv[]" id="img_{{ $timeframe->name }}"
                                                class="form-control tv_input"
                                                placeholder="Isi URL Trading View Disini.."
                                                @if ($timeframe->pivot->picture_type == 0) value="{{ $timeframe->pivot->url_picture }}" @endif>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="notes" class="text-dark">Catatan</label>
                                <textarea name="notes" id="notes" class="form-control" style="resize: none; height: 200px"></textarea>
                            </div>
                            <hr />
                            <div>
                                <div class="d-flex align-items-start justify-content-between text-dark mb-3">
                                    <h6 class="text-muted"><strong>Transaksi</strong></h6>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#tambahTransaksiModal">+ Tambah
                                        Transaksi</button>
                                </div>
                            </div>
                            <div id="table-container" class="table-responsive mb-3">
                                <table id="transactions-list-table" class="table table-striped table-hover"
                                    role="grid" data-toggle="data-table">
                                    <thead>
                                        <tr class="light">
                                            <th>#</th>
                                            <th>Tipe</th>
                                            <th>Harga</th>
                                            <th>Kuantitas</th>
                                            <th>Waktu</th>
                                            <th>Biaya Tambahan</th>
                                            <th>Keuntungan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trade->transactions as $transaction)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($transaction->type == 0)
                                                        Entri
                                                    @elseif($transaction->type == 1)
                                                        Tutup
                                                    @endif
                                                </td>
                                                <td>${{ number_format((float) $transaction->price, 2) }}</td>
                                                @if ($transaction->type == 0)
                                                    <td>+{{ number_format((float) $transaction->quantity, 2) }}</td>
                                                @elseif($transaction->type == 1)
                                                    <td>{{ number_format((float) $transaction->quantity, 2) }}</td>
                                                @endif
                                                <td>{{ $transaction->time }}</td>
                                                <td>{{ $transaction->fee == '' ? '-' : '$' . (float) $transaction->fee }}
                                                </td>
                                                @if ($transaction->pnl > 0)
                                                    <td class="text-success">
                                                        ${{ number_format((float) $transaction->pnl, 2) }}</td>
                                                @elseif($transaction->pnl < 0)
                                                    <td class="text-danger">
                                                        -${{ abs(number_format((float) $transaction->pnl, 2)) }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <td>
                                                    <div class="flex align-items-center list-asset-transaction-action">
                                                        <button type="button" class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-bs-toggle="modal" data-bs-target="#tambahTransaksiModal" data-transaction-id="{{json_encode($transaction)}}">
                                                            <span class="btn-inner">
                                                                <svg width="20" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        <a href="{{route('user.journal.trade.transaction.delete', ['journal' => $journal->id, 'trade' => $trade->id, 'trade_transaction' => $transaction->id])}}" class="btn btn-sm btn-icon btn-danger btn-delete"
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
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M20.708 6.23975H3.75"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path
                                                                        d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($trade->status != 0)
                                <div class="row mb-2">
                                    <div class="col-sm-12 col-md-4">
                                        Total Keuntungan:
                                        @if ($trade->pnl > 0)
                                        <span id="total_pnl" class="text-success ms-2"> ${{ number_format((float) $trade->pnl, 2) }}</span>
                                        @elseif($trade->pnl < 0)
                                        <span id="total_pnl" class="text-danger ms-2"> -${{ abs(number_format((float) $trade->pnl, 2)) }}</span>
                                        @else
                                        <span>-</span>
                                        @endif
                                    </span>
                                    </div>
                                    <div class="col-sm-12 col-md-4 d-flex justify-content-start justify-content-md-center">
                                        Total Biaya Tambahan:
                                        @if ($trade->total_fees > 0)
                                        <span id="total_fees" class="ms-2"> ${{ number_format((float) $trade->total_fees, 2) }}</span>
                                        @else
                                        <span>-</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-12 col-md-4 d-flex justify-content-start justify-content-md-end">
                                        Keuntungan Bersih:
                                        @if ($trade->nett_pnl > 0)
                                        <span id="nett_pnl" class="text-success ms-2"> ${{ number_format((float) $trade->nett_pnl, 2) }} ({{number_format((float) $trade->roe, 2)}}%)</span>
                                        @elseif($trade->nett_pnl < 0)
                                        <span id="nett_pnl" class="text-danger ms-2"> -${{ abs(number_format((float) $trade->nett_pnl, 2))}} (-{{abs(number_format((float) $trade->roe, 2))}}%)</span>
                                        @else
                                        <span>-</span>
                                        @endif
                                    </div>
                                </div>
                                @if ($trade->status == 2)
                                <div class="row">
                                    <div class="col-sm-12 col-md-4">
                                        Durasi: {{$trade->diff_days > 0 ? $trade->diff_days . ' hari ' : ''}}  {{$trade->diff_hours > 0 ? $trade->diff_hours . ' jam ' : ''}} {{$trade->diff_minutes > 0 ? $trade->diff_minutes . ' menit ' : ''}} {{$trade->diff_seconds > 0 ? $trade->diff_seconds . ' detik ' : ''}}
                                    </span>
                                    </div>
                                    <div class="col-sm-12 col-md-4 d-flex justify-content-start justify-content-md-center">
                                        Risk Ratio Ril: {{$trade->real_rr}}
                                    </div>
                                    <div class="col-sm-12 col-md-4 d-flex justify-content-start justify-content-md-end">
                                        Tutup Memenuhi: @if(str_contains($trade->closed_at, 'TP')) <span class="text-success ms-2">{{$trade->closed_at}}</span> @elseif(str_contains($trade->closed_at, 'SL')) <span class="text-danger ms-2">{{$trade->closed_at}}</span> @else - @endif
                                    </div>
                                </div>
                                @endif
                            @endif
                            <hr />
                            <h6 class="text-muted mb-2"><strong>Arsip Screenshot</strong></h6>
                            <div class="row row-cols-1 row-cols-md-2 gx-3 ps-0 pe-0" id="pnl_screenshot">
                                <div class="col mt-0 mb-3 text-dark">
                                    <div class="img-wrap"
                                        style="@if ($trade->screenshot_url_1 == '') display:none @endif">
                                        <a href="javascript:void(0);"
                                            class="img-delete text-danger screenshot-delete"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="@if ($trade->screenshot_url_1 == '') {{asset('images/no-image.webp')}} @else {{asset('storage/uploads/' . $trade->screenshot_url_1)}} @endif"
                                            class="img-fluid" alt="">
                                    </div>
                                    <div class="img-input"
                                        style="@if ($trade->screenshot_url_1 != '') display:none @endif">
                                        <div class="input-group py-2">
                                            <input type="hidden" name="hidden_img[]" class="hidden_img_input" value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_1) }}">
                                            <input type="file" name="img[]" id="img_1" class="img_input"
                                                value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_1) }}">
                                            <label for="img_1">Pilih gambar...</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mt-0 mb-3 text-dark">
                                    <div class="img-wrap"
                                        style="@if ($trade->screenshot_url_2 == '') display:none @endif">
                                        <a href="javascript:void(0);"
                                            class="img-delete text-danger screenshot-delete"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="@if ($trade->screenshot_url_2 == '') {{asset('images/no-image.webp')}} @else {{asset('storage/uploads/' . $trade->screenshot_url_2)}} @endif"
                                            class="img-fluid" alt="">
                                    </div>
                                    <div class="img-input"
                                        style="@if ($trade->screenshot_url_2 != '') display:none @endif">
                                        <div class="input-group py-2">
                                            <input type="hidden" name="hidden_img[]" class="hidden_img_input" value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_2) }}">
                                            <input type="file" name="img[]" id="img_2" class="img_input"
                                                value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_2) }}">
                                            <label for="img_2">Pilih gambar...</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mt-0 mb-3 text-dark">
                                    <div class="img-wrap"
                                        style="@if ($trade->screenshot_url_3 == '') display:none @endif">
                                        <a href="javascript:void(0);"
                                            class="img-delete text-danger screenshot-delete"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="@if ($trade->screenshot_url_3 == '') {{asset('images/no-image.webp')}} @else {{asset('storage/uploads/' . $trade->screenshot_url_3)}} @endif"
                                            class="img-fluid" alt="">
                                    </div>
                                    <div class="img-input"
                                        style="@if ($trade->screenshot_url_3 != '') display:none @endif">
                                        <div class="input-group py-2">
                                            <input type="hidden" name="hidden_img[]" class="hidden_img_input" value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_3) }}">
                                            <input type="file" name="img[]" id="img_3" class="img_input"
                                                value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_3) }}">
                                            <label for="img_3">Pilih gambar...</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mt-0 mb-3 text-dark">
                                    <div class="img-wrap"
                                        style="@if ($trade->screenshot_url_4 == '') display:none @endif">
                                        <a href="javascript:void(0);"
                                            class="img-delete text-danger screenshot-delete"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="@if ($trade->screenshot_url_4 == '') {{asset('images/no-image.webp')}} @else {{asset('storage/uploads/' . $trade->screenshot_url_4)}} @endif"
                                            class="img-fluid" alt="">
                                    </div>
                                    <div class="img-input"
                                        style="@if ($trade->screenshot_url_4 != '') display:none @endif">
                                        <div class="input-group py-2">
                                            <input type="hidden" name="hidden_img[]" class="hidden_img_input" value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_4) }}">
                                            <input type="file" name="img[]" id="img_4" class="img_input"
                                                value="{{ URL::asset('storage/uploads/' . $trade->screenshot_url_4) }}">
                                            <label for="img_4">Pilih gambar...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-pill"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-save me-1" viewBox="0 0 16 16">
                                        <path
                                            d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z" />
                                    </svg> Simpan Perubahan</button>
                            </div>
                        </form>
                        <div class="modal fade" id="tambahTransaksiModal" tabindex="-1"
                            aria-labelledby="tambahTransaksiLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tambahTransaksiTitle">Tambah Transaksi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form
                                            action="{{ route('user.journal.trade.transaction.store', ['journal' => $journal->id, 'trade' => $trade->id]) }}"
                                            id="transaction_form" method="POST">
                                            @csrf
                                            <input type="hidden" name="transaction_id" id="transaction_id">
                                            <div class="form-group form-group-alt mb-2">
                                                <label for="transaction_price"
                                                    class="form-label text-dark">Harga</label>
                                                <input type="text" class="form-control" name="transaction_price"
                                                    id="transaction_price">
                                            </div>
                                            <div class="form-group form-group-alt mb-2">
                                                <label for="transaction_quantity"
                                                    class="form-label text-dark">Kuantitas</label>
                                                <input type="text" name="transaction_quantity"
                                                    class="form-control" aria-label="Jumlah Koin"
                                                    aria-describedby="basic-addon2" id="transaction_quantity">
                                            </div>
                                            <div class="form-group form-group-alt row gx-2 gy-0">
                                                <div class="col-sm-12 col-md-6">
                                                    <label for="transaction_fee" class="form-label text-dark">Biaya
                                                        Tambahan</label>
                                                    <input type="text" class="form-control" name="transaction_fee"
                                                        id="transaction_fee">
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label for="transaction_type"
                                                        class="form-label text-dark">Tipe</label>
                                                    <select name="transaction_type" id="transaction_type"
                                                        class="form-control">
                                                        <option value="0" selected>Entri</option>
                                                        <option value="1">Tutup</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-alt">
                                                <label for="transaction_time"
                                                    class="form-label text-dark">Waktu</label>
                                                <input type="datetime-local" id="transaction_time"
                                                    name="transaction_time" class="form-control">
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button type="reset" class="btn btn-danger"
                                                    id="transaction_reset">Reset</button>
                                                <button type="submit" id="transaction_submit"
                                                    class="btn btn-primary">Kumpul</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

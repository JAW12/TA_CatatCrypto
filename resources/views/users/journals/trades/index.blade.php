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

        .img-wrap .delete {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 100;
        }

        .img-wrap .delete:hover {
            filter: brightness(85%);
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.min.js"
        integrity="sha512-6j+LxzZ7EO1Kr7H5yfJ8VYCVZufCBMNFhSMMzb2JRhlwQ/Ri7Zv8VfJ7YI//cg9H5uXT2lQpb14YMvqUAdGlcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
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
            var open_price = $("#open_price").val();
            let avgPriceString = '';
            if (open_price != null) {
                if (open_price > 0 && open_price < 1) {
                    avgPriceString = parseFloat(open_price);
                } else {
                    avgPriceString = parseFloat(open_price).toLocaleString('en-US');
                }
            }
            $("#average_price").text(avgPriceString);
            calculateInitialMargin();
            calculateRisk();
            calculateProfit();
            calculateLoss();
            calculateRR();
        });

        $("#open_quantity").keyup(function() {
            var qty = $("#open_quantity").val();
            let quantityString = '';
            if (qty != null) {
                if (qty > 0 && qty < 1) {
                    quantityString = parseFloat(qty);
                } else {
                    quantityString = parseFloat(qty).toLocaleString('en-US');
                }
            }
            $("#quantity_remaining").text(quantityString);
            calculateInitialMargin();
            calculateProfit();
            calculateLoss();
            calculateRR();
        });

        $("#lev").keyup(function() {
            var lev = $("#lev").val();
            if (lev != null) {
                lev = parseInt(lev);
            }
            $("#lev").text(lev);
            calculateInitialMargin();
            calculateProfit();
            calculateLoss();
            calculateRR();
        });

        function calculateInitialMargin() {
            var lev = $("#lev").val();
            var open_price = $("#open_price").val();
            var qty = $("#open_quantity").val();

            let initial_margin = qty * open_price / lev;
            let initialMarginString = '';
            if (initial_margin != null) {
                if (initial_margin > 0 && initial_margin < 1) {
                    initialMarginString = parseFloat(initial_margin);
                } else {
                    initialMarginString = parseFloat(initial_margin).toLocaleString('en-US');
                }
            }
            $("#initial_margin").attr("value", initial_margin);
            $("#initial_margin").text(initialMarginString);
        }

        function calculateRisk(){
            var open_price = $("#open_price").val();
            var sl1 = $("#sl1").val();
            let direction = $("#type").val();
            if(risk > 0 && lev != "" && open_price != "" && sl1 != "" && direction != ""){
                var right_direction = false;
                if(direction == "1" && parseFloat(open_price) > parseFloat(sl1)){
                    right_direction = true;
                }
                else if (direction == "0" && parseFloat(sl1) > parseFloat(open_price)){
                    right_direction = true;
                }
                if(right_direction == true){
                    let max_loss = risk / 100 * balances;
                    let quantity = max_loss / Math.abs(sl1 - open_price);
                    $("#openQuantityInline").html(`Disarankan <strong>${quantity}</strong> sesuai risk ${risk}%`);
                    $("#openQuantityInline").fadeIn();
                }
                else{
                    $("#openQuantityInline").fadeOut();
                }
            }
            else{
                $("#openQuantityInline").fadeOut();
            }
        }

        $("#add_tp").click(function() {
            let ctr_tp = $('.tp_container').length + 1;
            let container = $("#tp_container");
            let new_tp = `<div class="row gx-2 gy-2 gy-md-0 mb-2 align-items-center tp_container" no="${ctr_tp}">
                            <div class="col-sm-12 col-md-2">
                                <span class="txt">TP ${ctr_tp}</span>
                                <a href="#" value="${ctr_tp}" class="text-dark delete_tp" id="delete_tp${ctr_tp}"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                    </svg></a>
                            </div>
                            <div class="col-sm-12 col-md-3"><input type="number" name="tp[]"
                                    id="tp${ctr_tp}" no="${ctr_tp}" class="form-control input_tp"></div>
                            <div class="col-sm-12 col-md-7">akan mendapatkan keuntungan <span
                                    class="text-success pnl_tp" id="pnl_tp${ctr_tp}">$0 (0%)</span></div>
                        </div>`;
            container.append(new_tp);
        });

        $("#add_sl").click(function() {
            let ctr_sl = $('.sl_container').length + 1;
            let container = $("#sl_container");
            let new_sl = `<div class="row gx-2 gy-2 gy-md-0 mb-2 align-items-center sl_container" no="${ctr_sl}">
                            <div class="col-sm-12 col-md-2">
                                <span class="txt">SL ${ctr_sl}</span>
                                <a href="#" value="${ctr_sl}" class="text-dark delete_sl" id="delete_sl${ctr_sl}"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                    </svg></a>
                            </div>
                            <div class="col-sm-12 col-md-3"><input type="number" name="sl[]"
                                    id="sl${ctr_sl}" no="${ctr_sl}" class="form-control input_sl"></div>
                            <div class="col-sm-12 col-md-7">akan mendapatkan kerugian <span
                                    class="text-danger pnl_sl" id="pnl_sl${ctr_sl}">$0 (0%)</span></div>
                        </div>`;
            container.append(new_sl);
        });

        function calculateProfit(){
            let initial_margin = $("#initial_margin").attr("value");
            let qty = $("#open_quantity").val();
            let open_price = $("#open_price").val();

            let input_tp = $(".input_tp");
            input_tp.each(function(){
                let no = $(this).attr("no");
                let tp_price = $(this).val();
                if(tp_price != "" && initial_margin != "" && qty != "" && open_price != ""){
                    let direction = $("#type").val();
                    let profit = 0;
                    if(direction == "1" && parseFloat(tp_price) > parseFloat(open_price)){
                        profit = Math.abs((open_price - tp_price) * qty);
                    }
                    else if(direction == "0" && parseFloat(tp_price) < parseFloat(open_price)){
                        profit = Math.abs((tp_price - open_price) * qty);
                    }
                    let profit_percentage = profit / initial_margin * 100;

                    let profitString = '';
                    if (profit > 0 && profit < 1) {
                        profitString = parseFloat(profit);
                    } else {
                        profitString = parseFloat(profit).toLocaleString('en-US');
                    }

                    $(`#pnl_tp${no}`).html(`$${profitString} (${profit_percentage}%)`);
                    $(this).attr("pnl", profit);
                    $(this).attr("pnl_percentage", profit_percentage);
                }
            });
        }

        function calculateLoss(){
            let initial_margin = $("#initial_margin").attr("value");
            let qty = $("#open_quantity").val();
            let open_price = $("#open_price").val();

            let input_sl = $(".input_sl");
            input_sl.each(function(){
                let no = $(this).attr("no");
                let sl_price = $(this).val();
                if(sl_price != "" && initial_margin != "" && qty != "" && open_price != ""){
                    let direction = $("#type").val();
                    let loss = 0;
                    if(direction == "1" && parseFloat(sl_price) < parseFloat(open_price)){
                        loss = Math.abs((sl_price - open_price) * qty);
                    }
                    else if(direction == "0" && parseFloat(sl_price) > parseFloat(open_price)){
                        loss = Math.abs((open_price - sl_price) * qty);
                    }
                    let loss_percentage = loss / initial_margin * 100;

                    let lossString = '';
                    if (loss > 0 && loss < 1) {
                        lossString = parseFloat(loss);
                    } else {
                        lossString = parseFloat(loss).toLocaleString('en-US');
                    }

                    $(`#pnl_sl${no}`).html(`$${lossString} (${loss_percentage}%)`);

                    $(this).attr("pnl", loss);
                    $(this).attr("pnl_percentage", loss_percentage);
                }
            });
        }

        function calculateRR(){
            let tp1 = $("#tp1");
            let sl1 = $("#sl1");

            let tp1_pnl = tp1.attr("pnl");
            let sl1_pnl = sl1.attr("pnl");

            if(tp1_pnl != "" && sl1_pnl != ""){
                let rr = parseFloat(tp1_pnl) / parseFloat(sl1_pnl);
                $("#rr_expected").html(rr);
            }
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
                    $(this).find('.pnl_tp').attr('id', `pnl_tp${value}`);
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
                    $(this).find('.pnl_sl').attr('id', `pnl_sl${value}`);
                    value++;
                })
                parent.remove();
            });

            $(document).on('keyup', '.input_sl', function(){

                let open_price = $("#open_price").val();
                let this_input = $(this);
                let no = this_input.attr("no");
                if(no == "1"){
                    calculateRisk(open_price, this_input.val());
                }

                calculateLoss();
                calculateRR();
            });

            $(document).on('keyup', '.input_tp', function(){
                calculateProfit();
                calculateRR();
            });

            $(document).on('change', '#type', function(){
                calculateProfit();
                calculateLoss();
                calculateRisk();
                calculateRR();
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
                            <h4 class="card-title">Tambah Catatan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" class="row g-0">
                            @csrf
                            <div class="col-12 mb-3">
                                <h6 class="text-muted"><strong>Informasi Koin</strong></h6>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-6 form-group row gx-3 align-items-center">
                                    <div class="col-3">
                                        <label for="asset" class="text-dark">Koin</label>
                                    </div>
                                    <div class="col-9">
                                        <div class="ui-widget">
                                            <input type="text" name="asset" id="asset" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 form-group row gx-3 align-items-center">
                                    <div class="col-2">
                                        <label for="type" class="text-dark">Tipe</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="type" id="type" class="form-control">
                                            <option value="1">LONG</option>
                                            <option value="0">SHORT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-5 form-group row gx-3 align-items-center">
                                    <div class="col-4"><label for="lev" class="text-dark">Leverage</label></div>
                                    <div class="col-8"><input type="number" name="lev" id="lev"
                                            class="form-control"></div>
                                </div>
                                <div class="col-sm-12 col-md-7 form-group row gx-3 align-items-center">
                                    <div class="col-4"><label for="open_price" class="text-dark">Harga Entri</label>
                                    </div>
                                    <div class="col-8"><input type="number" name="open_price" id="open_price"
                                            class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-12 form-group row align-items-center">
                                    <div class="col-sm-12 col-md-5 row gx-2 align-items-center">
                                        <div class="col-4">
                                            <label for="open_quantity" class="text-dark">Jumlah</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="number" name="open_quantity" id="open_quantity"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-7">
                                        <span id="openQuantityInline" class="form-text" style="display: none;"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-5 form-group row gx-3 align-items-center">
                                    <div class="col-12 text-dark">Margin Awal: $<span id="initial_margin"></span></div>
                                </div>
                                <div class="col-sm-12 col-md-7 form-group row gx-3 align-items-center">
                                    <div class="col-4"><label for="open_time" class="text-dark">Waktu Entri</label>
                                    </div>
                                    <div class="col-8"><input type="datetime-local" name="open_time" id="open_time"
                                            class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-12 text-dark">
                                    <div class="ps-1 mb-3">
                                        Harga Entri Rata-Rata: $<span id="average_price"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-12 text-dark">
                                    <div class="ps-2 mb-3">
                                        Sisa Jumlah: <span id="quantity_remaining"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <hr />
                            </div>
                            <div class="col-12 mb-3">
                                <h6 class="text-muted"><strong>Informasi TP & SL</strong></h6>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0 pe-3 justify-content-start align-items-start">
                                <div
                                    class="col-sm-12 col-md-12 d-flex align-items-center justify-content-between text-dark mb-3">
                                    <span>Harga TP</span>
                                    <button id="add_tp" type="button" class="btn btn-sm btn-primary">+ Tambah Harga TP</button>
                                </div>
                                <div id="tp_container" class="col-12 h-100">
                                    <div class="row gx-2 gy-2 gy-md-0 mb-2 align-items-center tp_container">
                                        <div class="col-sm-12 col-md-2">
                                            <span class="txt">TP 1</span>
                                        </div>
                                        <div class="col-sm-12 col-md-3"><input type="number" name="tp[]"
                                                id="tp1" no="1" class="form-control input_tp"></div>
                                        <div class="col-sm-12 col-md-7">akan mendapatkan keuntungan <span
                                                class="text-success pnl_tp" id="pnl_tp1">$0 (0%)</span></div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="col-sm-12 col-md-6 row g-0 ps-0 ps-md-2 pe-3 pe-md-4 justify-content-start align-items-start">
                                <div
                                    class="col-sm-12 col-md-12 d-flex align-items-center justify-content-between text-dark mb-3">
                                    <span>Harga SL</span>
                                    <button type="button" id="add_sl" class="btn btn-sm btn-primary">+ Tambah
                                        Harga SL</button>
                                </div>
                                <div id="sl_container" class="col-12 h-100">
                                    <div class="row gx-2 gy-2 gy-md-0 align-items-center mb-2 sl_container" no="1">
                                        <div class="col-sm-12 col-md-2">
                                            <span class="txt">SL 1</span>
                                        </div>
                                        <div class="col-sm-12 col-md-3"><input type="number" name="sl[]"
                                                id="sl1" no="1"  class="form-control input_sl"></div>
                                        <div class="col-sm-12 col-md-7">akan mendapatkan kerugian <span
                                                class="text-danger pnl_sl" id="pnl_sl1">$0 (0%)</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 mt-md-0 col-12 text-dark">
                                Risk Ratio Terdekat: <span id="rr_expected">0</span>
                            </div>
                            <div class="col-12 mb-3">
                                <hr />
                            </div>
                            <div class="col-12 mb-3">
                                <h6 class="text-muted"><strong>Analisa dan Strategi yang Digunakan</strong></h6>
                            </div>
                            <div class="col-12 row g-0">
                                <div class="col-sm-12 col-md-3 form-group row gx-0 align-items-center">
                                    <div class="col-4"><label for="timeframe" class="text-dark">Timeframe</label>
                                    </div>
                                    <div class="col-8"><select name="timeframe" id="timeframe"
                                            class="form-control"></select></div>
                                </div>
                                <div class="col-sm-12 col-md-9 form-group row gx-2 align-items-center">
                                    <div class="col-2 text-center"><label for="strategy" class="text-dark">Strategi
                                            Entry</label></div>
                                    <div class="col-10"><select name="strategy" id="strategy"
                                            class="form-control"></select></div>
                                </div>
                            </div>
                            <div class="col-12 row g-0">
                                <div class="col-12 form-group row gx-0 align-items-center pe-0 pe-md-2">
                                    <div class="col-1"><label for="pattern" class="text-dark">Pattern</label></div>
                                    <div class="col-11"><select name="pattern" id="pattern"
                                            class="form-control"></select></div>
                                </div>
                            </div>
                            <div class="col-12 row g-0">
                                <div class="col-12 form-group row gx-0 align-items-center pe-0 pe-md-2">
                                    <div class="col-1"><label for="indicator" class="text-dark">Indikator</label>
                                    </div>
                                    <div class="col-11"><select name="indicator" id="indicator"
                                            class="form-control"></select></div>
                                </div>
                            </div>
                            <div class="col-12 row row-cols-1 row-cols-md-2 row-cols-lg-4 mt-0 ps-0 pe-0"
                                id="strategy_screenshot">
                                <div class="col mt-0 mb-3 text-dark">
                                    <strong>1H</strong>
                                    <div class="img-wrap d-block">
                                        <a href="" class="delete text-danger"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                            alt="">
                                    </div>
                                    <div class="img-input d-none">
                                        <div class="input-group py-2">
                                            <input type="file" name="img_4h" id="img_4h" class="img_input">
                                            <label for="img_4h">Pilih gambar...</label> /
                                        </div>
                                        <input type="url" name="tv_4h" id="tv_4h" class="form-control"
                                            placeholder="Isi URL Trading View Disini..">
                                    </div>
                                </div>
                                <div class="col mt-0 mb-3 text-dark">
                                    <strong>1H</strong>
                                    <div class="img-wrap d-none">
                                        <a href="" class="delete text-danger"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                            alt="">
                                    </div>
                                    <div class="img-input d-block">
                                        <div class="input-group py-2">
                                            <input type="file" name="img_4h" id="img_4h" class="img_input">
                                            <label for="img_4h">Pilih gambar...</label> /
                                        </div>
                                        <input type="url" name="tv_4h" id="tv_4h" class="form-control"
                                            placeholder="Isi URL Trading View Disini..">
                                    </div>
                                </div>
                                <div class="col mt-0 mb-3 text-dark">
                                    <strong>1H</strong>
                                    <div class="img-wrap d-block">
                                        <a href="" class="delete text-danger"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                            alt="">
                                    </div>
                                    <div class="img-input d-none">
                                        <div class="input-group py-2">
                                            <input type="file" name="img_4h" id="img_4h" class="img_input">
                                            <label for="img_4h">Pilih gambar...</label> /
                                        </div>
                                        <input type="url" name="tv_4h" id="tv_4h" class="form-control"
                                            placeholder="Isi URL Trading View Disini..">
                                    </div>
                                </div>
                                <div class="col mt-0 mb-3 text-dark">
                                    <strong>1H</strong>
                                    <div class="img-wrap d-block">
                                        <a href="" class="delete text-danger"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                            alt="">
                                    </div>
                                    <div class="img-input d-none">
                                        <div class="input-group py-2">
                                            <input type="file" name="img_4h" id="img_4h" class="img_input">
                                            <label for="img_4h">Pilih gambar...</label> /
                                        </div>
                                        <input type="url" name="tv_4h" id="tv_4h" class="form-control"
                                            placeholder="Isi URL Trading View Disini..">
                                    </div>
                                </div>
                                <div class="col mt-2 mb-3 text-dark">
                                    <strong>1H</strong>
                                    <div class="img-wrap d-block">
                                        <a href="" class="delete text-danger"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                            </svg></a>
                                        <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                            alt="">
                                    </div>
                                    <div class="img-input d-none">
                                        <div class="input-group py-2">
                                            <input type="file" name="img_4h" id="img_4h" class="img_input">
                                            <label for="img_4h">Pilih gambar...</label> /
                                        </div>
                                        <input type="url" name="tv_4h" id="tv_4h" class="form-control"
                                            placeholder="Isi URL Trading View Disini..">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row g-0">
                                <div class="col-12 form-group align-items-center pe-0 pe-md-2">
                                    <label for="notes" class="text-dark">Catatan</label>
                                    <textarea name="notes" id="notes" class="form-control" style="resize: none; height: 200px"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <hr />
                            </div>
                            <div class="col-12 mb-3 row gx-4 gy-4 mt-0 ps-2 pe-0 pe-md-2">
                                <div class="col-sm-12 col-md-7 row g-0">
                                    <div
                                        class="col-12 d-flex align-items-start justify-content-between text-dark mb-3">
                                        <h6 class="text-muted"><strong>Transaksi</strong></h6>
                                        <button type="button" class="btn btn-sm btn-primary">+ Tambah
                                            Transaksi</button>
                                    </div>
                                    <div class="col-12 table-responsive">
                                        <table id="transactions-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>Tipe</th>
                                                    <th>Harga</th>
                                                    <th>Kuantitas</th>
                                                    <th>Waktu</th>
                                                    <th>Keuntungan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-5 mt-0">
                                    <h6 class="text-muted mb-2"><strong>Arsip Screenshot</strong></h6>
                                    <div class="row row-cols-1 row-cols-md-2 gx-3 ps-0 pe-0" id="pnl_screenshot">
                                        <div class="col mt-0 mb-3 text-dark">
                                            <div class="img-wrap d-block">
                                                <a href="" class="delete text-danger"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="18"
                                                        height="18" fill="currentColor" class="bi bi-trash-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                    </svg></a>
                                                <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                                    alt="">
                                            </div>
                                            <div class="img-input d-none">
                                                <div class="input-group py-2">
                                                    <input type="file" name="img_1" id="img_1"
                                                        class="img_input">
                                                    <label for="img_1">Pilih gambar...</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col mt-0 mb-3 text-dark">
                                            <div class="img-wrap d-block">
                                                <a href="" class="delete text-danger"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="18"
                                                        height="18" fill="currentColor" class="bi bi-trash-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                    </svg></a>
                                                <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                                    alt="">
                                            </div>
                                            <div class="img-input d-none">
                                                <div class="input-group py-2">
                                                    <input type="file" name="img_1" id="img_1"
                                                        class="img_input">
                                                    <label for="img_1">Pilih gambar...</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col mt-0 mb-3 text-dark">
                                            <div class="img-wrap d-none">
                                                <a href="" class="delete text-danger"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="18"
                                                        height="18" fill="currentColor" class="bi bi-trash-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                    </svg></a>
                                                <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                                    alt="">
                                            </div>
                                            <div class="img-input d-block">
                                                <div class="input-group py-2">
                                                    <input type="file" name="img_1" id="img_1"
                                                        class="img_input">
                                                    <label for="img_1">Pilih gambar...</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col mt-0 mb-3 text-dark">
                                            <div class="img-wrap d-none">
                                                <a href="" class="delete text-danger"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="18"
                                                        height="18" fill="currentColor" class="bi bi-trash-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                    </svg></a>
                                                <img src="{{ asset('images/no-image.webp') }}" class="img-fluid"
                                                    alt="">
                                            </div>
                                            <div class="img-input d-block">
                                                <div class="input-group py-2">
                                                    <input type="file" name="img_1" id="img_1"
                                                        class="img_input">
                                                    <label for="img_1">Pilih gambar...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <hr />
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

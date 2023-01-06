@push('styles')
    <style>
        .ui-autocomplete {
            max-height: 25vh;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }

        * html .ui-autocomplete {
            height: 25vh;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush
@push('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    $(function(){
        $("#inputAset").keyup(function(){
            $("#asetResult").empty();

            var query = $(this).val();
            if(query != ''){
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{route('user.wallet.asset.add.autocomplete', $wallet->id)}}",
                    method:"POST",
                    data:{query:query, _token:_token},
                    success:function(data){
                        $("#asetResult").fadeIn();
                        data.forEach(element => {
                            // console.log(element['name']);
                            $("#asetResult").append(
                                `<li class="aset-item"><a class="dropdown-item" href="#">${element['name']}</a></li>`
                            );
                        });
                    }
                })
            }
        })

        $(document).on('click', '.aset-item', function(){
            // alert('tes');
            $("#inputAset").val($(this).text());
            $("#asetResult").fadeOut(function(){
                $(this).empty();
            });
        });
    });
</script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        // $(document).ready(function() {

        //     $("#inputAset").autocomplete({
        //         source: function(request, response) {
        //             // Fetch data
        //             $.ajax({
        //                 url: "{{ route('user.wallet.asset.add.autocomplete', $wallet->id) }}",
        //                 type: 'get',
        //                 dataType: "json",
        //                 data: {
        //                     query: request.term
        //                 },
        //                 success: function(data) {
        //                     // console.log(data);
        //                     response(data);
        //                 }
        //             });
        //         },
        //         select: function(event, ui) {
        //             // // Set selection
        //             console.log(ui.item)
        //             // $('#employee_search').val(ui.item.label); // display the selected text
        //             // $('#employeeid').val(ui.item.value); // save selected id to input
        //             return false;
        //         }
        //     });

        // });

        $("#inputAset").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.wallet.asset.add.autocomplete', $wallet->id) }}",
                    type: "get",
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        // console.log(data);
                        // var resp = $.map(data,function(obj){
                        //     return obj.label;
                        // });
                        // response(resp);
                        response($.map(data, function(el) {
                            return {
                                label: el.label,
                                value: el.value
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                // // Set selection
                console.log(ui.item)
                $('#inputAset').val(ui.item.label); // display the selected text
                // $('#employeeid').val(ui.item.value); // save selected id to input
                return false;
            },
            open: function() {
                $('ul.ui-autocomplete').hide().fadeIn("fast")
            },
            close: function() {
                $('ul.ui-autocomplete').show().fadeOut("fast")
            }
        });
    </script>
@endpush

<x-app-layout :assets="$assets ?? []">
    <x-back-button />
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-md-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Tambah Aset</h4>
                        </div>
                    </div>
                    <div class="card-body min-vh-100">
                        <form action="" method="post">
                            @csrf
                            <div class="ui-widget">
                                <input type="text" name="inputAset" id="inputAset" class="form-control"
                                    placeholder="Masukkan aset yang ingin Anda tambahkan">
                                {{-- <ul id="asetResult" class="dropdown-menu"
                                    style="display:block; position: relative;width:100%">

                                </ul> --}}
                            </div>
                        </form>
                        <div id="asetDetail">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

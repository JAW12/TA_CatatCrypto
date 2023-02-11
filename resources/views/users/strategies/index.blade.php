@section('title', 'Daftar Pustaka')
@push('styles')
    <style>
        .has-search .form-control {
            padding-left: 2.375rem;
        }

        .favorite {
            color: #ff4425;
        }

        .favorite:hover {
            color: #c72508;
        }

        .delete {
            color: #323232;
        }

        .delete:hover {
            color: black
        }

        .card-img-top {
            width: 100%;
            height: 15vw;
            object-fit: cover;
        }

        .card-text {
            overflow: hidden;
            text-overflow: ellipsis !important;
            display: -webkit-box;
            line-height: 1.5em;
            /* fallback */
            max-height: 4.5em;
            /* fallback */
            -webkit-line-clamp: 5;
            /* number of lines to show */
            -webkit-box-orient: vertical;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function loadData() {
            let search = $("#search").val();
            $.ajax({
                url: "{{ route('user.library.search') }}",
                type: 'get',
                data: {
                    search: search,
                    favorit: '{{ $favorit }}',
                    kategori: '{{ $category }}'
                },
                success: function(data) {
                    $("#entry_strategies").html("");
                    $("#pattern").html("");
                    $("#indicator").html("");
                    $("#private").html("");

                    var user_id = {{ Auth::id() }};
                    var kategori = "{{ $category }}";

                    if (data.length > 0) {
                        let entry_strategies = [];
                        let pattern = [];
                        let indicator = [];
                        let private = [];
                        data.forEach(element => {
                            // console.log(element, ":", element.user_id, user_id, "=", element.user_id == user_id);
                            if (element.category_id == 1 && element.user_id != user_id && (kategori == "semua" || kategori == "strategi-entri")) {
                                entry_strategies.push(element);
                            } else if (element.category_id > 1 && element.category_id < 5 && element
                                .user_id != user_id && (kategori == "semua" || kategori == "pola")) {
                                pattern.push(element);
                            } else if (element.category_id == 5 && element.user_id != user_id && (kategori == "semua" || kategori == "indikator")) {
                                indicator.push(element);
                            } else if (element.user_id == user_id) {
                                private.push(element);
                            }
                        });

                        let counter = 0;
                        entry_strategies.forEach(element => {
                            if (counter < 8 || kategori != "semua") {
                                let src = `<?php echo asset('url'); ?>`;
                                if (element.url != "") {
                                    src = src.replace('url', element.url_picture);
                                } else {
                                    src = "{{ asset('images/no-image.webp') }}";
                                }

                                if (element.description == null) {
                                    element.description = "";
                                }

                                let favButton = "";
                                if (element.users.length > 0) {
                                    let route = `<?php echo route('user.library.unlike', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                        </a>`;
                                } else {
                                    let route = `<?php echo route('user.library.like', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        </a>`;
                                }

                                let routeDetail = `<?php echo route('user.library.detail', ['id' => ':id']); ?>`;
                                routeDetail = routeDetail.replace(':id', element.id);

                                let cardHTML = `<div class="col">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <img src="${src}" class="card-img-top" alt="${element.name}">
                                        ${favButton}
                                    </div>
                                <div class="card-body">
                                    <h5 class="card-title">${element.name}</h5>
                                    <p class="card-text">${element.description}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="${routeDetail}" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>`;

                                $("#entry_strategies").append(cardHTML);
                                counter++;
                            }
                        });

                        if (entry_strategies.length == 0) {
                            $("#entry_strategies").html("<p>Hasil tidak ditemukan</p>");
                        }

                        counter = 0;
                        pattern.forEach(element => {
                            if (counter < 8 || kategori != "semua") {
                                let src = `<?php echo asset('url'); ?>`;
                                if (element.url != "") {
                                    src = src.replace('url', element.url_picture);
                                } else {
                                    src = "{{ asset('images/no-image.webp') }}";
                                }

                                if (element.description == null) {
                                    element.description = "";
                                }

                                let favButton = "";
                                if (element.users.length > 0) {
                                    let route = `<?php echo route('user.library.unlike', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                        </a>`;
                                } else {
                                    let route = `<?php echo route('user.library.like', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        </a>`;
                                }

                                let routeDetail = `<?php echo route('user.library.detail', ['id' => ':id']); ?>`;
                                routeDetail = routeDetail.replace(':id', element.id);

                                let cardHTML = `<div class="col">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <img src="${src}" class="card-img-top" alt="${element.name}">
                                        ${favButton}
                                    </div>
                                <div class="card-body">
                                    <h5 class="card-title">${element.name}</h5>
                                    <p class="card-text">${element.description}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="${routeDetail}" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>`;

                                $("#pattern").append(cardHTML);
                                counter++;
                            }
                        });

                        if (pattern.length == 0) {
                            $("#pattern").html("<p>Hasil tidak ditemukan</p>");
                        }

                        counter = 0;
                        indicator.forEach(element => {
                            if (counter < 8 || kategori != "semua") {
                                let src = `<?php echo asset('url'); ?>`;
                                if (element.url != "") {
                                    src = src.replace('url', element.url_picture);
                                } else {
                                    src = "{{ asset('images/no-image.webp') }}";
                                }

                                if (element.description == null) {
                                    element.description = "";
                                }

                                let favButton = "";
                                if (element.users.length > 0) {
                                    let route = `<?php echo route('user.library.unlike', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                        </a>`;
                                } else {
                                    let route = `<?php echo route('user.library.like', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        </a>`;
                                }

                                let routeDetail = `<?php echo route('user.library.detail', ['id' => ':id']); ?>`;
                                routeDetail = routeDetail.replace(':id', element.id);

                                let cardHTML = `<div class="col">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <img src="${src}" class="card-img-top" alt="${element.name}">
                                        ${favButton}
                                    </div>
                                <div class="card-body">
                                    <h5 class="card-title">${element.name}</h5>
                                    <p class="card-text">${element.description}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="${routeDetail}" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>`;

                                $("#indicator").append(cardHTML);
                                counter++;
                            }
                        });

                        if (indicator.length == 0) {
                            $("#indicator").html("<p>Hasil tidak ditemukan</p>");
                        }

                        counter = 0;
                        private.forEach(element => {
                            if (counter < 8 || kategori != "semua") {
                                let src = `<?php echo asset('url'); ?>`;
                                if (element.url != "") {
                                    src = src.replace('url', element.url_picture);
                                } else {
                                    src = "{{ asset('images/no-image.webp') }}";
                                }

                                if (element.description == null) {
                                    element.description = "";
                                }

                                let favButton = "";
                                if (element.users.length > 0) {
                                    let route = `<?php echo route('user.library.unlike', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                        </a>`;
                                } else {
                                    let route = `<?php echo route('user.library.like', ['id' => ':id']); ?>`;
                                    route = route.replace(':id', element.id);
                                    favButton = `<a href="${route}" class="favorite">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        </a>`;
                                }

                                let routeDelete = `<?php echo route('user.library.delete', ['id' => ':id']); ?>`;
                                routeDelete = routeDelete.replace(':id', element.id);
                                let routeDetail = `<?php echo route('user.library.detail', ['id' => ':id']); ?>`;
                                routeDetail = routeDetail.replace(':id', element.id);

                                let deleteButton = `<a href="${routeDelete}" class="delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                            </a>`;

                                let cardHTML = `<div class="col">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <img src="${src}" class="card-img-top" alt="${element.name}">
                                        <div style="position:absolute; top: 0.5em; right:0.5em;">
                                            ${favButton}
                                            ${deleteButton}
                                        </div>
                                    </div>
                                <div class="card-body">
                                    <h5 class="card-title">${element.name} <br><span class="h6 text-muted"><small>${element.category.name}</small></span></h5>
                                    <p class="card-text">${element.description}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="${routeDetail}" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>`;

                                $("#private").append(cardHTML);
                                counter++;
                            }
                        });

                        if (private.length == 0) {
                            $("#private").html("<p>Hasil tidak ditemukan</p>");
                        }
                    } else {
                        $("#entry_strategies").html("<p>Hasil tidak ditemukan</p>");
                        $("#pattern").html("<p>Hasil tidak ditemukan</p>");
                        $("#indicator").html("<p>Hasil tidak ditemukan</p>");
                        $("#private").html("<p>Hasil tidak ditemukan</p>");
                    }
                }
            });
        }

        loadData();

        $(function() {
            $("#search").keyup(function() {
                let search = $(this).val();
                loadData();
            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                let delete_button = $(this);
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan menghapus pustaka pribadi ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, hapus!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = $(this).attr('href');
                    }
                })
            });
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        @if($category != "semua")
        <x-back-button>{{route('user.library')}}</x-back-button>
        @endif
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-3">
                                    <h4 class="card-title">Daftar Pustaka @if ($favorit == 1)
                                            Favorit
                                        @endif
                                    </h4>
                                </div>
                                <div class="col-sm-12 col-md-9 mt-3 mt-md-0">
                                    <div class="row g-2">
                                        <div class="col-sm-12 col-md-9">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <svg width="18" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="11.7669" cy="11.7666" r="8.98856"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></circle>
                                                        <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                                <input type="search" class="form-control" placeholder="Search..."
                                                    id="search">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <button type="button" class="btn btn-dark w-100">Laporan
                                                Metrik</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($category == "semua" || $category == "strategi-entri")
                            <div class="d-md-flex justify-content-between">
                                <h5>Strategi Entri</h5>
                                @if($category == "semua" and $favorit == 0)
                                <a href="{{route('user.library.category', ['category' => 'strategi-entri'])}}" class="btn btn-soft-primary">Lihat Selengkapnya</a>
                                @endif
                            </div>
                            <hr>
                            <div class="mb-4">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="entry_strategies">
                                </div>
                            </div>
                            @endif
                            @if($category == "semua" || $category == "pola")
                            <div class="d-md-flex justify-content-between">
                                <h5>Pola</h5>
                                @if($category == "semua" and $favorit == 0)
                                <a href="{{route('user.library.category', ['category' => 'pola'])}}" class="btn btn-soft-primary">Lihat Selengkapnya</a>
                                @endif
                            </div>
                            <hr>
                            <div class="mb-4">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="pattern">
                                </div>
                            </div>
                            @endif
                            @if($category == "semua" || $category == "indikator")
                            <div class="d-md-flex justify-content-between">
                                <h5>Indikator</h5>
                                @if($category == "semua" and $favorit == 0)
                                <a href="{{route('user.library.category', ['category' => 'indikator'])}}" class="btn btn-soft-primary">Lihat Selengkapnya</a>
                                @endif
                            </div>
                            <hr>
                            <div class="mb-5">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="indicator">
                                </div>
                            </div>
                            @endif
                            @if($category == "semua")
                            <h5>Strategi Pribadi</h5>
                            <hr>
                            <div>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="private">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

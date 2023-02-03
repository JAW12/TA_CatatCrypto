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

        .card-img-top {
            width: 100%;
            height: 15vw;
            object-fit: cover;
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
                    favorit: '{{ $favorit}}'
                },
                success: function(data) {
                    $("#entry_strategies").html("");
                    $("#pattern").html("");
                    $("#indicator").html("");
                    $("#private").html("");

                    var user_id = {{ Auth::id() }};

                    if (data.length > 0) {
                        let entry_strategies = [];
                        let pattern = [];
                        let indicator = [];
                        let private = [];
                        data.forEach(element => {
                            if (element.category_id == 1 && element.user_id != user_id) {
                                entry_strategies.push(element);
                            } else if (element.category_id > 1 && element.category_id < 5 && element
                                .user_id != user_id) {
                                pattern.push(element);
                            } else if (element.category_id == 5 && element.user_id != user_id) {
                                indicator.push(element);
                            } else if (element.user_id == user_id) {
                                private.push(element);
                            }
                        });

                        let counter = 0;
                        entry_strategies.forEach(element => {
                            if (counter < 8) {
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
                                    <a href="#" class="btn btn-primary">Lihat Detail</a>
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
                            if (counter < 8) {
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
                                    favButton = `<a href="#" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                        </a>`;
                                } else {
                                    favButton = `<a href="#" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        </a>`;
                                }

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
                                    <a href="#" class="btn btn-primary">Lihat Detail</a>
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
                            if (counter < 8) {
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
                                    favButton = `<a href="#" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                        </a>`;
                                } else {
                                    favButton = `<a href="#" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        </a>`;
                                }

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
                                    <a href="#" class="btn btn-primary">Lihat Detail</a>
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
                            if (counter < 8) {
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
                                    favButton = `<a href="#" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                        </svg>
                                        </a>`;
                                } else {
                                    favButton = `<a href="#" class="favorite" style="position:absolute; top: 0.5em; right: 0.5em;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        </a>`;
                                }

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
                                    <a href="#" class="btn btn-primary">Lihat Detail</a>
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
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-3">
                                    <h4 class="card-title">Daftar Pustaka @if($favorit == 1) Favorit @endif</h4>
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
                                            <button type="button" class="btn btn-dark w-100">Lihat Laporan
                                                Metrik</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5>Strategi Entri</h5>
                            <hr>
                            <div class="mb-4">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="entry_strategies">
                                </div>
                            </div>
                            <h5>Pattern</h5>
                            <hr>
                            <div class="mb-4">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="pattern">
                                </div>
                            </div>
                            <h5>Indicator</h5>
                            <hr>
                            <div class="mb-5">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="indicator">
                                </div>
                            </div>
                            <h5>Strategi Pribadi</h5>
                            <hr>
                            <div>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gx-3 gy-4" id="private">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

@section('title', 'Profil Pengguna')
@push('scripts')
    <script>
        $(function() {
            $('#btnSimpan').click(function(e) {
                e.preventDefault() // Don't post the form, unless confirmed
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin dengan perubahan profil?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, simpan perubahan!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(e.target).closest('form').submit() // Post the surrounding form
                    }
                })
            });

            $("#btnUbah").click(function() {
                $(this).hide();
                $("#txtNama").hide();
                $("#txtJK").hide();
                $("#txtEmail").hide();
                $("#txtTglLahir").hide();
                $("#txtTelp").hide();


                $("#btnSimpan").show();
                $("#inpNama").show();
                $("#inpJK").show();
                $("#inpEmail").show();
                $("#inpTglLahir").show();
                $("#inpTelp").show();
            });
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    <form method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="profile-img position-relative me-3 mb-3 mb-lg-0">
                                    <img src="{{ $profileImage ?? asset('images/avatars/01.png') }}" alt="User-Profile"
                                        class="theme-color-default-img img-fluid rounded-pill avatar-100">
                                </div>
                                <div class="d-flex flex-wrap align-items-center mb-3 mb-sm-0">
                                    <div class="d-flex flex-wrap align-items-center mb-3 mb-sm-0">
                                        <h4 class="me-2 h4">{{ $data->full_name ?? 'Austin Robertson' }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div id="btnArea">
                                <button class="btn btn-soft-primary rounded-pill" id="btnUbah" type="button">
                                    <span class="btn-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </span>
                                    Ubah
                                </button>
                                <button class="btn btn-primary rounded-pill" id="btnSimpan" type="submit"
                                    style="display:none">
                                    <span class="btn-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </span>
                                    Simpan
                                </button>
                            </div>
                            {{-- <ul class="d-flex nav nav-pills mb-0 text-center profile-tab" data-toggle="slider-tab"
                            id="profile-pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" data-bs-toggle="tab" href="#profile-feed" role="tab"
                                    aria-selected="false">Feed</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#profile-activity" role="tab"
                                    aria-selected="false">Activity</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#profile-friends" role="tab"
                                    aria-selected="false">Friends</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#profile-profile" role="tab"
                                    aria-selected="false">Profile</a>
                            </li>
                        </ul> --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">News</h4>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-inline m-0 p-0">
                        <li class="d-flex mb-2">
                            <div class="news-icon me-3">
                                <svg width="20" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z" />
                                </svg>
                            </div>
                            <p class="news-detail mb-0">there is a meetup in your city on fryday at 19:00. <a
                                    href="#">see details</a></p>
                        </li>
                        <li class="d-flex">
                            <div class="news-icon me-3">
                                <svg width="20" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z" />
                                </svg>
                            </div>
                            <p class="news-detail mb-0">20% off coupon on selected items at pharmaprix </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Gallery</h4>
                    </div>
                    <span>132 pics</span>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-card grid-cols-3">
                        <a data-fslightbox="gallery" href="{{ asset('images/icons/04.png') }}">
                            <img src="{{ asset('images/icons/04.png') }}" class="img-fluid bg-soft-info rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/shapes/02.png') }}">
                            <img src="{{ asset('images/shapes/02.png') }}" class="img-fluid bg-soft-primary rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/icons/08.png') }}">
                            <img src="{{ asset('images/icons/08.png') }}" class="img-fluid bg-soft-info rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/shapes/04.png') }}">
                            <img src="{{ asset('images/shapes/04.png') }}" class="img-fluid bg-soft-primary rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/icons/02.png') }}">
                            <img src="{{ asset('images/icons/02.png') }}" class="img-fluid bg-soft-warning rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/shapes/06.png') }}">
                            <img src="{{ asset('images/shapes/06.png') }}" class="img-fluid bg-soft-primary rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/icons/05.png') }}">
                            <img src="{{ asset('images/icons/05.png') }}" class="img-fluid bg-soft-danger rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/shapes/04.png') }}">
                            <img src="{{ asset('images/shapes/04.png') }}" class="img-fluid bg-soft-primary rounded"
                                alt="profile-image">
                        </a>
                        <a data-fslightbox="gallery" href="{{ asset('images/icons/01.png') }}">
                            <img src="{{ asset('images/icons/01.png') }}" class="img-fluid bg-soft-success rounded"
                                alt="profile-image">
                        </a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Twitter Feeds</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="twit-feed">
                        <div class="d-flex align-items-center mb-2">
                            <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-soft-danger ps-2"
                                src="{{ asset('images/icons/03.png') }}" alt="">
                            <div class="media-support-info">
                                <h6 class="mb-0">Figma Community</h6>
                                <p class="mb-0">@figma20
                                    <span class="text-primary">
                                        <svg width="15" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="media-support-body">
                            <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry
                            </p>
                            <div class="d-flex flex-wrap">
                                <a href="#" class="twit-meta-tag pe-2">#Html</a>
                                <a href="#" class="twit-meta-tag pe-2">#Bootstrap</a>
                            </div>
                            <div class="twit-date">07 Jan 2021</div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="twit-feed">
                        <div class="d-flex align-items-center mb-2">
                            <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-soft-primary"
                                src="{{ asset('images/icons/04.png') }}" alt="">
                            <div class="media-support-info">
                                <h6 class="mb-0">Flutter</h6>
                                <p class="mb-0">@jane59
                                    <span class="text-primary">
                                        <svg width="15" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="media-support-body">
                            <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry
                            </p>
                            <div class="d-flex flex-wrap">
                                <a href="#" class="twit-meta-tag pe-2">#Js</a>
                                <a href="#" class="twit-meta-tag pe-2">#Bootstrap</a>
                            </div>
                            <div class="twit-date">18 Feb 2021</div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="twit-feed">
                        <div class="d-flex align-items-center mb-2">
                            <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-soft-warning pt-2"
                                src="{{ asset('images/icons/02.png') }}" alt="">
                            <div class="mt-2">
                                <h6 class="mb-0">Blender</h6>
                                <p class="mb-0">@blender59
                                    <span class="text-primary">
                                        <svg width="15" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="media-support-body">
                            <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry
                            </p>
                            <div class="d-flex flex-wrap">
                                <a href="#" class="twit-meta-tag pe-2">#Html</a>
                                <a href="#" class="twit-meta-tag pe-2">#CSS</a>
                            </div>
                            <div class="twit-date">15 Mar 2021</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
            <div class="col-lg-6">
                <div class="profile-content">
                    {{-- <div id="profile-feed" class="tab-pane fade active show">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between pb-4">
                            <div class="header-title">
                                <div class="d-flex flex-wrap">
                                    <div class="media-support-user-img me-3">
                                        <img class="rounded-pill img-fluid avatar-60 bg-soft-danger p-1 ps-2"
                                            src="{{ asset('images/avatars/02.png') }}" alt="">
                                    </div>
                                    <div class="media-support-info mt-2">
                                        <h5 class="mb-0">Anna Sthesia</h5>
                                        <p class="mb-0 text-primary">colleages</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <span class="dropdown-toggle" id="dropdownMenuButton7" data-bs-toggle="dropdown"
                                    aria-expanded="false" role="button">
                                    29 mins
                                </span>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton7">
                                    <a class="dropdown-item " href="javascript:void(0);">Action</a>
                                    <a class="dropdown-item " href="javascript:void(0);">Another action</a>
                                    <a class="dropdown-item " href="javascript:void(0);">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="user-post">
                                <a href="javascript:void(0);"><img src="{{ asset('images/pages/02-page') }}.png"
                                        alt="post-image" class="img-fluid"></a>
                            </div>
                            <div class="comment-area p-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center message-icon me-3">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                            </svg>
                                            <span class="ms-1">140</span>
                                        </div>
                                        <div class="d-flex align-items-center feather-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M10,16V19.08L13.08,16H20V4H4V16H10Z" />
                                            </svg>
                                            <span class="ms-1">140</span>
                                        </div>
                                    </div>
                                    <div class="share-block d-flex align-items-center feather-icon">
                                        <a href="javascript:void(0);" data-bs-toggle="offcanvas"
                                            data-bs-target="#share-btn" aria-controls="share-btn">
                                            <span class="ms-1">
                                                <svg width="18" class="me-1" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12S8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C19.66 8 21 6.66 21 5S19.66 2 18 2 15 3.34 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C4.34 9 3 10.34 3 12S4.34 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.34C15.11 18.55 15.08 18.77 15.08 19C15.08 20.61 16.39 21.91 18 21.91S20.92 20.61 20.92 19C20.92 17.39 19.61 16.08 18 16.08M18 4C18.55 4 19 4.45 19 5S18.55 6 18 6 17 5.55 17 5 17.45 4 18 4M6 13C5.45 13 5 12.55 5 12S5.45 11 6 11 7 11.45 7 12 6.55 13 6 13M18 20C17.45 20 17 19.55 17 19S17.45 18 18 18 19 18.45 19 19 18.55 20 18 20Z">
                                                    </path>
                                                </svg>
                                                99 Share</span></a>
                                    </div>
                                </div>
                                <hr>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nulla dolor, ornare at
                                    commodo non, feugiat non nisi. Phasellus faucibus mollis pharetra. Proin blandit ac
                                    massa sed rhoncus</p>
                                <hr>
                                <ul class="list-inline p-0 m-0">
                                    <li class="mb-2">
                                        <div class="d-flex">
                                            <img src="{{ asset('images/avatars/03.png') }}" alt="userimg"
                                                class="avatar-50 p-1 pt-2 bg-soft-primary rounded-pill img-fluid">
                                            <div class="ms-3">
                                                <h6 class="mb-1">Monty Carlo</h6>
                                                <p class="mb-1">Lorem ipsum dolor sit amet</p>
                                                <div class="d-flex flex-wrap align-items-center mb-1">
                                                    <a href="javascript:void(0);" class="me-3">
                                                        <svg width="20" height="20" class="text-body me-1"
                                                            viewBox="0 0 24 24">
                                                            <path fill="currentColor"
                                                                d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                                        </svg>
                                                        like
                                                    </a>
                                                    <a href="javascript:void(0);" class="me-3">
                                                        <svg width="20" height="20" class="me-1"
                                                            viewBox="0 0 24 24">
                                                            <path fill="currentColor"
                                                                d="M8,9.8V10.7L9.7,11C12.3,11.4 14.2,12.4 15.6,13.7C13.9,13.2 12.1,12.9 10,12.9H8V14.2L5.8,12L8,9.8M10,5L3,12L10,19V14.9C15,14.9 18.5,16.5 21,20C20,15 17,10 10,9" />
                                                        </svg>
                                                        reply
                                                    </a>
                                                    <a href="javascript:void(0);" class="me-3">translate</a>
                                                    <span> 5 min </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex">
                                            <img src="{{ asset('images/avatars/04.png') }}" alt="userimg"
                                                class="avatar-50 p-1 bg-soft-danger rounded-pill img-fluid">
                                            <div class="ms-3">
                                                <h6 class="mb-1">Paul Molive</h6>
                                                <p class="mb-1">Lorem ipsum dolor sit amet</p>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <a href="javascript:void(0);" class="me-3">
                                                        <svg width="20" height="20" class="text-body me-1"
                                                            viewBox="0 0 24 24">
                                                            <path fill="currentColor"
                                                                d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                                        </svg>
                                                        like
                                                    </a>
                                                    <a href="javascript:void(0);" class="me-3">
                                                        <svg width="20" height="20" class="me-1"
                                                            viewBox="0 0 24 24">
                                                            <path fill="currentColor"
                                                                d="M8,9.8V10.7L9.7,11C12.3,11.4 14.2,12.4 15.6,13.7C13.9,13.2 12.1,12.9 10,12.9H8V14.2L5.8,12L8,9.8M10,5L3,12L10,19V14.9C15,14.9 18.5,16.5 21,20C20,15 17,10 10,9" />
                                                        </svg>
                                                        reply
                                                    </a>
                                                    <a href="javascript:void(0);" class="me-3">translate</a>
                                                    <span> 5 min </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <form class="comment-text d-flex align-items-center mt-3"
                                    action="javascript:void(0);">
                                    <input type="text" class="form-control rounded" placeholder="Lovely!">
                                    <div class="comment-attagement d-flex">
                                        <a href="javascript:void(0);" class="me-2 text-body">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z" />
                                            </svg>
                                        </a>
                                        <a href="javascript:void(0);" class="text-body">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M20,4H16.83L15,2H9L7.17,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V6H8.05L9.88,4H14.12L15.95,6H20V18M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15Z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between pb-4">
                            <div class="header-title">
                                <div class="d-flex flex-wrap">
                                    <div class="media-support-user-img me-3">
                                        <img class="rounded-pill img-fluid avatar-60 p-1 bg-soft-info"
                                            src="{{ asset('images/avatars/05.png') }}" alt="">
                                    </div>
                                    <div class="media-support-info mt-2">
                                        <h5 class="mb-0">Wade Warren</h5>
                                        <p class="mb-0 text-primary">colleages</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <span class="dropdown-toggle" id="dropdownMenuButton07" data-bs-toggle="dropdown"
                                    aria-expanded="false" role="button">
                                    1 Hr
                                </span>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton07">
                                    <a class="dropdown-item " href="javascript:void(0);">Action</a>
                                    <a class="dropdown-item " href="javascript:void(0);">Another action</a>
                                    <a class="dropdown-item " href="javascript:void(0);">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <p class="p-3 mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nulla
                                dolor, ornare at commodo non, feugiat non nisi. Phasellus faucibus mollis pharetra.
                                Proin blandit ac massa sed rhoncus</p>
                            <div class="comment-area p-3">
                                <hr class="mt-0">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center message-icon me-3">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                            </svg>
                                            <span class="ms-1">140</span>
                                        </div>
                                        <div class="d-flex align-items-center feather-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M10,16V19.08L13.08,16H20V4H4V16H10Z" />
                                            </svg>
                                            <span class="ms-1">140</span>
                                        </div>
                                    </div>
                                    <div class="share-block d-flex align-items-center feather-icon">
                                        <a href="javascript:void(0);" data-bs-toggle="offcanvas"
                                            data-bs-target="#share-btn" aria-controls="share-btn">
                                            <span class="ms-1">
                                                <svg width="18" class="me-1" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12S8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C19.66 8 21 6.66 21 5S19.66 2 18 2 15 3.34 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C4.34 9 3 10.34 3 12S4.34 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.34C15.11 18.55 15.08 18.77 15.08 19C15.08 20.61 16.39 21.91 18 21.91S20.92 20.61 20.92 19C20.92 17.39 19.61 16.08 18 16.08M18 4C18.55 4 19 4.45 19 5S18.55 6 18 6 17 5.55 17 5 17.45 4 18 4M6 13C5.45 13 5 12.55 5 12S5.45 11 6 11 7 11.45 7 12 6.55 13 6 13M18 20C17.45 20 17 19.55 17 19S17.45 18 18 18 19 18.45 19 19 18.55 20 18 20Z">
                                                    </path>
                                                </svg>
                                                99 Share
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <form class="comment-text d-flex align-items-center mt-3"
                                    action="javascript:void(0);">
                                    <input type="text" class="form-control rounded" placeholder="Lovely!">
                                    <div class="comment-attagement d-flex">
                                        <a href="javascript:void(0);" class="me-2 text-body">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z" />
                                            </svg>
                                        </a>
                                        <a href="javascript:void(0);" class="text-body">
                                            <svg width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M20,4H16.83L15,2H9L7.17,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V6H8.05L9.88,4H14.12L15.95,6H20V18M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15Z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="profile-activity" class="tab-pane fade">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Activity</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div
                                class="iq-timeline0 m-0 d-flex align-items-center justify-content-between position-relative">
                                <ul class="list-inline p-0 m-0">
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">Client Login</h6>
                                        <small class="float-right mt-1">24 November 2019</small>
                                        <div class="d-inline-block w-100">
                                            <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-success text-success"></div>
                                        <h6 class="float-left mb-1">Scheduled Maintenance</h6>
                                        <small class="float-right mt-1">23 November 2019</small>
                                        <div class="d-inline-block w-100">
                                            <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-danger text-danger"></div>
                                        <h6 class="float-left mb-1">Dev Meetup</h6>
                                        <small class="float-right mt-1">20 November 2019</small>
                                        <div class="d-inline-block w-100">
                                            <p>Bonbon macaroon jelly beans <a href="#">gummi bears</a>gummi bears
                                                jelly lollipop apple</p>
                                            <div class="iq-media-group iq-media-group-1">
                                                <a href="#" class="iq-media-1">
                                                    <div class="icon iq-icon-box-3 rounded-pill">SP</div>
                                                </a>
                                                <a href="#" class="iq-media-1">
                                                    <div class="icon iq-icon-box-3 rounded-pill">PP</div>
                                                </a>
                                                <a href="#" class="iq-media-1">
                                                    <div class="icon iq-icon-box-3 rounded-pill">MM</div>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">Client Call</h6>
                                        <small class="float-right mt-1">19 November 2019</small>
                                        <div class="d-inline-block w-100">
                                            <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-warning text-warning"></div>
                                        <h6 class="float-left mb-1">Mega event</h6>
                                        <small class="float-right mt-1">15 November 2019</small>
                                        <div class="d-inline-block w-100">
                                            <p>Bonbon macaroon jelly beans gummi bears jelly lollipop apple</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="profile-friends" class="tab-pane fade">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h4 class="card-title">Friends</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-inline m-0 p-0">
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/01.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Paul Molive</h6>
                                        <p class="mb-0">Web Designer</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton9"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton9">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/05.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Paul Molive</h6>
                                        <p class="mb-0">trainee</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton10"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton10">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/02.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Anna Mull</h6>
                                        <p class="mb-0">Web Developer</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton11"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton11">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/03.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Paige Turner</h6>
                                        <p class="mb-0">trainee</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton12"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton12">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/04.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Barb Ackue</h6>
                                        <p class="mb-0">Web Designer</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton13"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton13">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/05.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Greta Life</h6>
                                        <p class="mb-0">Tester</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton14"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton14">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/03.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Ira Membrit</h6>
                                        <p class="mb-0">Android Developer</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton15"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton15">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center">
                                    <img src="{{ asset('images/avatars/02.png') }}" alt="story-img"
                                        class="rounded-pill avatar-40">
                                    <div class="ms-3 flex-grow-1">
                                        <h6>Pete Sariya</h6>
                                        <p class="mb-0">Web Designer</p>
                                    </div>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton16"
                                            data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton16">
                                            <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                            <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                            <a class="dropdown-item " href="javascript:void(0);">block</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
                    <div id="profile-profile" class="">
                        {{-- <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h4 class="card-title">Profile</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="user-profile">
                                    <img src="{{ asset('images/avatars/01.png') }}" alt="profile-img"
                                        class="rounded-pill avatar-130 img-fluid">
                                </div>
                                <div class="mt-3">
                                    <h3 class="d-inline-block">{{ $data->first_name . ' ' . $data->last_name }}</h3>
                                    <p class="d-inline-block pl-3"> - Web developer</p>
                                    <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's standard dummy text ever since the
                                        1500s</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        <div class="card">
                            <div class="card-header">
                                <div class="header-title">
                                    <h4 class="card-title">Tentang</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- <div class="user-bio">
                                <p>Tart I love sugar plum I love oat cake. Sweet roll caramels I love jujubes. Topping
                                    cake wafer.</p>
                            </div> --}}
                                <div class="mt-2" id="txtNama">
                                    <h6 class="mb-1">Nama:</h6>
                                    <p>{{ $data->first_name . ' ' . $data->last_name }}</p>
                                </div>
                                <div class="mt-2 row g-2" id="inpNama" style="display:none">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label h6 mb-1">Nama Depan:</label>
                                        <input type="text" class="form-control" name="first_name"
                                            value="{{ $data->first_name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label h6 mb-1">Nama Belakang:</label>
                                        <input type="text" class="form-control" name="last_name"
                                            value="{{ $data->last_name }}">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h6 class="mb-1">Mulai bergabung:</h6>
                                    <p>{{ $data->created_at->formatLocalized('%d %B %Y') }}</p>
                                </div>
                                <div class="mt-2" id="txtJK">
                                    <h6 class="mb-1">Jenis Kelamin:</h6>
                                    <p>
                                        @if ($data->gender == 'm')
                                            Pria
                                        @elseif($data->gender == 'f')
                                            Wanita
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-2" id="inpJK" style="display:none">
                                    <label for="gender" class="form-label h6 mb-1">Jenis Kelamin:</label>
                                    <select name="gender" class="form-select">
                                        <option value="m" @if ($data->gender == 'm') selected @endif>Pria
                                        </option>
                                        <option value="f" @if ($data->gender == 'f') selected @endif>Wanita
                                        </option>
                                        <option value="null" @if ($data->gender == '') selected @endif>-
                                        </option>
                                    </select>
                                </div>
                                <div class="mt-2" id="txtEmail">
                                    <h6 class="mb-1">Alamat Email:</h6>
                                    <p>{{ $data->email }}
                                        @if ($data->email_verified_at != '')
                                            <span class="badge rounded-pill bg-success">Terverifikasi</span>
                                        @else
                                            <a href="{{ route('verification.send') }}"><span
                                                    class="badge rounded-pill bg-primary">Verifikasi disini</span></a>
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-2" id="inpEmail" style="display:none">
                                    <label for="email" class="form-label h6 mb-1">Alamat Email:</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ $data->email }}">
                                </div>
                                <div class="mt-2" id="txtTglLahir">
                                    <h6 class="mb-1">Tanggal Lahir:</h6>
                                    <p>{{ $data->birthdate == null ? '-' : date('d F Y', strtotime($data->birthdate)) }}
                                    </p>
                                </div>
                                <div class="mt-2" id="inpTglLahir" style="display:none">
                                    <label for="birthdate" class="form-label h6 mb-1">Tanggal Lahir:</label>
                                    <input type="date" class="form-control" name="birthdate"
                                        value="{{ $data->birthdate }}">
                                </div>
                                <div class="mt-2" id="txtTelp">
                                    <h6 class="mb-1">No. Telp:</h6>
                                    <p>{{ $data->phone_number }}</p>
                                </div>
                                <div class="mt-2" id="inpTelp" style="display:none">
                                    <label for="phone_number" class="form-label h6 mb-1">No. Telp:</label>
                                    <input type="tel" class="form-control" name="phone_number"
                                        value="{{ $data->phone_number }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Detail Membership</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="bd-example table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Jenis Membership</th>
                                        <td><span
                                                class="text-capitalize mt-1">{{ ucwords(count(auth()->user()->membership) == 0? (auth()->user()->user_type == 'user'? '-': auth()->user()->user_type): auth()->user()->membership->first()->name) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Aktif dari</th>
                                        <td>{{ $data->membership_since == null ? '-' : Carbon\Carbon::parse($data->membership_since)->formatLocalized('%d %B %Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Aktif sampai</th>
                                        <td>{{ $data->membership_till == null ? '-' : Carbon\Carbon::parse($data->membership_till)->formatLocalized('%d %B %Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Dompet tersedia</th>
                                        @if ($data->max_wallets >= 0)
                                            <td>{{ $data->wallets->count() }} dari {{ $data->max_wallets }}, sisa
                                                @if ($data->max_wallets - $data->wallets->count() >= 0)
                                                    {{ $data->max_wallets - $data->wallets->count() }} Dompet
                                                @else
                                                    0 Dompet
                                                @endif
                                            </td>
                                        @else
                                            <td>∞ Dompet</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>Jurnal tersedia</th>
                                        @if ($data->max_journals >= 0)
                                            <td>{{ $data->journals->count() }} dari {{ $data->max_journals }}, sisa
                                                @if ($data->max_journals - $data->journals->count() >= 0)
                                                    {{ $data->max_journals - $data->journals->count() }} Jurnal
                                                @else
                                                    0 Jurnal
                                                @endif
                                            </td>
                                        @else
                                            <td>∞ Jurnal</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>Catatan per bulan</th>
                                        @if ($data->trades_quantity_per_month >= 0)
                                            <td>{{ $data->trades_quantity_per_month }} Catatan</td>
                                        @else
                                            <td>∞ Catatan</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>Catatan tersedia</th>
                                        @if ($data->remaining_trades >= 0)
                                            <td>
                                                {{ $data->remaining_trades }} Catatan
                                                <a href="{{route('user.membership.payments', ['type' => 0])}}" type="button"
                                                    class="btn btn-primary btn-sm ms-2">Tambah 100</a>
                                            </td>
                                        @else
                                            <td>∞ Catatan</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Stories</h4>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-inline m-0 p-0">
                        <li class="d-flex mb-4 align-items-center active">
                            <img src="{{ asset('images/icons/06.png') }}" alt="story-img"
                                class="rounded-pill avatar-70 p-1 border bg-soft-light img-fluid">
                            <div class="ms-3">
                                <h5>Web Design</h5>
                                <p class="mb-0">1 hour ago</p>
                            </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <img src="{{ asset('images/icons/03.png') }}" alt="story-img"
                                class="rounded-pill avatar-70 p-1 border img-fluid bg-soft-danger">
                            <div class="ms-3">
                                <h5>App Design</h5>
                                <p class="mb-0">4 hour ago</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="{{ asset('images/icons/07.png') }}" alt="story-img"
                                class="rounded-pill avatar-70 p-1 border bg-soft-primary img-fluid">
                            <div class="ms-3">
                                <h5>Abstract Design</h5>
                                <p class="mb-0">9 hour ago</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Suggestions</h4>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-inline m-0 p-0">
                        <li class="d-flex mb-4 align-items-center">
                            <div class="img-fluid bg-soft-warning rounded-pill"><img
                                    src="{{ asset('images/icons/05.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Paul Molive</h6>
                                <p class="mb-0">4 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <div class="img-fluid bg-soft-danger rounded-pill"><img
                                    src="{{ asset('images/icons/03.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Robert Fox</h6>
                                <p class="mb-0">4 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <div class="img-fluid bg-soft-dark rounded-pill"><img
                                    src="{{ asset('images/icons/06.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Jenny Wilson</h6>
                                <p class="mb-0">6 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <div class="img-fluid bg-soft-primary rounded-pill"><img
                                    src="{{ asset('images/icons/07.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Cody Fisher</h6>
                                <p class="mb-0">8 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <div class="img-fluid bg-soft-info rounded-pill"><img
                                    src="{{ asset('images/icons/04.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Bessie Cooper</h6>
                                <p class="mb-0">1 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <div class="img-fluid bg-soft-warning rounded-pill"><img
                                    src="{{ asset('images/icons/02.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Wade Warren</h6>
                                <p class="mb-0">3 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <div class="img-fluid bg-soft-success rounded-pill"><img
                                    src="{{ asset('images/icons/01.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Guy Hawkins</h6>
                                <p class="mb-0">12 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="img-fluid bg-soft-info rounded-pill"><img
                                    src="{{ asset('images/icons/08.png') }}" alt="story-img"
                                    class="rounded-pill avatar-40"></div>
                            <div class="ms-3 flex-grow-1">
                                <h6>Floyd Miles</h6>
                                <p class="mb-0">2 mutual friends</p>
                            </div>
                            <a href="javascript:void(0);"
                                class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                <span class="btn-inner">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" currentColor="#3a57e8">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M19.2036 8.66919V12.6792" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M21.2497 10.6741H17.1597" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> --}}
            </div>
        </div>

    </form>
    {{-- @include('partials.components.share-offcanvas') --}}
</x-app-layout>

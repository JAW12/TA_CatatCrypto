@section('title', 'Ubah Pustaka')
@push('scripts')
    <script>
        $(function() {
            $(document).on('change', '#url_picture', function(e) {
                let this_input = $(this);
                if (this_input.length > 0) {
                    this_input = this_input[0];
                }
                if (this_input.files.length > 0) {
                    let img = $("#img_container");
                    let src = URL.createObjectURL(this_input.files[0]);
                    img.attr("src", src);
                }
            });
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <x-back-button>{{ route('admin.library.detail', ['id' => $strategy->id]) }}</x-back-button>
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h4 class="card-title">Ubah Pustaka</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="formAdd" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 col-md-8">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Judul</label>
                                            <input type="text" name="name" id="name" class="form-control" value="{{$strategy->name}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="category_id" class="form-label">Kategori</label>
                                            <select name="category_id" id="category_id" class="form-control">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @if($strategy->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="form-label">Catatan</label>
                                            <textarea name="description" id="description" class="form-control" style="height: 200px">{{$strategy->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <div class="d-flex flex-column h-100">
                                            <div class="form-group mb-2">
                                                <img src="{{ asset($strategy->url_picture) }}"
                                                    class="img-fluid viewer mb-2" alt="img" id="img_container">
                                                <input type="file" name="url_picture" id="url_picture">
                                            </div>
                                            <div class="mt-auto w-100">
                                                <button class="btn btn-primary w-100">Kumpul</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

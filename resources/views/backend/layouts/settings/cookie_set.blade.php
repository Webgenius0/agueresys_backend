@extends('backend.app')
@section('title', 'Cookie Settings')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Cookie Settings</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Settings</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Cookie</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <ul class="ps-0 mb-4 list-unstyled d-flex flex-wrap gap-2 gap-lg-3">
                    <li>
                        <a href="{{ route('system_settings.index') }}"
                            class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">System
                            Settings</a>
                    </li>
                    <li>
                        <a href="{{ route('system_settings.cookie_get') }}"
                            class="btn btn-primary border border-primary bg-primary text-white py-2 px-3 fw-semibold">Cookie
                            <Source:media:sizes></Source:media:sizes>Settings
                        </a>
                    </li>
                </ul>

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Cookie Settings</h4>
                    <p class="fs-15">Update your Cookie Text here.</p>
                </div>

                <form action="{{ route('system_settings.cookie_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark ps-5 h-55 @error('description') is-invalid @enderror" name="description"
                                        placeholder="Enter description here">{{ old('description', $data->description ?? 'This website uses cookies to enhance your experience. By continuing to browse, you accept our use of cookies.') }}</textarea>
                                </div>
                                @error('description')
                                    <div id="description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                        class="ri-check-line text-white fw-medium"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>
@endpush

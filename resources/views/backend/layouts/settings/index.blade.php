@extends('backend.app')
@section('title', 'Settings')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">System Settings</h3>

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
                    <span class="fw-medium">System</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card bg-white border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <ul class="ps-0 mb-4 list-unstyled d-flex flex-wrap gap-2 gap-lg-3">
                <li>
                    <a href="{{route('system_settings.index')}}"
                        class="btn btn-primary border border-primary bg-primary text-white py-2 px-3 fw-semibold">System
                        Settings</a>
                </li>
                {{-- <li>
                    <a href="{{route('system_settings.mail_get')}}"
                        class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">Mail
                        <Source:media:sizes></Source:media:sizes>Settings</a>
                </li> --}}
            </ul>

            <div class="mb-4">
                <h4 class="fs-20 mb-1">System Settings</h4>
                <p class="fs-15">Update your system and site details here.</p>
            </div>

            <form action="{{ route('system_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Title</label>
                            <div class="form-group position-relative">
                                <input type="text"
                                    class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                    name="title" value="{{ old('title', $settings->title ?? '') }}"
                                    placeholder="Enter Title here">
                            </div>
                            @error('title')
                                <div id="title-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">System Name</label>
                            <div class="form-group position-relative">
                                <input type="text"
                                    class="form-control text-dark ps-5 h-55 @error('system_name') is-invalid @enderror"
                                    name="system_name" value="{{ old('system_name', $settings->system_name ?? '') }}"
                                    placeholder="Enter system name here">
                            </div>
                            @error('system_name')
                                <div id="system_name-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Contact Number</label>
                            <div class="form-group position-relative">
                                <input type="text"
                                    class="form-control text-dark ps-5 h-55 @error('contact_number') is-invalid @enderror"
                                    name="contact_number" value="{{ old('contact_number', $settings->contact_number ?? '') }}"
                                    placeholder="Enter contact number here">
                            </div>
                            @error('contact_number')
                                <div id="contact_number-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Email</label>
                            <div class="form-group position-relative">
                                <input type="email"
                                    class="form-control text-dark ps-5 h-55 @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $settings->email ?? '') }}"
                                    placeholder="Enter email here">
                            </div>
                            @error('email')
                                <div id="email-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Copyright Text</label>
                            <div class="form-group position-relative">
                                <input type="text"
                                    class="form-control text-dark ps-5 h-55 @error('copyright_text') is-invalid @enderror"
                                    name="copyright_text" value="{{ old('copyright_text', $settings->copyright_text ?? '') }}"
                                    placeholder="Enter copyright text here">
                            </div>
                            @error('copyright_text')
                                <div id="copyright_text-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                  
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Description</label>
                            <div class="form-group position-relative">
                                <textarea class="form-control text-dark ps-5 h-55 @error('description') is-invalid @enderror"
                                    name="description"
                                    placeholder="Enter description here">{{ old('description', $settings->description ?? '') }}</textarea>
                            </div>
                            @error('description')
                                <div id="description-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>                    


                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label class="label text-secondary mb-1">Logo</label>
                            <input class="dropify form-control @error('logo') is-invalid @enderror" type="file"
                                name="logo"
                                data-default-file="{{ asset($settings->logo ?? 'backend/admin/assets/logo.png') }}">
                            @error('logo')
                                <div id="logo-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    

                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label class="label text-secondary mb-1">Favicon</label>
                            <input class="dropify form-control @error('favicon') is-invalid @enderror" type="file"
                                name="favicon"
                                data-default-file="{{ asset($settings->favicon ?? 'backend/admin/assets/logo.png') }}">
                            @error('favicon')
                                <div id="favicon-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap gap-3">
                            {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i class="ri-check-line text-white fw-medium"></i> Update</button>
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

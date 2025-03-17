@extends('backend.app')
@section('title', 'Settings')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Mail Settings</h3>

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
                        <span class="fw-medium">Mail</span>
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
                        <a href="{{ route('system_settings.mail_get') }}"
                            class="btn btn-primary border border-primary bg-primary text-white py-2 px-3 fw-semibold">Mail
                            <Source:media:sizes></Source:media:sizes>Settings
                        </a>
                    </li>
                </ul>

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Mail Settings</h4>
                    <p class="fs-15">Update your Mail details here.</p>
                </div>

                <form action="{{ route('system_settings.mail') }}" method="POST" >
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">MAIL MAILER</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('mail_mailer') is-invalid @enderror"
                                        name="mail_mailer" value="{{ env('MAIL_MAILER') }}" required
                                        placeholder="Enter your mailer name (e.g. smtp, sendmail, mailgun)">
                                </div>
                                @error('mail_mailer')
                                    <div id="mail_mailer-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">MAIL HOST</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('mail_host') is-invalid @enderror"
                                        name="mail_host" value="{{ env('MAIL_HOST') }}" required
                                        placeholder="Enter your mail hostname (e.g. smtp.example.com)">
                                </div>
                                @error('mail_host')
                                    <div id="mail_host-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">MAIL PORT</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('mail_port') is-invalid @enderror"
                                        name="mail_port" value="{{ env('MAIL_PORT') }}" required
                                        placeholder="Enter your mail port (e.g. 587, 2525)">
                                </div>
                                @error('mail_port')
                                    <div id="mail_port-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">MAIL USERNAME</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('mail_username') is-invalid @enderror"
                                        name="mail_username" value="{{ env('MAIL_USERNAME') }}" required
                                        placeholder="Enter your mail username">
                                </div>
                                @error('mail_username')
                                    <div id="mail_username-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">MAIL PASSWORD</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('mail_password') is-invalid @enderror"
                                        name="mail_password" value="{{ env('MAIL_PASSWORD') }}" required
                                        placeholder="Enter your mail password">
                                </div>
                                @error('mail_password')
                                    <div id="mail_password-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">MAIL ENCRYPTION</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('mail_encryption') is-invalid @enderror"
                                        name="mail_encryption" value="{{ env('MAIL_ENCRYPTION') }}" required
                                        placeholder="Enter your mail encryption (e.g. tls, ssl)">
                                </div>
                                @error('mail_encryption')
                                    <div id="mail_encryption-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">MAIL FROM ADDRESS</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('mail_from_address') is-invalid @enderror"
                                        name="mail_from_address" value="{{ env('MAIL_FROM_ADDRESS') }}" required
                                        placeholder="Enter your mail from address (e.g. noreply@example.com)">
                                </div>
                                @error('mail_from_address')
                                    <div id="mail_from_address-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                        class="ri-check-line text-white fw-medium"></i> Update Profile</button>
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

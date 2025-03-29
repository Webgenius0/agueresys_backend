@extends('backend.app')
@section('title', 'God Details')

@push('styles')
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h3 class="mb-0 text-primary">View God Details</h3>
        </div>

        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-5">
                <div class="d-flex gap-4 mb-4">
                    {{-- Thumbnail --}}
                    @if ($data->thumbnail)
                        <div class="thumbnail-container">
                            {{-- <h5 class="text-primary">Thumbnail</h5> --}}
                            <img src="{{ asset($data->thumbnail) }}" alt="{{ $data->title }}"
                                class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                        </div>
                    @endif

                    {{-- God Details --}}
                    <div class="god-details">
                        <h1 class="fs-25 mb-1 text-uppercase"><span class="font-weight-bold">God Title:</span>
                            {{ $data->title }}</h1>
                        <h4 class="fs-18 mb-1 text-muted"><span class="font-weight-bold">God Sub Title:</span>
                            {{ $data->sub_title }}</h4>
                        <h5 class="fs-15 text-dark"><span class="font-weight-bold">Description Title:</span>
                            {{ $data->description_title }}</h5>
                        <p class="fs-15 text-muted"><span class="font-weight-bold">Description:</span>
                            {{ $data->description }}</p>
                        <p class="fs-15 text-dark"><span class="font-weight-bold">Aspect Description:</span>
                            {{ $data->aspect_description }}</p>
                        <p class="fs-15 text-dark"><span class="font-weight-bold">Viewers:</span>
                            {{ $data->viewers_count }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8 d-flex gap-4">
                        <p><strong>Status:</strong>
                            <span class="badge {{ $data->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($data->status) }}
                            </span>
                        </p>
                        <p><strong>Created At:</strong> {{ $data->created_at->diffForHumans() }}</p>
                        <p><strong>Updated At:</strong> {{ $data->updated_at->diffForHumans() }}</p>
                    </div>

                </div>
                <hr />
                {{-- Abilities images --}}
                <div class="mt-4">
                    @if ($data->abilities->count() > 0)
                        <h5 class="text-primary text-center">Abilities</h5>
                        <div class="row">
                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Thumbnail</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->abilities as $ability)
                                        <tr>
                                            <td>
                                                @if ($ability->ability_thumbnail)
                                                    <img src="{{ asset($ability->ability_thumbnail) }}"
                                                        alt="{{ $ability->name }}" class="img-thumbnail rounded"
                                                        style="max-width: 100px; height: auto;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $ability->description ?? 'No Description' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    @endif
                </div>
                <hr />
                {{-- Display Roles --}}
                @if ($data->godRoles->count() > 0)
                    <div class="mt-4">
                        {{-- <h5 class="text-primary fw-bold">
            <i class="fas fa-user-shield me-2"></i> Roles
        </h5> --}}
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover text-center align-middle shadow-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-users me-2"></i> Role</th>
                                        <th><i class="fas fa-thumbs-up me-2 text-success"></i> Upvotes</th>
                                        <th><i class="fas fa-thumbs-down me-2 text-danger"></i> Downvotes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->godRoles as $godRole)
                                        <tr>
                                            <td class="fw-semibold">{{ $godRole->role->name }}</td>
                                            <td class="text-success fw-bold">
                                                <i class="fas fa-arrow-up me-1"></i> {{ $godRole->upvotes_count }}
                                            </td>
                                            <td class="text-danger fw-bold">
                                                <i class="fas fa-arrow-down me-1"></i> {{ $godRole->downvotes_count }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
@endpush

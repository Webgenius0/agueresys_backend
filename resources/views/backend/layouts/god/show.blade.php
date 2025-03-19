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
           <hr/>
                {{-- Abilities images --}}
                <div class="mt-4">
                    @if ($data->abilities->count() > 0)
                        <h5 class="text-primary">Abilities</h5>
                        <div class="row">
                            @foreach ($data->abilities as $ability)
                                <div class="col-md-1 text-center gap-3">
                                    <p class="font-weight-bold">{{ $ability->name }}</p>
                                    @if ($ability->ability_thumbnail)
                                        <img src="{{ asset($ability->ability_thumbnail) }}" alt="{{ $ability->name }}"
                                            class="img-fluid rounded" style="max-width: 100px;">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <hr/>
                {{-- Display Roles --}}
                @if ($data->godRoles->count() > 0)
                    <div class="mt-4">
                        <h5 class="text-primary">Roles</h5>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-users"></i> Role</th> {{-- Icon for Role --}}
                                    <th><i class="fas fa-thumbs-up"></i> Upvotes</th> {{-- Icon for Upvotes --}}
                                    <th><i class="fas fa-thumbs-down"></i> Downvotes</th> {{-- Icon for Downvotes --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->godRoles as $godRole)
                                    <tr>
                                        <td>{{ $godRole->role->name }}</td>
                                        <td>{{ $godRole->upvotes_count }}</td>
                                        <td>{{ $godRole->downvotes_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
@endpush

@extends('backend.app')
@section('title', 'God Details')

@push('styles')
   
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">View God Details</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h4 class="fs-20 mb-1">{{ $data->title }}</h4>
                    <h4 class="fs-16 mb-1">{{ $data->sub_title }}</h4>
                    <p class="fs-15">{{ $data->description }}</p>
                    <p class="fs-15">{{ $data->description_title }}</p>
                    <p class="fs-15">{{ $data->aspect_description }}</p>
                </div>

                <div class="row">
                    <div class="col-md-4 d-flex gap-3">
                        <p><strong>Status:</strong> <span class="badge {{ $data->status == 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($data->status) }}</span></p>
                        <p><strong>Created At:</strong> {{ $data->created_at->diffForHumans()}}</p>
                        <p><strong>Updated At:</strong> {{ $data->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

                @if($data->thumbnail)
                    <div class="mt-4">
                        <h5>Thumbnail</h5>
                        <img src="{{ asset($data->thumbnail) }}" alt="{{ $data->title }}" class="img-fluid rounded" style="max-width: 200px;">
                    </div>
                @endif

                {{-- Display Abilities --}}
                @if($data->abilities->count() > 0)
                    <div class="mt-4">
                        <h5>Abilities</h5>
                        <div class="row">
                            @foreach($data->abilities as $ability)
                                <div class="col-md-1 text-center">
                                    <p><strong>{{ $ability->name }}</strong></p>
                                    @if($ability->ability_thumbnail)
                                        <img src="{{ asset($ability->ability_thumbnail) }}" alt="{{ $ability->name }}" class="img-fluid rounded" style="max-width: 100px;">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Display Roles --}}
                @if($data->roles->count() > 0)
                    <div class="mt-4">
                        <h5>Roles</h5>
                        <ul>
                            @foreach($data->roles as $role)
                                <li>{{ $role->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
@endpush

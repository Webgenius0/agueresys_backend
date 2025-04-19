@extends('backend.app')
@section('title', 'God Details')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h3 class="mb-0 ">View Role Details</h3>
        </div>

        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-5">
                <!-- ROLE & GODS TABLE -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3"><div class="mb-4">
                    <h3 class=" fw-bold">
                        <i class="fas fa-user-tag me-2"></i> Role: {{ $role->name }}
                    </h3>
                </div>
                <div class="mb-4">
                    <a href="{{ route('roles.index') }}" type="button" class="btn btn-primary">Back </a>
                </div></div>
                

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>SL</th>
                                <th>God Image</th>
                                <th>God Name</th>
                                <th>Upvotes</th>
                                <th>Downvotes</th>
                                <th>Net Vote</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gods as $key => $god)
                                @foreach($god->godRoles as $gr)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <img src="{{ asset($god->thumbnail) }}" width="50" height="50" class="rounded" alt="God Image">
                                        </td>
                                        <td>{{ $god->title }}</td>
                                        <td class="text-success fw-bold">
                                            <i class="fas fa-thumbs-up me-1"></i> {{ $gr->upvotes }}
                                        </td>
                                        <td class="text-danger fw-bold">
                                            <i class="fas fa-thumbs-down me-1"></i> {{ $gr->downvotes }}
                                        </td>
                                        <td class="text-primary fw-bold">
                                            <i class="fas fa-chart-line me-1"></i> {{ $gr->net_votes }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
@endpush

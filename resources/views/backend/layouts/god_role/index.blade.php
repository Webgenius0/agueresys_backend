@extends('backend.app')
@section('title', 'Vote Reset')

@push('styles')
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Vote Reset</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Votes</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Vote Reset</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-lg-12">
            <div class="col-xl-12">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-4" style="padding-bottom: 0 !important;">
                        <div class="mb-3 mb-lg-4">
                            <h3 class="mb-0">Roles List</h3>
                        </div>
                        <div class="row">
                            @forelse ($roles as $role)
                                <a href="{{ route('roles.show', $role->id) }}"
                                    class="col-xxl-4 col-xl-4 col-sm-6 cursor-pointer" style="cursor: pointer;">
                                    <div
                                        class="card bg-success bg-opacity-10 border-success border-opacity-10 rounded-3 mb-4 stats-box style-three">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-19">
                                                <div class="flex-shrink-0">
                                                    <i class="material-symbols-outlined fs-40 text-success">folder_open</i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <span>{{ $role->name }}</span>
                                                    {{-- <h3 class="fs-20 mt-1 mb-0">{{$role->total_votes}}</h3> --}}
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                                                <span class="fs-12">Click To View</span>
                                                <span class="count down fw-medium ms-0">{{ $role->total_votes }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                            @endforelse


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/custom-actions.js') }}"></script>
    <script>
        // Use the status change alert
        function changeStatus(event, id) {
            event.preventDefault();
            let statusUrl = '{{ route('gods.status', ':id') }}';
            showStatusChangeAlert(id, statusUrl);
        }

        // Use the delete confirm alert
        function deleteRecord(event, id) {
            event.preventDefault();
            let deleteUrl = '{{ route('gods.destroy', ':id') }}';
            showDeleteConfirm(id, deleteUrl);
        }
    </script>
@endpush

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
            <div class="card bg-white border-0 rounded-3 mb-12">
                <div class="card-body p-4">
                    <h4 class="fs-18 mb-4">Vote Reset</h4>
                    <ul class="nav nav-tabs mb-4" id="myTab7" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="preview7-tab" data-bs-toggle="tab"
                                data-bs-target="#preview7-tab-pane" type="button" role="tab"
                                aria-controls="preview7-tab-pane" aria-selected="true">GOD FOR THE ROLE</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="code7-tab" data-bs-toggle="tab" data-bs-target="#code7-tab-pane"
                                type="button" role="tab" aria-controls="code7-tab-pane" aria-selected="false"
                                tabindex="-1">ROLE FOR ALL SPECIFIC GOD</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="code8-tab" data-bs-toggle="tab" data-bs-target="#code8-tab-pane"
                                type="button" role="tab" aria-controls="code8-tab-pane" aria-selected="false"
                                tabindex="-1">COUNTER PICK FOR ALL GOD</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent7">
                        <div class="tab-pane fade active show" id="preview7-tab-pane" role="tabpanel"
                            aria-labelledby="preview7-tab" tabindex="0">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('vote.reset_role_votes') }}" type="button"
                                    class="btn btn-primary fw-medium text-white py-3 px-4 w-100">RESET GOD FOR THE ROLE
                                    VOTES</a>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="code7-tab-pane" role="tabpanel" aria-labelledby="code7-tab"
                            tabindex="0">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('vote.reset_god_votes') }}" type="button"
                                    class="btn btn-primary fw-medium text-white py-3 px-4 w-100">RESET
                                    ROLE FOR ALL SPECIFIC GOD VOTES</a>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="code8-tab-pane" role="tabpanel" aria-labelledby="code8-tab"
                            tabindex="0">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('vote.reset_counter_votes') }}" type="button"
                                    class="btn btn-primary fw-medium text-white py-3 px-4 w-100">RESET
                                    COUNTER PICK FOR ALL GOD VOTES</a>

                            </div>
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

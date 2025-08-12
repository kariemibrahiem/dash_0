@extends('layouts/contentNavbarLayout')

@push('styles')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('dashboard-analytics') }}">Dashboard</a> /
  </span> {{ trns('profile') }}
</h4>

<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
              
                <h4 class="mb-1 text-capitalize">{{ $admin->user_name }}</h4>
                <h6 class="text-muted">{{ $admin->email }}</h6>
                <button class="btn btn-secondary mt-3 editBtn">
                    <a href="{{ route('admins.edit' ,$admin->id) }}" class="text-white text-decoration-none">
                        <i class="bi bi-pencil-square me-1"></i> {{ trns('update_Profile') }}
                    </a>
                </button>
            </div>
        </div>
    </div>

    <!-- Information Card -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">
                {{ trns('information') }}
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ trns('personal_information') }}</h5>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <tbody>
                            <tr>
                                <td><strong>{{ trns('name') }}:</strong> {{ $admin->user_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trns('email') }}:</strong> {{ $admin->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trns('register_date') }}:</strong> {{ $admin->created_at->diffForHumans() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editOrCreate" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trns('admin_data') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trns('Close') }}"></button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Ajax loaded content -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Handle tab click (if you add tabs later)
    $('.tab-action').on('click', function(e) {
        e.preventDefault();
        $('.tab-action').removeClass('active show');
        $('.tab-pane').removeClass('active show');
        $(this).addClass('active show');
        $($(this).attr('href')).addClass('active show');
    });
</script>
@endpush

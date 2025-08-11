@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - ' . $route)

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('dashboard-analytics') }}">Dashboard</a> /
  </span> {{ $route }}
</h4>

<hr class="my-5" />

<!-- Card -->
<div class="card">
  <h5 class="card-header d-flex justify-content-between align-items-center flex-wrap">
    <div class="d-flex align-items-center mb-2 mb-md-0">
      <span class="me-3 fw-bold">{{ ucfirst($route) }}</span>
      <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
        <li class="avatar avatar-xs pull-up me-1">
          <img src="{{ asset('assets/img/avatars/5.png') }}" class="rounded-circle">
        </li>
        <li class="avatar avatar-xs pull-up me-1">
          <img src="{{ asset('assets/img/avatars/6.png') }}" class="rounded-circle">
        </li>
        <li class="avatar avatar-xs pull-up me-1">
          <img src="{{ asset('assets/img/avatars/7.png') }}" class="rounded-circle">
        </li>
      </ul>
    </div>
    <div>
      <button class="btn btn-success" id="bulkStatusUpdate">{{trns("Update_Selected")}}</button>
    </div>
  </h5>

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered" id="usersTable">
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Users') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Actions') }}</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route($route . ".index") }}',
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                }
            },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function () {
                    return `
                        <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li class="avatar avatar-xs pull-up me-1"><img src="{{ asset('assets/img/avatars/5.png') }}" class="rounded-circle"></li>
                            <li class="avatar avatar-xs pull-up me-1"><img src="{{ asset('assets/img/avatars/6.png') }}" class="rounded-circle"></li>
                            <li class="avatar avatar-xs pull-up me-1"><img src="{{ asset('assets/img/avatars/7.png') }}" class="rounded-circle"></li>
                        </ul>`;
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function (data) {
                    let labelClass = 'bg-label-secondary';
                    if (data === 'Active') labelClass = 'bg-label-primary';
                    else if (data === 'Completed') labelClass = 'bg-label-success';
                    else if (data === 'Scheduled') labelClass = 'bg-label-info';
                    else if (data === 'Pending') labelClass = 'bg-label-warning';

                    return `<span class="badge ${labelClass} me-1">${data}</span>`;
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (id) {
                    return `
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/${'{{ $route }}'}/${id}/edit"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteUser(${id})"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        </div>`;
                }
            }
        ],
        order: [[1, "DESC"]],
        language: {
            sZeroRecords: "No records found",
            sProcessing: "Processing...",
            sSearch: "Search:",
            oPaginate: {
                sPrevious: "Previous",
                sNext: "Next"
            }
        }
    });

    $('#select-all').on('click', function () {
        const rows = table.rows({ search: 'applied' }).nodes();
        $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
    });

    $('#usersTable tbody').on('change', 'input.row-checkbox', function () {
        if (!this.checked) {
            $('#select-all').prop('checked', false);
        }
    });

    $('#bulkStatusUpdate').on('click', function () {
        const selectedIds = [];
        $('input.row-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('{{ trns("Please select at least one user.") }}');
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ route($route . ".updateColumnSelected") }}',
            data: {
                _token: '{{ csrf_token() }}',
                ids: selectedIds,
                status: 'Active' // or 1, depending on your backend
            },
            success: function (data) {
                if (data.status === 200) {
                    toastr.success("Updated Successfully");
                    table.ajax.reload();
                } else {
                    toastr.error("Something went wrong");
                }
            },
            error: function () {
                toastr.error("AJAX Error");
            }
        });
    });
});

function deleteUser(id) {
    if (!confirm("Are you sure you want to delete this user?")) return;
    // Implement delete logic or AJAX here
    toastr.info("Delete functionality not implemented");
}
</script>
@endpush

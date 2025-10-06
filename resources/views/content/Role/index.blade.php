@extends('layouts/contentNavbarLayout')

@section('title', trns('Role'))

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">
        <a href="{{ route('dashboard-analytics') }}">{{ trns('Dashboard') }}</a> /
    </span> {{ trns("Role") }}
</h4>

<hr class="my-4" />

<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <span class="fw-bold">{{ trns($route) }}</span>
        <div>
            <button class="btn btn-success" id="bulkStatusUpdate">{{ trns("Update_Selected") }}</button>
            <button class="btn btn-danger" id="deleteSelected">{{ trns("Delete_Selected") }}</button>
            <a href="{{ route($route . '.create') }}" class="btn btn-primary">{{ trns('Add_New') }} {{ $route }}</a>
        </div>
    </h5>

    <!-- Delete Selected Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trns('confirm_deletion') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ trns('are_you_sure_you_want_to_delete_selected_items') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">{{ trns('delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="rolesTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>{{ trns('role') }}</th>
                        <th>{{ trns('permissions') }}</th>
                        <th>{{ trns('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#rolesTable').DataTable({
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
            { data: 'permissions', name: 'permissions' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, "ASC"]],
        language: {
            sZeroRecords: "No records found",
            sProcessing: "Processing...",
            sSearch: "Search:",
            oPaginate: { sPrevious: "Previous", sNext: "Next" }
        }
    });

    // Select all checkboxes
    $('#select-all').on('click', function () {
        const rows = table.rows({ search: 'applied' }).nodes();
        $('input.row-checkbox', rows).prop('checked', this.checked);
    });

    $('#rolesTable tbody').on('change', 'input.row-checkbox', function () {
        if (!this.checked) $('#select-all').prop('checked', false);
    });

    // Bulk Status Update
    $('#bulkStatusUpdate').on('click', function () {
        const selectedIds = $('input.row-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (selectedIds.length === 0) { alert('{{ trns("Please select at least one role.") }}'); return; }

        $.ajax({
            type: 'POST',
            url: '{{ route($route . ".updateColumnSelected") }}',
            data: { _token: '{{ csrf_token() }}', ids: selectedIds },
            success: function(data) {
                if(data.status === 200) {
                    toastr.success("Updated Successfully");
                    table.ajax.reload();
                } else { toastr.error("Something went wrong"); }
            },
            error: function() { toastr.error("AJAX Error"); }
        });
    });

    // Bulk Delete
    $('#deleteSelected').on('click', function () {
        const selected = $('input.row-checkbox:checked').map(function(){ return $(this).val(); }).get();
        if(selected.length === 0){ alert('{{ trns("Please select at least one role.") }}'); return; }

        let deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();

        $('#confirm-delete-btn').off('click').on('click', function() {
            $.ajax({
                url: '{{ route($route . ".destroySelected") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', ids: selected },
                success: function(response){
                    if(response.status === 200){
                        toastr.success('{{ trns("deleted_successfully") }}');
                        deleteModal.hide();
                        $('#select-all').prop('checked', false);
                        $('.row-checkbox').prop('checked', false);
                        table.ajax.reload();
                    } else { toastr.error('{{ trns("something_went_wrong") }}'); }
                },
                error: function(){ toastr.error('{{ trns("something_went_wrong") }}'); deleteModal.hide(); }
            });
        });
    });
});
</script>
@endpush

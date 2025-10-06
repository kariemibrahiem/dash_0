@extends('layouts/contentNavbarLayout')

@section('title', trns('Create Role'))

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('Role.index') }}">{{ trns('Roles') }}</a> /
  </span> {{ trns('Create Role') }}
</h4>

<div class="card">
  <div class="card-body">
    <form action="{{ route($route.'.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row g-3 mb-3">

        <!-- Role Name -->
        <div class="col-md-6">
          <label for="role" class="form-label">{{ trns('Role') }}</label>
          <input type="text" class="form-control @error('role') is-invalid @enderror" 
                 id="role" name="role" value="{{ old('role') }}" required>
          @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Select All Permissions for all groups -->
        <div class="col-md-12 mb-3">
          <input type="checkbox" id="selectAllPermissions"> {{ trns('Select All Permissions') }}
        </div>

        <!-- Permissions as Checkboxes -->
        <div class="col-md-12">
          <label class="form-label">{{ trns('Permissions') }}</label>

          @php
            $grouped = $permissions->groupBy(function($perm) {
                return explode('_', $perm->name)[0]; // group by first part
            });
          @endphp

          @foreach($grouped as $group => $perms)
            <div class="mb-3 border p-2 rounded">
              <strong>{{ ucfirst($group) }}</strong>
              <div>
                <input type="checkbox" class="select-all" data-group="{{ $group }}"> {{ trns('Select All') }}
              </div>
              <div class="ms-3 mt-1">
                @foreach($perms as $permission)
                  <div class="form-check form-check-inline">
                    <input class="form-check-input {{ $group }}" 
                           type="checkbox" 
                           name="permission_id[]" 
                           value="{{ $permission->id }}" 
                           id="perm_{{ $permission->id }}"
                           {{ in_array($permission->id, old('permission_id', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach

          @error('permission_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <!-- Status -->
        <div class="col-md-6">
          <label for="status" class="form-label">{{ trns('Status') }}</label>
          <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>{{ trns('Active') }}</option>
            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>{{ trns('Inactive') }}</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>

      <button type="submit" class="btn btn-primary">{{ trns('Create') }}</button>
      <a href="{{ route($route.'.index') }}" class="btn btn-secondary">{{ trns('Cancel') }}</a>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Select All for each group
  document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
      let group = this.dataset.group;
      document.querySelectorAll('.' + group).forEach(function(checkbox) {
        checkbox.checked = selectAllCheckbox.checked;
      });
    });
  });

  // Select All for all permissions
  document.getElementById('selectAllPermissions').addEventListener('change', function() {
    let checked = this.checked;
    document.querySelectorAll('input[name="permission_id[]"]').forEach(function(checkbox) {
      checkbox.checked = checked;
    });
    // تحديث كل الـSelect All لكل مجموعة
    document.querySelectorAll('.select-all').forEach(function(groupCheckbox) {
      groupCheckbox.checked = checked;
    });
  });
});
</script>
@endpush

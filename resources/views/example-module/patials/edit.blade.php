@extends('layouts/contentNavbarLayout')

@section('title', trns('Edit Admin'))

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('users.index') }}">{{ trns('Users') }}</a> /
  </span> {{ trns('Edit Admin') }}
</h4>

<div class="card">
  <div class="card-body">
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row m-3">

        <div class="col-5">
          <label class="form-label" for="name">{{ trns('username') }}</label>
          <input type="text" class="form-control" id="name" name="name"
                 value="{{ old('name', $user->name) }}" required>
        </div>

       

        <div class="col-5">
          <label class="form-label" for="image">{{ trns('image') }}</label>
          <input class="form-control" type="file" id="image" name="image">
          @if($user->image)
            <div class="mt-2">
              <img src="{{ asset('storage/' . $user->image) }}" alt="Current Image"
                   class="rounded" width="100">
            </div>
          @endif
        </div>

      </div>

      <button type="submit" class="btn btn-primary">{{ trns('update') }}</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ trns('cancel') }}</a>
    </form>
  </div>
</div>
@endsection

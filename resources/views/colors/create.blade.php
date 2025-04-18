@extends('layout.master')

@section('content')
<div class="container">
    <h2>Add New Color</h2>
    <form action="{{ route('colors.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Color Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('colors.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

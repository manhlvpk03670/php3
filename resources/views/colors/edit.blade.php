@extends('layout.master')

@section('content')
<div class="container">
    <h2>Chỉnh sửa màu sắc</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('colors.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên màu</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $color->name }}" required>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('colors.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection

@extends('layout.master')

@section('content')
<form action="{{ route('brands.store') }}" method="POST">
    @csrf
    <label for="name">Tên Brand:</label>
    <input type="text" name="name" required>

    <label for="category_id">Danh mục:</label>
    <select name="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <button type="submit">Thêm</button>
</form>

@endsection

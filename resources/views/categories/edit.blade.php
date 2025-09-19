@extends('layouts.sleeky')

@section('title', 'Edit Category')

@section('content')
<section class="section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Edit Category</h2>
        </div>
        <div class="contact-content">
            <div class="contact-form">
                <form method="POST" action="{{ route('categories.update', $category->category_id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" value="{{ old('category_name', $category->category_name) }}">
                        @error('category_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="active">Status</label>
                        <select name="active" id="active" class="form-control">
                            <option value="1" {{ old('active', $category->active) == '1' ? 'selected' : '' }}>Enable</option>
                            <option value="0" {{ old('active', $category->active) == '0' ? 'selected' : '' }}>Disable</option>
                        </select>
                        @error('active')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">Update Category</button><br /><br />
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
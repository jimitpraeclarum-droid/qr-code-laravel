@extends('layouts.sleeky')

@section('title', 'Manage Categories')

@section('content')
<section class="section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Manage Categories</h2>
        </div>

        <div class="card mb-4">
            <div class="card-header">Add New Category</div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" value="{{ old('category_name') }}">
                        @error('category_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="active">Status</label>
                        <select name="active" id="active" class="form-control">
                            <option value="1" {{ old('active') == '1' ? 'selected' : '' }}>Enable</option>
                            <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Disable</option>
                        </select>
                        @error('active')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">Add Category</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Existing Categories</div>
            <div class="card-body">
                <div class="div-table">
                    <div class="div-table-header">
                        <div class="div-table-cell">ID</div>
                        <div class="div-table-cell">Category Name</div>
                        <div class="div-table-cell">Status</div>
                        <div class="div-table-cell">Created At</div>
                        <div class="div-table-cell">Actions</div>
                    </div>
                    @forelse($categories as $category)
                        <div class="div-table-row">
                            <div class="div-table-cell" data-label="ID">{{ $category->category_id }}</div>
                            <div class="div-table-cell" data-label="Category Name">{{ $category->category_name }}</div>
                            <div class="div-table-cell" data-label="Status">{{ $category->active ? 'Enabled' : 'Disabled' }}</div>
                            <div class="div-table-cell" data-label="Created At">{{ $category->created_at->format('Y-m-d H:i') }}</div>
                            <div class="div-table-cell" data-label="Actions">
                                <a href="{{ route('categories.edit', $category->category_id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <button class="btn btn-sm btn-danger" style="margin-left:30px;" onclick="openDeleteModal('{{ $category->category_id }}')"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @empty
                        <div class="div-table-row">
                            <div class="div-table-cell text-center" colspan="5">No categories found.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h2>Confirm Deletion</h2>
                <p>Are you sure you want to delete this category?</p>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.modal.modal-active {
    display: flex !important;
}
</style>
@endpush

@push('scripts')
<script>
function openDeleteModal(categoryId) {
    openModal('deleteModal');
    document.getElementById('deleteForm').action = `/categories/${categoryId}`;
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('modal-active');
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('modal-active');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 200);
}
</script>
@endpush

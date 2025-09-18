@extends('layouts.sleeky')

@section('title', 'My QR Codes')

@section('content')
<div class="with-left-panel">
    <div class="left-panel">
        @include('layouts.left-menu')
    </div>
    <div class="main-panel">
        <section class="section">
            <div class="section-container">
                <div class="section-header">
                    <h2 class="section-title">My QR Codes</h2>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="{{ route('qr.index') }}" method="GET">
                            <div class="input-group" style="    display: flex;   gap: 20px;">
                                <input type="text" name="search" class="form-control" placeholder="Search by title, content..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="submit-btn" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="text-muted">Showing {{ $qrCodes->firstItem() }} to {{ $qrCodes->lastItem() }} of {{ $qrCodes->total() }} results</p>
                    </div>
                </div>

                <div class="qr-grid-list">
                    <div class="qr-grid-header">
                        <div class="qr-grid-cell">Title</div>
                        <div class="qr-grid-cell">Content</div>
                        <div class="qr-grid-cell">Last Updated</div>
                        <div class="qr-grid-cell">Actions</div>
                    </div>
                    @forelse ($qrCodes as $qrCode)
                        <div class="qr-grid-row" id="qr-card-{{ $qrCode->qrcode_id }}">
                            <div class="qr-grid-cell" data-label="Title">{{ $qrCode->title ?? 'Untitled' }}</div>
                            <div class="qr-grid-cell" data-label="Content">{{ Str::limit($qrCode->content, 50) }}</div>
                            <div class="qr-grid-cell" data-label="Last Updated">{{ $qrCode->updated_at->format('M d, Y') }}</div>
                            <div class="qr-grid-cell qr-grid-actions" data-label="Actions">
                                <a href="#" class="action-icon" title="View" onclick="openViewQrModal('{{ $qrCode->title }}', '{{ asset($qrCode->qrcode_image) }}')">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <div class="action-menu-container">
                                    <button class="action-btn" onclick="toggleActions(this)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="action-menu">
                                        <a href="#" onclick="openRenameModal('{{ $qrCode->qrcode_id }}', '{{ $qrCode->title }}')">Rename</a>
                                        <a href="{{route('qr.edit',$qrCode->qrcode_id)}}" >Edit</a>
                                        <a href="#" onclick="openDeleteModal('{{ $qrCode->qrcode_id }}')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <p>No QR codes found. <a href="{{ route('qr.create') }}">Create one now!</a></p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>

        <div class="d-flex justify-content-center mt-4">
            {{ $qrCodes->links() }}
        </div>
    </div>
</section>

<!-- Rename Modal -->
<div id="renameModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('renameModal')">&times;</span>
        <h2>Rename QR Code</h2>
        <form id="renameForm" onsubmit="renameQrCode(event)" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="newTitle">Title</label>
                <input type="text" id="newTitle" name="title" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this QR code? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <form id="deleteForm" onsubmit="deleteQrCode(event)" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>

<!-- View QR Modal -->
<div id="viewQrModal" class="modal">
    <div class="modal-content text-center">
        <span class="close" onclick="closeModal('viewQrModal')">&times;</span>
        <h2 id="viewQrTitle">QR Code</h2>
        <div class="qr-code-image-container">
            <img id="viewQrImage" src="" alt="QR Code">
        </div>
        <a id="viewQrDownload" href="" download="" class="btn btn-primary">
            <i class="fas fa-download"></i> Download QR Code
        </a>
    </div>
</div>

@endsection

@push('styles')
<style>
.qr-grid-list {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.qr-grid-header, .qr-grid-row {
    display: grid;
    grid-template-columns: 2fr 3fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    align-items: center;
        justify-content: space-between;
    justify-items: start;
}
.qr-grid-header {
    font-weight: 600;
    color: #4a5568;
    background: #f8fafc;
}
.qr-grid-row:last-child {
    border-bottom: none;
}
.qr-grid-cell {
    word-break: break-word;
}
.qr-grid-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    justify-content: flex-end;
}
.action-icon {
    color: #64748b;
    font-size: 1.1rem;
    transition: color 0.2s;
}
.action-icon:hover {
    color: #3b82f6;
}
.action-menu-container {
    position: relative;
}
.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    color: #666;
    padding: 0.5rem;
}
.action-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 10;
    width: 120px;
}
.action-menu.show {
    display: block;
}
.action-menu a {
    display: block;
    padding: 0.75rem 1rem;
    color: #333;
    text-decoration: none;
    font-size: 0.9rem;
}
.action-menu a:hover {
    background: #f0f0f0;
}
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .qr-grid-header {
        display: none;
    }
    .qr-grid-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
    }
    .qr-grid-cell {
        display: flex;
        justify-content: space-between;
    }
    .qr-grid-cell::before {
        content: attr(data-label);
        font-weight: 600;
        color: #4a5568;
        margin-right: 1rem;
    }
    .qr-grid-actions {
        justify-content: flex-start;
    }
}

p.text-muted{
    text-align: end;
    padding-top: 15px;
}
</style>
@endpush

@push('scripts')
<script>
function toggleActions(btn) {
    const menu = btn.nextElementSibling;
    document.querySelectorAll('.action-menu.show').forEach(m => {
        if (m !== menu) m.classList.remove('show');
    });
    menu.classList.toggle('show');
    const closeMenuHandler = (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
            document.removeEventListener('click', closeMenuHandler);
        }
    };
    document.addEventListener('click', closeMenuHandler);
}
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'flex';
    setTimeout(() => { modal.classList.add('active'); }, 10);
}
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('active');
    setTimeout(() => { modal.style.display = 'none'; }, 200);
}
function openRenameModal(qrId, currentTitle) {
    openModal('renameModal');
    document.getElementById('renameForm').setAttribute('data-qrid', qrId);
    document.getElementById('newTitle').value = currentTitle;
}
function openDeleteModal(qrId) {
    openModal('deleteModal');
    document.getElementById('deleteForm').action = `/qrcodes/${qrId}`;
}
function openViewQrModal(title, imageUrl) {
    document.getElementById('viewQrTitle').textContent = title;
    document.getElementById('viewQrImage').src = imageUrl;
    document.getElementById('viewQrDownload').href = imageUrl;
    document.getElementById('viewQrDownload').download = title.replace(/[^a-zA-Z0-9]/g, '_') + '_qr.png';
    openModal('viewQrModal');
}
async function renameQrCode(event) {
    event.preventDefault();
    const form = event.target;
    const qrId = form.getAttribute('data-qrid');
    const formData = new FormData(form);
    const response = await fetch(`/qrcodes/${qrId}/rename`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    });
    if (response.ok) {
        const data = await response.json();
        document.getElementById(`qr-card-${qrId}`).querySelector('[data-label="Title"]').textContent = data.title;
        closeModal('renameModal');
    } else {
        alert('Failed to rename QR code.');
    }
}
async function deleteQrCode(event) {
    event.preventDefault();
    const form = document.getElementById('deleteForm');
    const formData = new FormData(form);
    const response = await fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    });
    if (response.ok) {
        const data = await response.json();
        const urlParts = form.action.split('/');
        const qrId = urlParts[urlParts.length - 1];
        document.getElementById(`qr-card-${qrId}`).remove();
        closeModal('deleteModal');
    } else {
        alert('Failed to delete QR code.');
    }
}
// Close modals on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal('renameModal');
        closeModal('deleteModal');
        closeModal('viewQrModal');
    }
});

// Auto-open view modal for new QR if present in URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const newQrId = urlParams.get('new_qr');
    if (newQrId) {
        const qrRow = document.getElementById('qr-card-' + newQrId);
        if (qrRow) {
            // Get title and image from row
            const title = qrRow.querySelector('[data-label="Title"]').textContent;
            // Find the image URL from the view button's onclick
            const viewBtn = qrRow.querySelector('.action-icon[title="View"]');
            if (viewBtn) {
                // Extract image URL from onclick string
                const onclick = viewBtn.getAttribute('onclick');
                const match = onclick.match(/,\s*'([^']+)'\)/);
                const imageUrl = match ? match[1] : '';
                openViewQrModal(title, imageUrl);
            }
        }
    }
});
</script>
@endpush
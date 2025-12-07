<x-pharmacy-layout>
    <x-slot name="header">Drug Inventory</x-slot>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title">Drug Inventory</h4>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDrugModal">
                        <i class="iconoir-plus-circle me-1"></i> Add New Drug
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Drug Name</th>
                            <th>Price (₦)</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drugs as $drug)
                            <tr>
                                <td class="fw-medium">{{ $drug->name }}</td>
                                <td>{{ number_format($drug->price, 2) }}</td>
                                <td>
                                    <span class="fw-bold {{ $drug->stock < 10 ? 'text-danger' : 'text-dark' }}">
                                        {{ $drug->stock }}
                                    </span>
                                </td>
                                <td>
                                    @if($drug->stock == 0)
                                        <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
                                    @elseif($drug->stock < 10)
                                        <span class="badge bg-warning-subtle text-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editDrugModal{{ $drug->id }}">
                                        Edit
                                    </button>

                                    <!-- Edit Drug Modal -->
                                    <div class="modal fade" id="editDrugModal{{ $drug->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Drug: {{ $drug->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('pharmacy.portal.inventory.update', $drug) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Price (₦)</label>
                                                            <input type="number" step="0.01" class="form-control" name="price" value="{{ $drug->price }}" min="0" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Stock Level</label>
                                                            <input type="number" class="form-control" name="stock" value="{{ $drug->stock }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No drugs found in inventory.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $drugs->links() }}
            </div>
        </div>
    </div>

    <!-- Add Drug Modal -->
    <div class="modal fade" id="addDrugModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Drug</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pharmacy.portal.inventory.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Drug Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (₦)</label>
                            <input type="number" step="0.01" class="form-control" name="price" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" class="form-control" name="stock" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Drug</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-pharmacy-layout>

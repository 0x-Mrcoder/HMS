<x-pharmacy-layout>
    <x-slot name="header">Drug Inventory</x-slot>

    <div class="card">
        <div class="card-body">
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
                                        data-bs-target="#updateStockModal{{ $drug->id }}">
                                        Update Stock
                                    </button>

                                    <!-- Update Stock Modal -->
                                    <div class="modal fade" id="updateStockModal{{ $drug->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Stock: {{ $drug->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('pharmacy.portal.inventory.update', $drug) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">New Stock Level</label>
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
</x-pharmacy-layout>

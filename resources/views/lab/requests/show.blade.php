<x-lab-layout>
    <x-slot name="header">Process Test Request</x-slot>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Patient Details</h4>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $labTest->visit->patient->photo_url ?? asset('rizz-assets/images/users/avatar-1.jpg') }}" alt="" class="thumb-lg rounded-circle mb-3">
                    <h5 class="mb-1">{{ $labTest->visit->patient->first_name }} {{ $labTest->visit->patient->last_name }}</h5>
                    <p class="text-muted mb-3">{{ $labTest->visit->patient->hospital_id }}</p>
                    
                    <div class="text-start mt-4">
                        <p class="mb-2"><i class="iconoir-calendar me-2"></i> Age: {{ \Carbon\Carbon::parse($labTest->visit->patient->date_of_birth)->age }} years</p>
                        <p class="mb-2"><i class="iconoir-user me-2"></i> Gender: {{ ucfirst($labTest->visit->patient->gender) }}</p>
                        <p class="mb-0"><i class="iconoir-wallet me-2"></i> Wallet: <span class="fw-bold text-success">₦{{ number_format($labTest->visit->patient->wallet->balance, 2) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Test Information</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Test Name</p>
                            <h5 class="fw-bold">{{ $labTest->test_name }}</h5>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Ordered By</p>
                            <h5 class="fw-medium">Dr. {{ $labTest->visit->doctor->name ?? 'Unknown' }}</h5>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Ordered At</p>
                            <p class="fw-medium">{{ $labTest->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Status</p>
                            @if($labTest->status == 'pending')
                                <span class="badge bg-warning-subtle text-warning fs-12">Pending Payment</span>
                            @elseif($labTest->status == 'in_progress')
                                <span class="badge bg-info-subtle text-info fs-12">In Progress</span>
                            @elseif($labTest->status == 'completed')
                                <span class="badge bg-success-subtle text-success fs-12">Completed</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    @if($labTest->status == 'pending')
                        <form action="{{ route('lab.portal.process', $labTest->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Test Cost (₦)</label>
                                <input type="number" name="amount" class="form-control form-control-lg" placeholder="Enter amount to charge" required min="0" step="0.01">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="iconoir-wallet me-2"></i> Deduct Payment & Start Test
                                </button>
                            </div>
                        </form>
                    @elseif($labTest->status == 'in_progress')
                        <form action="{{ route('lab.portal.update', $labTest->id) }}" method="POST">
                            @csrf
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0">Test Results</label>
                                <div class="d-flex align-items-center">
                                    <small class="me-2 text-muted">Load Template:</small>
                                    <select class="form-select form-select-sm" style="width: 200px;" onchange="applyTemplate(this.value)">
                                        <option value="">-- Manual Entry --</option>
                                        @foreach($allTemplates as $tmpl)
                                            <option value="{{ $tmpl->test_name }}" {{ $labTest->test_name == $tmpl->test_name ? 'selected' : '' }}>
                                                {{ $tmpl->test_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="resultsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 30%">Parameter</th>
                                                <th style="width: 25%">Result Value</th>
                                                <th style="width: 15%">Unit</th>
                                                <th style="width: 20%">Ref. Range</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultsBody">
                                            <!-- Dynamic Rows -->
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-soft-primary btn-sm" onclick="addResultRow()">
                                    <i class="iconoir-plus-circle me-1"></i> Add Parameter
                                </button>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Technician's Remarks / Summary</label>
                                <textarea name="result_summary" class="form-control" rows="3" placeholder="Overall conclusion (e.g. Parasites detected, Values normal)..." required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="iconoir-check-circle me-2"></i> Submit & Complete Test
                                </button>
                            </div>
                        </form>

                        <script>
                            // Store all templates in JS
                            const availableTemplates = @json($allTemplates->keyBy('test_name'));

                            function addResultRow(data = {}) {
                                const index = document.querySelectorAll('#resultsBody tr').length;
                                const parameter = data.parameter || '';
                                const unit = data.unit || '';
                                const range = data.range || '';
                                const value = data.value || ''; 
                                const options = data.options || [];

                                let valueField;
                                if (options.length > 0) {
                                    valueField = `<select name="results[${index}][value]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select Result</option>
                                        ${options.map(opt => `<option value="${opt}" ${value === opt ? 'selected' : ''}>${opt}</option>`).join('')}
                                    </select>`;
                                } else {
                                    valueField = `<input type="text" name="results[${index}][value]" class="form-control form-control-sm" placeholder="Value" value="${value}" required>`;
                                }

                                const row = `
                                    <tr>
                                        <td><input type="text" name="results[${index}][parameter]" class="form-control form-control-sm" placeholder="e.g. Hemoglobin" value="${parameter}" required></td>
                                        <td>${valueField}</td>
                                        <td><input type="text" name="results[${index}][unit]" class="form-control form-control-sm" placeholder="Unit" value="${unit}"></td>
                                        <td><input type="text" name="results[${index}][range]" class="form-control form-control-sm" placeholder="Range" value="${range}"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-soft-danger btn-sm p-1" onclick="this.closest('tr').remove()">
                                                <i class="iconoir-xmark"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                document.getElementById('resultsBody').insertAdjacentHTML('beforeend', row);
                            }

                            function applyTemplate(name) {
                                const tbody = document.getElementById('resultsBody');
                                tbody.innerHTML = ''; // Clear existing

                                if (name && availableTemplates[name]) {
                                    const fields = availableTemplates[name].fields;
                                    fields.forEach(field => {
                                        addResultRow(field);
                                    });
                                } else {
                                    addResultRow(); // Add one empty row if no template or manual
                                }
                            }

                            // Initialize
                            document.addEventListener('DOMContentLoaded', () => {
                                const initial = @json($initialTemplate);
                                
                                if (initial && initial.length > 0) {
                                    initial.forEach(field => addResultRow(field));
                                } else {
                                    addResultRow(); 
                                }
                            });
                        </script>
                    @elseif($labTest->status == 'completed')
                        <div class="alert alert-success border-0 d-flex align-items-center mb-4">
                             <i class="iconoir-check-circle fs-24 me-2"></i>
                             <div>
                                 <strong>Test Completed</strong><br>
                                 Result finalized on {{ $labTest->result_at->format('M d, Y H:i') }}
                             </div>
                        </div>

                        <h5 class="fw-bold mb-3">Result Data</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Result</th>
                                        <th>Unit</th>
                                        <th>Ref. Range</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(is_array($labTest->result_data))
                                        @foreach($labTest->result_data as $row)
                                            <tr>
                                                <td>{{ $row['parameter'] ?? '-' }}</td>
                                                <td class="fw-bold">{{ $row['value'] ?? '-' }}</td>
                                                <td>{{ $row['unit'] ?? '-' }}</td>
                                                <td class="text-muted">{{ $row['range'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="4" class="text-center">No structured data available. See summary below.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Summary / Remarks</h6>
                            <p class="text-muted p-3 bg-light rounded">{{ $labTest->result_summary }}</p>
                        </div>

                        <div class="d-flex gap-2">
                             <a href="{{ route('lab.portal.requests.print', $labTest->id) }}" target="_blank" class="btn btn-outline-primary flex-grow-1">
                                <i class="iconoir-printer me-2"></i> Print Official Receipt
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-lab-layout>

<div class="page-wrapper">
    <!-- Page Content-->
    <div class="page-content-tab">
        <div class="container-fluid py-4">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a wire:navigate
                                        href="{{ route('dashboard')}}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Client Management</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Client Management</h4>
                    </div>
                </div>
            </div>

            <div class="row mb-4 align-items-end">
                <div class="col-md-3 mb-2">
                    <input type="text" wire:model.debounce.500ms="search" placeholder="Search by client name or phone"
                        class="form-control" />
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" wire:model.debounce.500ms="filterBusiness" placeholder="Filter by business name"
                        class="form-control" />
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" wire:model.debounce.500ms="filterPartner" placeholder="Filter by partner name"
                        class="form-control" />
                </div>
                <div class="col-md-2 mb-2">
                    <input type="text" wire:model.debounce.500ms="filterState" placeholder="Filter by state"
                        class="form-control" />
                </div>
                <div class="col-md-1 mb-2 text-end">
                    <button wire:click="showCreateForm" class="btn btn-primary w-100">+ Add Client</button>
                </div>
            </div>

            @if (session()->has('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Client Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>State</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                <tr>
                                    <td>{{ $client->client_name }}</td>
                                    <td>{{ $client->phone_number }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->state }}</td>
                                    <td>
                                        <button wire:click="toggleExpand({{ $client->id }})"
                                            class="btn btn-sm btn-info">
                                            {{ in_array($client->id, $expandedClients) ? 'Hide' : 'Expand' }}
                                        </button>
                                        <button wire:click="editClient({{ $client->id }})"
                                            class="btn btn-sm btn-warning">Edit</button>
                                        <button wire:click="deleteClient({{ $client->id }})"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this client?')">Delete</button>
                                    </td>
                                </tr>

                                @if (in_array($client->id, $expandedClients))
                                <tr>
                                    <td colspan="5">
                                        <div class="row g-3">
                                            @foreach ($client->businesses as $business)
                                            <div class="col-12">
                                                <div class="card border-primary mb-3">
                                                    <div
                                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold">{{ $business->business_name }}</span>
                                                        <span class="small">
                                                            Entity: {{ $business->business_entity }} |
                                                            Nature: {{ $business->nature_of_business }}
                                                        </span>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row mb-2">
                                                            <div class="col-md-4"><span class="fw-semibold">GST:</span>
                                                                {{ $business->gst_number }}</div>
                                                            <div class="col-md-4"><span class="fw-semibold">PAN:</span>
                                                                {{ $business->pan_number }}</div>
                                                            <div class="col-md-4"><span
                                                                    class="fw-semibold">State:</span> {{
                                                                $business->state }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-md-6"><span
                                                                    class="fw-semibold">Start:</span> {{
                                                                $business->start_date }}</div>
                                                            <div class="col-md-6"><span class="fw-semibold">End:</span>
                                                                {{ $business->end_date }}</div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <span class="fw-semibold">Details:</span> {{
                                                            $business->business_details }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold mb-1">Partners</div>
                                                            <div class="row g-2">
                                                                @foreach ($business->partners as $partner)
                                                                <div class="col-md-6">
                                                                    <div class="card border-secondary p-2 mb-2">
                                                                        <div class="fw-medium">{{ $partner->partner_name
                                                                            }}</div>
                                                                        <div class="text-muted small">Phone: {{
                                                                            $partner->partner_phone }}</div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No clients found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    {{ $clients->links() }}
                </div>
            </div>

            <div class="mb-2 text-danger">Modal State: {{ $showForm ? 'FORM VISIBLE' : 'FORM HIDDEN' }}</div>
            @if ($showForm)
            <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.4); z-index: 1050;" wire:key="modal-{{ $formKey }}">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingClientId ? 'Edit Client' : 'Add Client' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showForm', false)"></button>
                        </div>
                        <form wire:submit.prevent="saveClient">
                            <div class="modal-body">
                                {{-- Client Fields --}}
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Client Name *</label>
                                        <input type="text" wire:model="client.client_name" class="form-control"
                                            required />
                                        @error('client.client_name')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Phone *</label>
                                        <input type="text" wire:model="client.phone_number" class="form-control"
                                            required />
                                        @error('client.phone_number')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Alternative Number</label>
                                        <input type="text" wire:model="client.alternative_number"
                                            class="form-control" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" wire:model="client.email" class="form-control" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">State *</label>
                                        <input type="text" wire:model="client.state" class="form-control" required />
                                        @error('client.state')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Businesses --}}
                                <div class="mt-4">
                                                                                                            <div class="alert alert-warning small mb-2">
                                                                                                                <strong>Type of businesses:</strong> {{ gettype($client['businesses'] ?? null) }}<br>
                                                                                                                <strong>Full client:</strong>
                                                                                                                <pre>{{ json_encode($client, JSON_PRETTY_PRINT) }}</pre>
                                                                                                            </div>
                                                                        <div class="alert alert-info small mb-2">
                                                                            <strong>Debug businesses:</strong>
                                                                            <pre>{{ json_encode($client['businesses'] ?? null, JSON_PRETTY_PRINT) }}</pre>
                                                                        </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-bold fs-5">Businesses</div>
                                        <button type="button" wire:click="addBusiness" class="btn btn-success btn-sm">
                                            + Add Business
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        @php
                                            $businesses = $client['businesses'] ?? [];
                                            if (empty($businesses)) {
                                                $businesses = [[
                                                    'business_name' => '',
                                                    'business_entity' => '',
                                                    'nature_of_business' => '',
                                                    'business_details' => '',
                                                    'start_date' => '',
                                                    'end_date' => '',
                                                    'gst_number' => '',
                                                    'pan_number' => '',
                                                    'state' => '',
                                                    'partners' => [
                                                        [
                                                            'partner_name' => '',
                                                            'partner_phone' => '',
                                                        ]
                                                    ]
                                                ]];
                                            }
                                        @endphp
                                        @foreach ($businesses as $bIndex => $business)
                                        <div class="col-12"
                                            wire:key="business-{{ $bIndex }}-{{ count($client['businesses']) }}">
                                            <div class="card border-info mb-3">
                                                <div
                                                    class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                                    <span class="fw-semibold">Business #{{ $bIndex + 1 }}</span>
                                                    @if (count($client['businesses']) > 1)
                                                    <button type="button" wire:click="removeBusiness({{ $bIndex }})"
                                                        class="btn btn-danger btn-sm">Remove</button>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Business Name *</label>
                                                            <input type="text"
                                                                wire:model="client.businesses.{{ $bIndex }}.business_name"
                                                                class="form-control" required />
                                                            @error('client.businesses.' . $bIndex . '.business_name')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Business Entity</label>
                                                            <input type="text"
                                                                wire:model="client.businesses.{{ $bIndex }}.business_entity"
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Nature of Business</label>
                                                            <input type="text"
                                                                wire:model="client.businesses.{{ $bIndex }}.nature_of_business"
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label">Business Details</label>
                                                            <textarea
                                                                wire:model="client.businesses.{{ $bIndex }}.business_details"
                                                                class="form-control" rows="2"></textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Start Date</label>
                                                            <input type="date"
                                                                wire:model="client.businesses.{{ $bIndex }}.start_date"
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">End Date</label>
                                                            <input type="date"
                                                                wire:model="client.businesses.{{ $bIndex }}.end_date"
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">GST Number</label>
                                                            <input type="text"
                                                                wire:model="client.businesses.{{ $bIndex }}.gst_number"
                                                                class="form-control" />
                                                            @error('client.businesses.' . $bIndex . '.gst_number')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">PAN Number</label>
                                                            <input type="text"
                                                                wire:model="client.businesses.{{ $bIndex }}.pan_number"
                                                                class="form-control" />
                                                            @error('client.businesses.' . $bIndex . '.pan_number')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">State *</label>
                                                            <input type="text"
                                                                wire:model="client.businesses.{{ $bIndex }}.state"
                                                                class="form-control" required />
                                                            @error('client.businesses.' . $bIndex . '.state')
                                                            <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    {{-- Partners --}}
                                                    <div class="mt-3">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-1">
                                                            <div class="fw-semibold">Partners</div>
                                                            <button type="button" wire:click="addPartner({{ $bIndex }})"
                                                                class="btn btn-success btn-sm">+ Add Partner</button>
                                                        </div>
                                                        <div class="row g-2">
                                                            @foreach ($client['businesses'][$bIndex]['partners'] as $pIndex => $partner)
                                                            <div class="col-md-6"
                                                                wire:key="partner-{{ $bIndex }}-{{ $pIndex }}-{{ count($client['businesses'][$bIndex]['partners']) }}">
                                                                <div class="card border-secondary p-2 mb-2">
                                                                    <div class="row g-2 align-items-center">
                                                                        <div class="col-6">
                                                                            <label class="form-label">Partner
                                                                                Name</label>
                                                                            <input type="text"
                                                                                wire:model="client.businesses.{{ $bIndex }}.partners.{{ $pIndex }}.partner_name"
                                                                                class="form-control" />
                                                                            @error('client.businesses.' . $bIndex .
                                                                            '.partners.' . $pIndex . '.partner_name')
                                                                            <span class="text-danger small">{{ $message
                                                                                }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label class="form-label">Partner
                                                                                Phone</label>
                                                                            <input type="text"
                                                                                wire:model="client.businesses.{{ $bIndex }}.partners.{{ $pIndex }}.partner_phone"
                                                                                class="form-control" />
                                                                        </div>
                                                                        @if(count($client['businesses'][$bIndex]['partners']) > 1)
                                                                        <div class="col-12 text-end">
                                                                            <button type="button"
                                                                                wire:click="removePartner({{ $bIndex }}, {{ $pIndex }})"
                                                                                class="btn btn-danger btn-sm">Remove</button>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    {{-- End Partners --}}

                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- End Businesses --}}

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showForm', false)">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Client</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!--Start Footer-->
            <livewire:layout.footer />
            <!--end footer-->

        </div>
    </div>
</div>

<script>
    function confirmDeletion(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteItem', { id: itemId });
            }
        });
    }
</script>

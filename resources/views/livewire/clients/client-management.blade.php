<div class="page-wrapper" role="main">
    <!-- Page Content-->
    <div class="page-content-tab">
        <div class="container-fluid py-4">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title mb-0 fw-bold"><i class="fas fa-users me-2"></i>Client Management</h1>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard')}}"><i class="fas fa-home"></i> Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Client Management</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row mb-4 align-items-end g-2">
                <div class="col-md-3">
                    <input type="text" wire:model.live="search" placeholder="Search by client name or phone" class="form-control" aria-label="Search by client name or phone" />
                </div>
                <div class="col-md-3">
                    <input type="text" wire:model.live="filterBusiness" placeholder="Filter by business name" class="form-control" aria-label="Filter by business name" />
                </div>
                <div class="col-md-3">
                    <input type="text" wire:model.live="filterPartner" placeholder="Filter by partner name" class="form-control" aria-label="Filter by partner name" />
                </div>
                <div class="col-md-2">
                    <input type="text" wire:model.live="filterState" placeholder="Filter by state" class="form-control" aria-label="Filter by state" />
                </div>
                <div class="col-md-1 text-end">
                    <button wire:click="showCreateForm" class="btn btn-primary w-100" data-bs-toggle="tooltip" title="Add a new client"><i class="fas fa-plus-circle me-1"></i> Add</button>
                </div>
            </div>

            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
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
                                @forelse ($clients as $clientRecord)
                                <tr>
                                    <td>{{ $clientRecord->client_name }}</td>
                                    <td>{{ $clientRecord->phone_number }}</td>
                                    <td>{{ $clientRecord->email }}</td>
                                    <td>{{ $clientRecord->state }}</td>
                                    <td>
                                        <button wire:click="toggleExpand({{ $clientRecord->id }})" class="btn btn-sm btn-info me-1" data-bs-toggle="tooltip" title="{{ in_array($clientRecord->id, $expandedClients) ? 'Hide details' : 'Show details' }}">
                                            <i class="fas {{ in_array($clientRecord->id, $expandedClients) ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                        <button wire:click="editClient({{ $clientRecord->id }})" class="btn btn-sm btn-warning me-1" data-bs-toggle="tooltip" title="Edit client"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete client" onclick="confirmDeletion({{ $clientRecord->id }})"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>

                                @if (in_array($clientRecord->id, $expandedClients))
                                <tr>
                                    <td colspan="5" class="bg-light-subtle px-4 py-4">
                                        <div class="row g-4">
                                            @foreach ($clientRecord->businesses as $business)
                                            <div class="col-12">
                                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                                    <div class="card-header bg-primary text-white px-4 py-3">
                                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                                            <div>
                                                                <div class="fw-bold fs-5">{{ $business->business_name }}</div>
                                                                <div class="small opacity-75 mt-1">Business summary and statutory profile</div>
                                                            </div>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @if ($business->business_entity)
                                                                <span class="badge bg-white text-primary rounded-pill px-3 py-2">{{ $business->business_entity }}</span>
                                                                @endif
                                                                @if ($business->nature_of_business)
                                                                <span class="badge bg-info-subtle text-dark rounded-pill px-3 py-2">{{ $business->nature_of_business }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-4 bg-white">
                                                        <div class="row g-3 mb-4">
                                                            <div class="col-md-3">
                                                                <div class="border rounded-3 p-3 h-100 bg-light">
                                                                    <div class="text-muted small text-uppercase fw-semibold mb-1">GST</div>
                                                                    <div class="fw-semibold text-dark">{{ $business->gst_number ?: 'N/A' }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="border rounded-3 p-3 h-100 bg-light">
                                                                    <div class="text-muted small text-uppercase fw-semibold mb-1">PAN</div>
                                                                    <div class="fw-semibold text-dark">{{ $business->pan_number ?: 'N/A' }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="border rounded-3 p-3 h-100 bg-light">
                                                                    <div class="text-muted small text-uppercase fw-semibold mb-1">State</div>
                                                                    <div class="fw-semibold text-dark">{{ $business->state ?: 'N/A' }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="border rounded-3 p-3 h-100 bg-light">
                                                                    <div class="text-muted small text-uppercase fw-semibold mb-1">State Code</div>
                                                                    <div class="fw-semibold text-dark">{{ $business->state_code ?: 'N/A' }}</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row g-4 mb-4">
                                                            <div class="col-lg-7">
                                                                <div class="border rounded-4 p-4 h-100">
                                                                    <div class="text-uppercase text-muted small fw-semibold mb-2">Address</div>
                                                                    <div class="fw-semibold text-dark mb-3">{{ $business->address ?: 'No address added' }}</div>
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="text-muted small text-uppercase fw-semibold mb-1">City</div>
                                                                            <div class="text-dark">{{ $business->city ?: 'N/A' }}</div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="text-muted small text-uppercase fw-semibold mb-1">Pincode</div>
                                                                            <div class="text-dark">{{ $business->pincode ?: 'N/A' }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-5">
                                                                <div class="border rounded-4 p-4 h-100 bg-light">
                                                                    <div class="text-uppercase text-muted small fw-semibold mb-3">Operational Period</div>
                                                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                                                        <span class="text-muted">Start Date</span>
                                                                        <span class="fw-semibold text-dark">{{ $business->start_date ?: 'N/A' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted">End Date</span>
                                                                        <span class="fw-semibold text-dark">{{ $business->end_date ?: 'N/A' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="border rounded-4 p-4 mb-4 bg-light">
                                                            <div class="text-uppercase text-muted small fw-semibold mb-2">Business Details</div>
                                                            <div class="text-dark mb-0">{{ $business->business_details ?: 'No additional business details provided.' }}</div>
                                                        </div>

                                                        <div>
                                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                                <div>
                                                                    <div class="fw-semibold fs-6 text-dark">Partners</div>
                                                                    <div class="text-muted small">Linked contacts for this business</div>
                                                                </div>
                                                                <span class="badge bg-secondary-subtle text-dark rounded-pill px-3 py-2">{{ $business->partners->count() }} Records</span>
                                                            </div>
                                                            <div class="row g-3">
                                                                @foreach ($business->partners as $partner)
                                                                <div class="col-md-6 col-xl-4">
                                                                    <div class="card border-0 shadow-sm h-100 bg-light-subtle rounded-4">
                                                                        <div class="card-body p-3">
                                                                            <div class="small text-uppercase text-muted fw-semibold mb-1">Partner</div>
                                                                            <div class="fw-semibold text-dark mb-2">{{ $partner->partner_name ?: 'Unnamed Partner' }}</div>
                                                                            <div class="text-muted small">Phone</div>
                                                                            <div class="text-dark">{{ $partner->partner_phone ?: 'N/A' }}</div>
                                                                        </div>
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


            @if ($showForm)
                <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.4); z-index: 1050;" wire:key="modal-{{ $formKey }}" aria-modal="true" role="dialog">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">{{ $editingClientId ? 'Edit Client' : 'Add Client' }}</h5>
                                <button type="button" class="btn-close" wire:click="$set('showForm', false)" aria-label="Close"></button>
                            </div>
                            <form wire:submit.prevent="saveClient" autocomplete="off">
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
                                    <div class="mt-4 pt-3 border-top">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                                            <div>
                                                <div class="fw-bold fs-4 text-dark d-flex align-items-center gap-2">
                                                    <i class="fas fa-building text-primary"></i>
                                                    <span>Business Details</span>
                                                </div>
                                                <div class="text-muted small">Capture business registration details, tax identifiers, and associated partners.</div>
                                            </div>
                                            <div class="col-lg-2">
                                            <button type="button" wire:click="addBusiness" class="btn btn-outline-success btn-md px-2 py-0 shadow-sm">
                                                <i class="fas fa-plus-circle me-1"></i>Add More Business
                                            </button>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            @php
                                                $businesses = $client['businesses'] ?? [];
                                                if (empty($businesses)) {
                                                    $businesses = [
                                                        [
                                                            'business_name' => '',
                                                            'business_entity' => '',
                                                            'nature_of_business' => '',
                                                            'business_details' => '',
                                                            'start_date' => '',
                                                            'end_date' => '',
                                                            'gst_number' => '',
                                                            'pan_number' => '',
                                                            'state' => '',
                                                            'address' => '',
                                                            'city' => '',
                                                            'pincode' => '',
                                                            'state_code' => '',
                                                            'partners' => [
                                                                [
                                                                    'partner_name' => '',
                                                                    'partner_phone' => '',
                                                                ]
                                                            ]
                                                        ]
                                                    ];
                                                }
                                            @endphp
                                            @foreach ($businesses as $bIndex => $business)
                                                <div class="col-12"
                                                    wire:key="business-{{ $bIndex }}-{{ count($client['businesses']) }}">
                                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                                        <div class="card-header bg-info border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge text-bg-info rounded-pill px-3 py-2">Business {{ $bIndex + 1 }}</span>
                                                                <span class="fw-semibold text-dark">Registration Information</span>
                                                            </div>
                                                            @if (count($client['businesses']) > 1)
                                                                <div class="col-lg-1">
                                                                   <button type="button" wire:click="removeBusiness({{ $bIndex }})" class="btn btn-danger btn-sm px-2 py-0 lh-sm">Remove</button>
                                                                </div>

                                                            @endif
                                                        </div>
                                                        <div class="card-body p-4 bg-light">
                                                            <div class="text-uppercase text-muted small fw-semibold mb-3">Business Profile</div>
                                                            <div class="row g-3">
                                                                <div class="col-md-4">
                                                                    <label class="form-label fw-semibold">Business Name *</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.business_name"
                                                                        class="form-control shadow-sm" required />
                                                                    @error('client.businesses.' . $bIndex . '.business_name')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label fw-semibold">Business Entity</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.business_entity"
                                                                        class="form-control shadow-sm" />
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label fw-semibold">Nature of Business</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.nature_of_business"
                                                                        class="form-control shadow-sm" />
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label class="form-label fw-semibold">Business Details</label>
                                                                    <textarea
                                                                        wire:model="client.businesses.{{ $bIndex }}.business_details"
                                                                        class="form-control shadow-sm" rows="3" placeholder="Brief summary of business operations or service scope"></textarea>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Start Date</label>
                                                                    <input type="date"
                                                                        wire:model="client.businesses.{{ $bIndex }}.start_date"
                                                                        class="form-control shadow-sm" />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">End Date</label>
                                                                    <input type="date"
                                                                        wire:model="client.businesses.{{ $bIndex }}.end_date"
                                                                        class="form-control shadow-sm" />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">GST Number</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.gst_number"
                                                                        class="form-control shadow-sm" />
                                                                    @error('client.businesses.' . $bIndex . '.gst_number')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">PAN Number</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.pan_number"
                                                                        class="form-control shadow-sm" />
                                                                    @error('client.businesses.' . $bIndex . '.pan_number')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">State *</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.state"
                                                                        class="form-control shadow-sm" required />
                                                                    @error('client.businesses.' . $bIndex . '.state')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">State Code</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.state_code"
                                                                        class="form-control shadow-sm" />
                                                                    @error('client.businesses.' . $bIndex . '.state_code')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label class="form-label fw-semibold">Address</label>
                                                                    <textarea
                                                                        wire:model="client.businesses.{{ $bIndex }}.address"
                                                                        class="form-control shadow-sm" rows="2" placeholder="Business address"></textarea>
                                                                    @error('client.businesses.' . $bIndex . '.address')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">City</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.city"
                                                                        class="form-control shadow-sm" />
                                                                    @error('client.businesses.' . $bIndex . '.city')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-semibold">Pincode</label>
                                                                    <input type="text"
                                                                        wire:model="client.businesses.{{ $bIndex }}.pincode"
                                                                        class="form-control shadow-sm" />
                                                                    @error('client.businesses.' . $bIndex . '.pincode')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            {{-- Partners --}}
                                                            <div class="mt-4 pt-3 border-top">
                                                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                                                    <div>
                                                                        <div class="fw-semibold text-dark d-flex align-items-center gap-2">
                                                                            <i class="fas fa-users text-secondary"></i>
                                                                            <span>Partners</span>
                                                                        </div>
                                                                        <div class="text-muted small">Add the key contacts or stakeholders for this business.</div>
                                                                    </div>
                                                                    <div class="col-lg-3">
                                                                    <button type="button" wire:click="addPartner({{ $bIndex }})"
                                                                        class="btn btn-outline-success btn-md px-2 py-0"><i class="fas fa-plus-circle me-1"></i>Add More Partner</button>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-3">
                                                                    @foreach ($client['businesses'][$bIndex]['partners'] as $pIndex => $partner)
                                                                    <div class="col-md-6"
                                                                        wire:key="partner-{{ $bIndex }}-{{ $pIndex }}-{{ count($client['businesses'][$bIndex]['partners']) }}">
                                                                        <div class="card border bg-light-subtle rounded-4 h-100 shadow-sm">
                                                                            <div class="card-body p-3">
                                                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                                                    <div class="small text-uppercase text-muted fw-semibold">Partner {{ $pIndex + 1 }}</div>
                                                                                    @if(count($client['businesses'][$bIndex]['partners']) > 1)
                                                                                   <div class="col-lg-1">
                                                                                    <button type="button"
                                                                                        wire:click="removePartner({{ $bIndex }}, {{ $pIndex }})"
                                                                                        class="btn btn-danger btn-sm px-2 py-0 lh-sm">Remove</button>
                                                                                   </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="row g-3">
                                                                                    <div class="col-6">
                                                                                        <label class="form-label fw-semibold">Partner Name</label>
                                                                                        <input type="text"
                                                                                            wire:model="client.businesses.{{ $bIndex }}.partners.{{ $pIndex }}.partner_name"
                                                                                            class="form-control shadow-sm" />
                                                                                        @error('client.businesses.' . $bIndex . '.partners.' . $pIndex . '.partner_name')
                                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <label class="form-label fw-semibold">Partner Phone</label>
                                                                                        <input type="text"
                                                                                            wire:model="client.businesses.{{ $bIndex }}.partners.{{ $pIndex }}.partner_phone"
                                                                                            class="form-control shadow-sm" />
                                                                                    </div>
                                                                                </div>
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
                                    <button type="button" class="btn btn-secondary" wire:click="$set('showForm', false)"><i class="fas fa-times-circle me-1"></i>Cancel</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Client</button>
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
    // Enhanced confirmation modal for delete
    function confirmDeletion(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ms-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteItem', { id: itemId });
            }
        });
    }
    // Enable Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

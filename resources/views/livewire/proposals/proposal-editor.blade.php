<div class="container py-4">
    <form wire:submit.prevent="saveDraft">
        <div class="card shadow mx-auto" style="max-width: 900px;">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="mb-0">@if($proposalId) Edit @else New @endif Proposal</h5>
                <select wire:model="type" class="form-select w-auto">
                    <option value="quotation">Quotation Proposal</option>
                    <option value="proposal">Business Proposal</option>
                </select>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" wire:model="title" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Client Details</label>
                        <input type="text" wire:model="client_details" class="form-control" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Project Scope</label>
                    <textarea wire:model="project_scope" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pricing Table</label>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th style="width: 80px;">Qty</th>
                                    <th style="width: 120px;">Price</th>
                                    <th style="width: 120px;">Total</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i => $item)
                                <tr>
                                    <td><input type="text" wire:model="items.{{$i}}.item_name" class="form-control form-control-sm" /></td>
                                    <td><input type="text" wire:model="items.{{$i}}.description" class="form-control form-control-sm" /></td>
                                    <td><input type="number" wire:model="items.{{$i}}.quantity" class="form-control form-control-sm" min="1" /></td>
                                    <td><input type="number" wire:model="items.{{$i}}.price" class="form-control form-control-sm" min="0" step="0.01" /></td>
                                    <td class="text-end">₹{{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                                    <td><button type="button" wire:click="removeItem({{$i}})" class="btn btn-sm btn-outline-danger">✕</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" wire:click="addItem" class="btn btn-outline-primary btn-sm mt-2">+ Add Item</button>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-auto">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="taxes_enabled" id="taxes_enabled">
                            <label class="form-check-label" for="taxes_enabled">GST ({{ $tax_percent }}%)</label>
                        </div>
                    </div>
                    <div class="col text-end">
                        <span class="fw-bold fs-5">Total: ₹{{ number_format($total_amount, 2) }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes / Terms</label>
                    <textarea wire:model="notes" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rich Content</label>
                    <textarea wire:model="rich_content" class="form-control" style="min-height:120px"></textarea>
                    <small class="text-muted">(Rich text editor integration recommended, e.g., TinyMCE)</small>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-outline-secondary">Save as Draft</button>
                    <button type="button" wire:click="sendProposal" class="btn btn-primary">Send to Customer</button>
                </div>
                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    </form>
</div>

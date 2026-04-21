
<div class="card shadow mx-auto my-4" style="max-width: 700px;">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <div>
            <h4 class="mb-0">{{ $proposal->title }}</h4>
            <div class="small">{{ $proposal->lead->company ?? '' }}</div>
        </div>
        <span class="badge bg-info fs-6">
            @include('livewire.proposals.proposal-status-badge', ['status' => $proposal->status])
        </span>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Client:</strong> {{ $proposal->lead->name }}<br>
            <strong>Email:</strong> {{ $proposal->lead->email }}<br>
            <strong>Phone:</strong> {{ $proposal->lead->phone }}
        </div>
        <div class="mb-3">
            <strong>Project Scope:</strong>
            <div class="text-muted whitespace-pre-line">{{ $proposal->project_scope ?? '-' }}</div>
        </div>
        <div class="mb-3">
            <strong>Pricing:</strong>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mt-2 mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposal->items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-end mb-3">
            <span class="fw-bold fs-5 text-primary">Total: ₹{{ number_format($proposal->total_amount, 2) }}</span>
        </div>
        <div class="mb-3">
            <strong>Notes / Terms:</strong>
            <div class="text-muted whitespace-pre-line">{{ $proposal->notes ?? '-' }}</div>
        </div>
        <div class="mb-3">
            <strong>Rich Content:</strong>
            <div class="prose">{!! $proposal->content !!}</div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('proposals.download', $proposal) }}" class="btn btn-outline-secondary">Download PDF</a>
            <a href="{{ route('proposals.email', $proposal) }}" class="btn btn-primary">Send Email</a>
        </div>
    </div>
</div>

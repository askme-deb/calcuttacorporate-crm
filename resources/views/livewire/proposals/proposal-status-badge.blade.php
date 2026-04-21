@props(['status'])
@php
    $badges = [
        'draft' => 'badge bg-secondary',
        'sent' => 'badge bg-info text-dark',
        'accepted' => 'badge bg-success',
        'rejected' => 'badge bg-danger',
    ];
@endphp
<span class="{{ $badges[$status] ?? 'badge bg-light text-dark' }}">
    {{ ucfirst($status) }}
</span>

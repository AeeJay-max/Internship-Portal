@props(['variant' => 'gray'])

@php
    $variants = [
        'navy'                => 'badge-navy',
        'crimson'             => 'badge-crimson',
        'success'             => 'badge-success',
        'warning'             => 'badge-warning',
        'danger'              => 'badge-danger',
        'info'                => 'badge-info',
        'gray'                => 'badge-gray',
        // Application statuses
        'draft'               => 'badge-draft',
        'submitted'           => 'badge-submitted',
        'under_review'        => 'badge-under_review',
        'documents_requested' => 'badge-documents_requested',
        'approved'            => 'badge-approved',
        'rejected'            => 'badge-rejected',
    ];
@endphp

<span {{ $attributes->merge(['class' => $variants[$variant] ?? 'badge-gray']) }}>
    {{ $slot }}
</span>

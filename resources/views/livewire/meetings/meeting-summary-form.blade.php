@php
    $isEdit = isset($meetingId) && $meetingId;
@endphp

<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('meetings.summary') }}">Meeting Summary</a></li>
                                <li class="breadcrumb-item active">{{ $isEdit ? 'Edit' : 'Add' }} Meeting</li>
                            </ol>
                        </div>
                        <h4 class="page-title">{{ $isEdit ? 'Edit' : 'Add' }} Meeting Summary</h4>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                        <div>
                            <h5 class="mb-1">{{ $isEdit ? 'Edit' : 'New' }} Meeting Summary</h5>
                            <p class="text-muted small mb-0">Use the sections below to prepare a complete internal meeting record.</p>
                        </div>
                       
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form wire:submit.prevent="save">
                        @include('livewire.meetings.partials.meeting-form-fields')
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 pt-4 border-top mt-4">
                            <div class="text-muted small">Review each section before submitting the meeting summary.</div>
                            <div class="gap-2">
                                <a href="{{ route('meetings.summary') }}" class="btn btn-outline-secondary">Back</a>
                                <button type="submit" class="btn btn-primary px-4">{{ $isEdit ? 'Update' : 'Create' }} Meeting Summary</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

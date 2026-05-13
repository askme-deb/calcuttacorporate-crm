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

            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        @include('livewire.meetings.partials.meeting-form-fields')
                        <div class="mt-4">
                            <a href="{{ route('meetings.summary') }}" class="btn btn-outline-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }} Meeting Summary</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

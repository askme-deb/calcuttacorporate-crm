<div class="page-wrapper">
    <!-- Page Content-->
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('worksheet') }}">Projects</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Project</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form wire:submit.prevent="updateProject" class="needs-validation"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group mt-2">
                                                    <label for="projectName" class="form-label">Project Title</label>
                                                    <input type="text" class="form-control" id="projectName"
                                                        aria-describedby="emailHelp" wire:model="title"
                                                        placeholder="Enter project name">
                                                </div><!--end form-group-->

                                                <div class="col-lg-6 col-6 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-start-date">Job Type</label>
                                                    <select class="form-select" wire:model="jobtype_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($jobtypes as $jobid => $jobname)
                                                        <option value="{{ $jobid }}">
                                                            {{ $jobname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                                <div class="col-lg-6 col-6 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-end-date">Work Type</label>
                                                    <select class="form-select" wire:model="work_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($worklists as $workid => $workname)
                                                        <option value="{{ $workid }}">
                                                            {{ $workname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end form-group-->

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4 col-4 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-start-date">Lead</label>
                                                    <select class="form-select" wire:model="lead_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($leads as $leadid => $leadname)
                                                        <option value="{{ $leadid }}">
                                                            {{ $leadname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                                <div class="col-lg-4 col-4 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-end-date">Deal</label>
                                                    <select class="form-select" wire:model="deal_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($deals as $dealid => $dealname)
                                                        <option value="{{ $dealid }}">
                                                            {{ $dealname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                                <div class="col-lg-4 col-4 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-end-date">Client</label>
                                                    <select class="form-select" wire:model="client_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($clients as $clientid => $clientname)
                                                        <option value="{{ $clientid }}">
                                                            {{ $clientname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end form-group-->

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-3 col-6 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-start-date">Project Start
                                                        Date</label>
                                                    <input type="date" class="form-control" wire:model="start_date"
                                                        id="pro-start-date" placeholder="Enter start date">
                                                </div><!--end col-->
                                                <div class="col-lg-3 col-6 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-end-date">Project End
                                                        Date</label>
                                                    <input type="date" wire:model="deadline" class="form-control"
                                                        id="" placeholder="Enter end date">
                                                </div><!--end col-->
                                                <div class="col-lg-3 col-6 mb-2 mb-lg-0">
                                                    <label class="form-label mt-2" for="pro-end-date">Client's
                                                        Deadline</label>
                                                    <input type="date" wire:model="customer_deadline"
                                                        class="form-control" id=""
                                                        placeholder="Enter Client's Deadline">
                                                </div><!--end col-->
                                                <div class="col-lg-3 col-6">
                                                    <label class="form-label mt-2" for="pro-rate">Cost</label>
                                                    <input type="text" wire:model="cost" class="form-control"
                                                        id="pro-rate" placeholder="">
                                                </div><!--end col-->

                                            </div><!--end row-->
                                        </div><!--end form-group-->
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4 col-6">
                                                    <label class="form-label mt-2" for="pro-end-date">Price
                                                        Type</label>
                                                    <select class="form-select" wire:model="price_type_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($price_types as $ptid => $ptname)
                                                        <option value="{{ $ptid }}">
                                                            {{ $ptname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                                <div class="col-lg-4 col-6">
                                                    <label class="form-label mt-2" for="pro-end-date">Invoice
                                                        Time</label>
                                                    <select class="form-select" wire:model="invoice_time_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($invoicetimes as $itid => $itname)
                                                        <option value="{{ $itid }}">
                                                            {{ $itname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                                <div class="col-lg-4 col-6">
                                                    <label class="form-label mt-2" for="pro-end-date">Priority</label>
                                                    <select class="form-select" wire:model="priority_id">
                                                        <option value="">Choose...</option>
                                                        @foreach ($priorities as $prid => $prname)
                                                        <option value="{{ $prid }}">
                                                            {{ $prname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end form-group-->
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label mt-2" for="pro-message">Description</label>
                                            <div wire:ignore>
                                                <textarea class="form-control" rows="5" id="pro-message" wire:model="description"></textarea>
                                            </div>
                                        </div><!--end form-group-->

                                    </div><!--end col-->
                                    <div class="col-lg-4 ms-auto mt-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Image</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-grid">
                                                    <div wire:loading wire:target="image" wire:key="image"><i
                                                            class="spinner-border spinner-border-sm mt-2 ml-2"></i>
                                                        Uploading...</div>
                                                    @if ($imagePreview)
                                                    <div
                                                        class="preview-box d-block justify-content-center rounded shadow overflow-hidden bg-light p-1">
                                                        <img class="rounded d-block"
                                                            src="{{ $imagePreview }}"
                                                            style="height: 172px;width: 100%;">
                                                    </div>
                                                    @else
                                                    <div
                                                        class="preview-box d-block justify-content-center rounded shadow overflow-hidden bg-light p-1">
                                                        <img class="rounded d-block" src="{{ $projectimage }}"
                                                            style="height: 172px;width: 100%;">
                                                    </div>
                                                    @endif
                                                    <input type="file" id="input-file" wire:model="image"
                                                        accept="image/*" hidden />
                                                    <label class="btn-upload btn btn-outline-secondary mt-4"
                                                        for="input-file"><i class="fas fa-cloud-upload-alt"></i>
                                                        Browse Image</label>
                                                    @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    @if ($errorMessage)
                                                    <span class="text-danger">{{ $errorMessage }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Team Members</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-grid">
                                                    @can('Asign Project')
                                                    <div class="form-group" wire:ignore>
                                                        <div class="col-lg-12 mb-2 mb-lg-0">
                                                            <label class="form-label mt-2" for="pro-end-date">Asign
                                                                to</label>
                                                            <select class="form-select" wire:ignore.self
                                                                wire:model="asign_to" id="rol" multiple="multiple">
                                                                <option value="">Choose...</option>
                                                                @foreach ($users as $userid => $username)
                                                                <option value="{{ $userid }}">
                                                                    {{ $username }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div><!--end col-->
                                                    </div><!--end form-group-->
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Attachments</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-grid">
                                                    <div class="form-group mt-2 mb-3">
                                                        <input type="text" class="form-control" id=""
                                                            wire:model="attachedTitle"
                                                            placeholder="Enter Title Here..">
                                                    </div>

                                                    <div x-data="{ isDragging: false }" x-on:dragover.prevent="isDragging = true"
                                                        x-on:dragleave.prevent="isDragging = false"
                                                        x-on:drop.prevent="isDragging = false; $wire.uploadMultiple('files', $event.dataTransfer.files)"
                                                        class="border border-2 border-soft-secondary p-4 rounded text-center bg-light"
                                                        :class="{ 'border-primary bg-primary bg-opacity-10': isDragging }">

                                                        <!-- Show Loading Icon When File is Being Processed -->
                                                        <div wire:loading wire:target="files" class="text-center">
                                                            <span class="spinner-border spinner-border-lg text-primary" role="status" aria-hidden="true"></span>
                                                            <p class="text-muted mt-2">Processing Files...</p>
                                                        </div>

                                                        <!-- Hide File Input and Label While Uploading -->
                                                        <div wire:loading.remove wire:target="files">
                                                            <input type="file" multiple wire:model="files" class="d-none" id="fileInput">
                                                            <label for="fileInput" class="d-block">
                                                                <p class="text-muted" x-show="!isDragging"><i class="fas fa-cloud-upload-alt"></i> Drag & Drop files Here or Click to Upload</p>
                                                                <p class="text-primary fw-bold" x-show="isDragging">Drop the files here...</p>
                                                            </label>
                                                        </div>

                                                    </div>

                                                    <!-- Show selected file names -->
                                                    @if ($files)
                                                    <div class="mt-3">
                                                        <ul class="list-group">
                                                            @foreach ($files as $file)
                                                            <li class="list-group-item text-success">
                                                                {{ $file->getClientOriginalName() }}
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Status</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-grid">
                                                    <select class="form-select" wire:model="status_id">

                                                        @foreach ($workstatus as $statusid => $statusname)
                                                        <option value="{{ $statusid }}">{{ $statusname }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-2 text-start mt-3 float-end">
                                            <button type="submit" class="btn btn-outline-primary">
                                                <span wire:loading wire:target="updateProject">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span> Loading...
                                                </span>
                                                <span wire:loading.remove wire:target="updateProject">
                                                    Save changes
                                                </span>
                                            </button>
                                            <a href="{{ route('worksheet') }}" wire:navigate
                                                class="btn btn-outline-danger">Cancel</a>
                                        </div>

                                    </div><!--end col-->
                                </div><!--end row-->
                            </form>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!-- container -->
        <!--Start Footer-->
        <livewire:layout.footer />
        <!-- end Footer -->
    </div>
    <!-- end page content -->
</div>
@script()
<script>
let tinyMCEInitialized = false;

// Initialize Select2
$(document).ready(function() {
    // Initialize Select2 for team member assignment
    $('#rol').select2();
    $('#rol').on('change', function(e) {
        let data = $(this).val();
        $wire.set('asign_to', data);
    });

    // Set initial selected values for team members
    if (Array.isArray(@this.asign_to)) {
        $('#rol').val(@this.asign_to).trigger('change');
    }

    // Initialize TinyMCE
    initTinyMCE();
});

function initTinyMCE() {
    if (tinyMCEInitialized) {
        return; // Prevent double initialization
    }

    tinymce.init({
        selector: '#pro-message',
        height: 300,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        branding: false,
        setup: function(editor) {
            // Set initial content when editor is ready
            editor.on('init', function() {
                const initialContent = @json($description ?? '');
                editor.setContent(initialContent);
                tinyMCEInitialized = true;

                            // Remove branding elements
            document.querySelectorAll('.tox-statusbar__branding').forEach(el => el.remove());
            document.querySelectorAll('.tox-tooltip').forEach(el => el.remove());
            document.querySelectorAll('.tox-promotion').forEach(el => el.remove());

            });

            // Update Livewire property when content changes
            editor.on('blur keyup change', function() {
                const content = editor.getContent();
                @this.call('updateDescription', content);
                       setTimeout(() => {
                document.querySelectorAll('.tox-tooltip').forEach(el => el.remove());
                document.querySelectorAll('.tox-promotion').forEach(el => el.remove());
            }, 100);
            });
        }

    });
}

// Listen for Livewire updates to re-sync content
window.addEventListener('livewire:updated', function() {
    // Reinitialize Select2 with updated values
    if ($('#rol').length && Array.isArray(@this.asign_to)) {
        $('#rol').val(@this.asign_to).trigger('change');
    }

    // Update TinyMCE content if needed
    const editor = tinymce.get('pro-message');
    if (editor && @this.description !== editor.getContent()) {
        editor.setContent(@this.description || '');
    }
});

// Clean up when navigating away or component is destroyed
window.addEventListener('beforeunload', function() {
    cleanupResources();
});

// Listen for Livewire navigation events
document.addEventListener('livewire:navigating', function() {
    cleanupResources();
});

// Cleanup function
function cleanupResources() {
    // Destroy TinyMCE editor
    const editor = tinymce.get('pro-message');
    if (editor) {
        editor.destroy();
        tinyMCEInitialized = false;
    }

    // Destroy Select2
    if ($('#rol').hasClass('select2-hidden-accessible')) {
        $('#rol').select2('destroy');
    }
}

// Handle Livewire component refresh/reload
document.addEventListener('livewire:load', function() {
    // Reset initialization flag when component reloads
    tinyMCEInitialized = false;
    
    // Reinitialize components after a brief delay
    setTimeout(function() {
        if ($('#rol').length && !$('#rol').hasClass('select2-hidden-accessible')) {
            $('#rol').select2();
            $('#rol').on('change', function(e) {
                let data = $(this).val();
                $wire.set('asign_to', data);
            });
        }
        
        if ($('#pro-message').length && !tinymce.get('pro-message')) {
            initTinyMCE();
        }
    }, 100);
});

// Error handling for TinyMCE
tinymce.on('AddEditor', function(e) {
    e.editor.on('error', function(error) {
        console.error('TinyMCE Error:', error);
    });
});

// Additional utility functions
function refreshComponents() {
    // Force refresh of both components
    cleanupResources();
    setTimeout(function() {
        initTinyMCE();
        $('#rol').select2();
    }, 100);
}

// Debounced content update to prevent excessive calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = function() {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Use debounced version for content updates
const debouncedUpdate = debounce(function(content) {
    @this.call('updateDescription', content);
}, 500);
</script>
@endscript
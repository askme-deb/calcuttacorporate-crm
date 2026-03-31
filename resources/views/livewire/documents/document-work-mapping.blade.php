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
                                <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard')}}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Map Documents to Works</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Map Documents to Works</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{-- Column 1: Document Selection --}}
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label">Select Document:</label>
                                        <select wire:model="selectedDocument" id="selectedDocument"
                                            wire:change="updateSelectedDocument($event.target.value)"
                                            class="form-control">
                                            <option value="">-- Select Document --</option>
                                            @foreach ($documents as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Column 2: Works + Button (visible only if a document is selected) --}}
                                @if ($selectedDocument)
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label mb-2">Select Works:</label>
                                        @foreach ($works as $work)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                wire:model="selectedWorks" value="{{ $work->id }}"
                                                id="work_{{ $work->id }}">
                                            <label class="form-check-label" for="work_{{ $work->id }}">
                                                {{ $work->name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>

                               
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <span wire:loading wire:click="saveMapping">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                        </span>
                                        <span wire:loading.remove wire:click="saveMapping">
                                            Save Mapping
                                        </span>
                                    </button>

                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div><!--end card-->
            </div> <!--end col-->
        </div><!--end row-->






    </div><!-- container -->
    <script>
        document.addEventListener("livewire:init", function() {
            document.getElementById('selectedDocument').addEventListener('change', function() {
                let selectedValue = this.value;
                console.log("Selected Leave Type:", selectedValue); // Debugging log
                Livewire.dispatch('updateSelectedDocument', selectedValue);
            });
        });
    </script>
    <!--Start Footer-->
    <livewire:layout.footer />

    <!--end modal-->

    <!--end footer-->
</div>
<!-- end page content -->
</div>
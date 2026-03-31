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

                                <li class="breadcrumb-item active">LIST OF DOCUMENTS</li>
                            </ol>
                        </div>
                        <h4 class="page-title">LIST OF DOCUMENTS</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            <!-- <button class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" wire:click="addLeadSource()" type="button" >
                                <span wire:loading wire:target="addLeadSource">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                                <span wire:loading.remove wire:target="addLeadSource">
                                    Add New <i class="fas fa-plus"></i>
                                </span>

                              </button> -->
<div class="container py-4">
    <form wire:submit.prevent="save" class="row g-3">
        {{-- Category Name --}}
        <div class="col-md-6">
            <label for="name" class="form-label">Document Name</label>
            <input type="text" id="name" wire:model="name" class="form-control">
            @error('name') 
                <div class="text-danger small">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Parent Category --}}
        <div class="col-md-6">
            <label for="parent_id" class="form-label">Parent Document</label>
            <select id="parent_id" wire:model="parent_id" class="form-select">
                <option value="">-- No Parent --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('parent_id') 
                <div class="text-danger small">{{ $message }}</div> 
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mt-4">
            {{ session('message') }}
        </div>
    @endif

    <hr class="my-4">

    {{-- Category Tree --}}
    <h3 class="h5">Documents Tree</h3>
    <ul class="list-unstyled ps-3">
    @foreach($categories as $cat)
       <li>
            <i class="far fa-folder me-1"></i>
            <strong>{{ $cat->name }}</strong>

            @if ($cat->children->count())
                <ul class="list-unstyled ps-4">
                    @foreach ($cat->children as $child)
                        <li>
                            <i class="far fa-folder-open me-1"></i>
                            {{ $child->name }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>

</div>


                          
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->






        </div><!-- container -->

       <!--Start Footer-->
       <livewire:layout.footer />

    <!--end modal-->

       <!--end footer-->
    </div>
    <!-- end page content -->
</div>









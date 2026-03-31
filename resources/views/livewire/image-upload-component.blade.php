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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}" wire:navigate>Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('users')}}" wire:navigate>Users</a></li>
                                <li class="breadcrumb-item active">Create New</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Create New User</h4>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            Create User
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="save" class="text-center">
                                <div x-data="{ isDragging: false }"
                                     x-on:dragover.prevent="isDragging = true"
                                     x-on:dragleave.prevent="isDragging = false"
                                     x-on:drop.prevent="isDragging = false; $wire.uploadMultiple('files', $event.dataTransfer.files)"
                                     class="border border-2 border-secondary p-4 rounded text-center bg-light"
                                     :class="{'border-primary bg-primary bg-opacity-10': isDragging}">

                                    <input type="file" multiple wire:model="files" class="d-none" id="fileInput">
                                    <label for="fileInput" class="d-block">
                                        <p class="text-muted" x-show="!isDragging">Drag & Drop Files Here or Click to Upload</p>
                                        <p class="text-primary fw-bold" x-show="isDragging">Drop the files here...</p>
                                    </label>
                                </div>

                                <!-- Show selected file names -->
                                @if ($files)
                                    <div class="mt-3">
                                        <ul class="list-group">
                                            @foreach ($files as $file)
                                                <li class="list-group-item text-success">{{ $file->getClientOriginalName() }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Validation Error -->
                                @error('files.*')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror

                                <!-- Upload Button -->
                                <button type="submit" class="btn btn-primary mt-3">Upload</button>

                                <!-- Success Message -->
                                @if (session()->has('message'))
                                    <p class="mt-3 text-success">{{ session('message') }}</p>
                                @endif
                            </form>

                            {{-- <form wire:submit.prevent="save" class="text-center">
                                <!-- Drag & Drop File Upload -->
                                <div
                                    x-data="{ isDragging: false }"
                                    x-on:dragover.prevent="isDragging = true"
                                    x-on:dragleave.prevent="isDragging = false"
                                    x-on:drop.prevent="isDragging = false; $wire.upload('file', $event.dataTransfer.files[0])"
                                    class="border border-2 border-secondary p-4 rounded text-center bg-light"
                                    :class="{'border-primary bg-primary bg-opacity-10': isDragging}"
                                >
                                    <input type="file" wire:model="file" class="d-none" id="fileInput">
                                    <label for="fileInput" class="d-block">
                                        <p class="text-muted" x-show="!isDragging">Drag & Drop File Here or Click to Upload</p>
                                        <p class="text-primary fw-bold" x-show="isDragging">Drop the file here...</p>
                                    </label>
                                </div>

                                <!-- Show selected file name -->
                                @if ($file)
                                    <div class="mt-3">
                                        <p class="text-success">Selected file: {{ $file->getClientOriginalName() }}</p>
                                    </div>
                                @endif

                                <!-- Validation Error -->
                                @error('file')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror

                                <!-- Upload Button -->
                                <button type="submit" class="btn btn-primary mt-3">Upload</button>

                                <!-- Success Message -->
                                @if (session()->has('message'))
                                    <p class="mt-3 text-success">{{ session('message') }}</p>
                                @endif
                            </form> --}}
                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- end container-fluid -->

        <!-- Footer Start -->
        <livewire:layout.footer />
        <!-- end Footer -->

    </div> <!-- end page content -->
</div> <!-- end page-wrapper -->

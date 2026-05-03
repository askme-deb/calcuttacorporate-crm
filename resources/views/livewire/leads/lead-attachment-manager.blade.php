<div class="mt-3">

    {{-- Status Message --}}


    {{-- Upload Section --}}
    <div class="card border-0 shadow-sm mb-3"
        x-data="{
            selectedFiles: [],
            uploading: false,
            progress: 0,
            handleFiles(event) {
                const newFiles = Array.from(event.target.files);
                newFiles.forEach(f => {
                    const entry = { file: f, preview: null };
                    if (f.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = e => { entry.preview = e.target.result; };
                        reader.readAsDataURL(f);
                    }
                    this.selectedFiles.push(entry);
                });
                event.target.value = '';
            },
            removeFile(index) {
                this.selectedFiles.splice(index, 1);
            },
            fileIcon(name) {
                const ext = name.split('.').pop().toLowerCase();
                const map = {
                    pdf:  { icon: 'fa-file-pdf',  color: '#dc3545' },
                    doc:  { icon: 'fa-file-word', color: '#2b579a' },
                    docx: { icon: 'fa-file-word', color: '#2b579a' },
                    xls:  { icon: 'fa-file-excel', color: '#217346' },
                    xlsx: { icon: 'fa-file-excel', color: '#217346' },
                    csv:  { icon: 'fa-file-csv',   color: '#217346' },
                    txt:  { icon: 'fa-file-alt',   color: '#6c757d' },
                    jpg:  { icon: 'fa-file-image', color: '#fd7e14' },
                    jpeg: { icon: 'fa-file-image', color: '#fd7e14' },
                    png:  { icon: 'fa-file-image', color: '#fd7e14' },
                    webp: { icon: 'fa-file-image', color: '#fd7e14' },
                };
                return map[ext] ?? { icon: 'fa-file', color: '#6c757d' };
            },
            getFiles() {
                return this.selectedFiles.map(e => e.file);
            },
            async doUpload() {
                if (this.selectedFiles.length === 0) return;
                this.uploading = true;
                this.progress = 0;
                $wire.uploadMultiple(
                    'files',
                    this.getFiles(),
                    () => {
                        // success: all files staged, now call PHP upload()
                        this.uploading = false;
                        this.selectedFiles = [];
                        $wire.call('saveFiles');
                    },
                    () => {
                        // error during temp upload
                        this.uploading = false;
                        $wire.set('statusType', 'danger');
                        $wire.set('statusMessage', 'File transfer failed. Please try again.');
                    },
                    (event) => {
                        this.progress = event.detail.progress;
                    }
                );
            }
        }">
        <div class="card-body p-3">

            {{-- Dropzone --}}
            <label class="w-100 border border-2 border-success-subtle rounded-3 p-4 text-center bg-light position-relative"
                style="cursor:pointer; border-style:dashed; transition: all 0.2s ease;">

                <input type="file"
                    class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                    multiple
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png,.webp,.txt"
                    @change="handleFiles($event)" />

                <div x-show="!uploading">
                    <div class="fw-semibold text-success mb-1">
                        <i class="fas fa-cloud-upload-alt me-1"></i>
                        Drag & Drop files here
                    </div>
                    <div class="text-muted small">
                        or click to browse (Max: 10MB each)
                    </div>
                </div>

                <div x-show="uploading">
                    <div class="fw-semibold text-primary">
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Transferring... <span x-text="Math.round(progress) + '%'"></span>
                    </div>
                </div>
            </label>

            {{-- Errors --}}
            @error('files')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
            @error('files.*')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror

            {{-- Selected Files --}}
            <template x-if="selectedFiles.length > 0">
                <div class="mt-3">
                    <div class="text-muted small mb-1 fw-semibold">
                        Selected (<span x-text="selectedFiles.length"></span> file(s))
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <template x-for="(entry, index) in selectedFiles" :key="index">
                            <div class="border rounded-2 bg-white position-relative d-flex flex-column align-items-center justify-content-center p-2"
                                style="width:90px; min-height:90px; overflow:hidden;">

                                {{-- Image preview --}}
                                <template x-if="entry.preview">
                                    <img :src="entry.preview" class="rounded-1 mb-1" style="width:56px;height:56px;object-fit:cover;" />
                                </template>

                                {{-- File type icon --}}
                                <template x-if="!entry.preview">
                                    <i class="fas mb-1" :class="fileIcon(entry.file.name).icon" :style="'font-size:2rem; color:' + fileIcon(entry.file.name).color"></i>
                                </template>

                                <span class="text-truncate small text-muted w-100 text-center" style="max-width:80px; font-size:0.7rem;" x-text="entry.file.name"></span>

                                {{-- Remove --}}
                                <button type="button"
                                    class="position-absolute top-0 end-0 btn btn-sm p-0 lh-1 text-danger border-0 bg-transparent"
                                    style="width:18px;height:18px;font-size:0.75rem;"
                                    @click.stop="removeFile(index)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Upload Button --}}
            <div class="mt-3">
                <button type="button"
                    @click="doUpload"
                    :disabled="uploading || selectedFiles.length === 0"
                    wire:loading.attr="disabled"
                    wire:target="saveFiles"
                    class="btn btn-success w-100 d-flex align-items-center justify-content-center">

                    <span wire:loading.remove wire:target="saveFiles">
                        <template x-if="!uploading">
                            <span><i class="fas fa-upload me-1"></i> Upload Files</span>
                        </template>
                        <template x-if="uploading">
                            <span><span class="spinner-border spinner-border-sm me-1"></span> Transferring...</span>
                        </template>
                    </span>

                    <span wire:loading wire:target="saveFiles">
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Saving...
                    </span>
                </button>
            </div>

        </div>
    </div>

    {{-- Attachment List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold small">
            Attachments
        </div>

        <ul class="list-group list-group-flush">

            @forelse($attachments as $attachment)
                @php
                    $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                    $iconMap = [
                        'pdf'  => ['fa-file-pdf',   '#dc3545'],
                        'doc'  => ['fa-file-word',  '#2b579a'],
                        'docx' => ['fa-file-word',  '#2b579a'],
                        'xls'  => ['fa-file-excel', '#217346'],
                        'xlsx' => ['fa-file-excel', '#217346'],
                        'csv'  => ['fa-file-csv',   '#217346'],
                        'txt'  => ['fa-file-alt',   '#6c757d'],
                        'jpg'  => ['fa-file-image', '#fd7e14'],
                        'jpeg' => ['fa-file-image', '#fd7e14'],
                        'png'  => ['fa-file-image', '#fd7e14'],
                        'webp' => ['fa-file-image', '#fd7e14'],
                    ];
                    [$fileIcon, $fileColor] = $iconMap[$ext] ?? ['fa-file', '#6c757d'];
                    $isImage = in_array($ext, ['jpg','jpeg','png','webp']);
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">

                    {{-- File Icon + Name --}}
                    <div class="d-flex align-items-center gap-3">
                        @if($isImage)
                            <img src="{{ $attachment->getUrl() }}" class="rounded-2" style="width:40px;height:40px;object-fit:cover;">
                        @else
                            <i class="fas {{ $fileIcon }} fa-2x" style="color:{{ $fileColor }}; min-width:28px;"></i>
                        @endif
                        <div class="d-flex flex-column">
                            <a href="{{ $attachment->getUrl() }}"
                                target="_blank"
                                class="fw-semibold text-primary text-decoration-none small">
                                {{ $attachment->file_name }}
                            </a>
                            <span class="text-muted small">
                                {{ $attachment->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            wire:click="deleteAttachment({{ $attachment->id }})"
                            wire:loading.attr="disabled"
                            wire:target="deleteAttachment">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>

                </li>
            @empty
                <li class="list-group-item text-center text-muted small py-4">
                    <i class="fas fa-folder-open mb-2"></i><br>
                    No attachments uploaded yet
                </li>
            @endforelse

        </ul>
    </div>

</div>

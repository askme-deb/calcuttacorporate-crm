<div wire:poll.30s="loadEmails" class="page-wrapper">
<style>
    .gmail-email-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #202124;
    }

    .gmail-email-header {
        padding: 24px;
        border-bottom: 1px solid #e8eaed;
    }

    .gmail-email-subject {
        font-size: 22px;
        font-weight: 400;
        margin-bottom: 12px;
        color: #202124;
        line-height: 1.3;
    }

    .gmail-email-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .gmail-sender-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .gmail-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #1a73e8;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 500;
        margin-right: 12px;
        font-size: 14px;
        text-transform: uppercase;
    }

    .gmail-sender-details {
        flex: 1;
    }

    .gmail-sender-name {
        font-weight: 500;
        color: #202124;
        margin-bottom: 2px;
        font-size: 14px;
    }

    .gmail-sender-email {
        color: #5f6368;
        font-size: 13px;
    }

    .gmail-timestamp {
        color: #5f6368;
        font-size: 13px;
        white-space: nowrap;
        margin-left: 16px;
    }

    .gmail-actions {
        display: flex;
        gap: 8px;
        margin-left: 16px;
    }

    .gmail-action-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #5f6368;
        transition: background-color 0.2s;
    }

    .gmail-action-btn:hover {
        background-color: #f1f3f4;
    }

    .gmail-recipients-row {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #5f6368;
        margin-bottom: 4px;
    }

    .gmail-recipients-label {
        margin-right: 8px;
        min-width: 25px;
        font-weight: 500;
    }

    .gmail-email-body {
        padding: 0 24px 24px;
        color: #202124;
        font-size: 14px;
        line-height: 1.6;
    }

    .gmail-email-body p {
        margin-bottom: 12px;
    }

    .gmail-attachments-section {
        padding: 0 24px 24px;
        border-top: 1px solid #e8eaed;
    }

    .gmail-attachments-header {
        font-size: 14px;
        font-weight: 500;
        color: #202124;
        margin: 16px 0 12px;
        display: flex;
        align-items: center;
    }

    .gmail-attachments-header i {
        margin-right: 8px;
        color: #5f6368;
    }

    .gmail-attachment-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .gmail-attachment-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        text-decoration: none;
        color: #1a73e8;
        background: #f8f9fa;
        transition: background-color 0.2s;
        font-size: 13px;
    }

    .gmail-attachment-item:hover {
        background-color: #e8f0fe;
        text-decoration: none;
        color: #1557b0;
    }

    .gmail-attachment-item i {
        margin-right: 8px;
        color: #5f6368;
    }

    .gmail-conversation-section {
        border-top: 1px solid #e8eaed;
    }

    .gmail-conversation-header {
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8f9fa;
    }

    .gmail-conversation-title {
        font-size: 14px;
        font-weight: 500;
        color: #202124;
        display: flex;
        align-items: center;
        margin: 0;
    }

    .gmail-conversation-title i {
        margin-right: 8px;
        color: #5f6368;
    }

    .gmail-thread-controls {
        display: flex;
        gap: 8px;
    }

    .gmail-thread-btn {
        background: none;
        border: none;
        color: #1a73e8;
        font-size: 12px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
        text-decoration: none;
    }

    .gmail-thread-btn:hover {
        background-color: #e8f0fe;
        color: #1557b0;
        text-decoration: none;
    }

    .gmail-threaded-conversation {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .gmail-reply-section {
        border-top: 1px solid #e8eaed;
        padding: 24px;
        background: #f8f9fa;
    }

    .gmail-reply-header {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }

    .gmail-reply-header i {
        margin-right: 8px;
        color: #5f6368;
    }

    .gmail-reply-header h6 {
        font-size: 14px;
        font-weight: 500;
        color: #202124;
        margin: 0;
    }

    .gmail-reply-form {
        background: white;
        border-radius: 8px;
        border: 1px solid #dadce0;
        overflow: hidden;
    }

    .gmail-reply-textarea {
        width: 100%;
        border: none;
        padding: 16px;
        font-size: 14px;
        color: #202124;
        resize: vertical;
        min-height: 120px;
        font-family: inherit;
        outline: none;
        background: white;
    }

    .gmail-reply-toolbar {
        padding: 12px 16px;
        border-top: 1px solid #e8eaed;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8f9fa;
        flex-wrap: wrap;
        gap: 12px;
    }

    .gmail-reply-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .gmail-btn-primary {
        background: #1a73e8;
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .gmail-btn-primary:hover {
        background: #1557b0;
    }

    .gmail-file-input-wrapper {
        position: relative;
        display: inline-block;
    }

    .gmail-file-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .gmail-file-input-label {
        display: flex;
        align-items: center;
        color: #5f6368;
        cursor: pointer;
        font-size: 14px;
        transition: color 0.2s;
        text-decoration: none;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .gmail-file-input-label:hover {
        color: #1a73e8;
        background-color: #e8f0fe;
        text-decoration: none;
    }

    .gmail-file-input-label i {
        margin-right: 4px;
    }

    .gmail-error-message {
        color: #d93025;
        font-size: 12px;
        margin-top: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .gmail-email-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .gmail-actions {
            margin-left: 0;
        }

        .gmail-reply-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
    <!-- Page Content -->
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Metrica</a></li>
                                <li class="breadcrumb-item"><a href="#">Apps</a></li>
                                <li class="breadcrumb-item active">Inbox</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Inbox</h4>
                    </div>
                </div>
            </div>
            <!-- End Page Title -->

            <div class="row">
                <div class="col-12">

                    <!-- Left Sidebar -->
                    <div class="email-leftbar">
                        <button type="button" class="btn btn-primary btn-sm w-100" wire:click="$dispatch('openCompose')">
                            <i class="fas fa-feather-alt me-2"></i> Compose
                        </button>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="mail-list">
                                    @foreach ([
                                    'INBOX' => ['label' => 'Inbox', 'icon' => 'las la-inbox'],
                                    '[Gmail]/Starred' => ['label' => 'Starred', 'icon' => 'las la-star'],
                                    '[Gmail]/Important' => ['label' => 'Important', 'icon' => 'las la-tag'],
                                    '[Gmail]/Drafts' => ['label' => 'Draft', 'icon' => 'las la-file-alt'],
                                    '[Gmail]/Sent Mail' => ['label' => 'Sent', 'icon' => 'las la-paper-plane'],
                                    '[Gmail]/Trash' => ['label' => 'Trash', 'icon' => 'las la-trash-alt'],
                                    ] as $key => $data)
                                    <a href="#"
                                        wire:click.prevent="loadFolder('{{ $key }}')"
                                        class="{{ $folder == $key ? 'active' : '' }}">
                                        <i class="{{ $data['icon'] }} font-15 me-1"></i> {{ $data['label'] }}
                                        @if(!empty($newEmailCounts[$key]) && $newEmailCounts[$key] > 0)
                                        <span class="badge bg-danger ms-1">{{ $newEmailCounts[$key] }}</span>
                                        @endif
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Left Sidebar -->

                    <!-- Right Content -->
                    <div class="email-rightbar">

                        {{-- Email List --}}
                        @if(!$selectedEmail)
                        <div class="card my-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ ucfirst(str_replace(['[Gmail]/', '_'], ['', ' '], $folder)) }}</h6>
                                <small class="text-muted">{{ $emails->count() }} of {{ $total }} emails</small>
                            </div>

                            <ul class="message-list">
                                @forelse ($emails as $email)
                                <li wire:key="email-{{ $email->uid }}"
                                    wire:click="openEmail({{ $email->uid }})"
                                    class="{{ $email->seen ? '' : 'unread' }} {{ in_array($email->uid, $newEmailUids) ? 'new-email' : '' }}">

                                    <!-- Left side -->
                                    <div class="col-mail col-mail-1">
                                        <div class="checkbox-wrapper-mail">
                                            <input type="checkbox" id="chk{{ $email->uid }}">
                                            <label for="chk{{ $email->uid }}" class="toggle"></label>
                                        </div>
                                        <p class="title mb-0">{{ Str::limit($email->from ?? '(Unknown)', 20) }}</p>
                                        <span class="star-toggle {{ $email->is_starred ? 'fas fa-star text-warning' : 'far fa-star' }}"></span>
                                    </div>

                                    <!-- Right side -->
                                    <div class="col-mail col-mail-2">
                                        <span class="subject">
                                            {{ Str::limit($email->subject ?? '(No Subject)', 60) }}
                                            @if($email->has_attachments)
                                            <i class="fas fa-paperclip ms-2 text-muted"></i>
                                            @endif
                                            – <span class="teaser">{{ Str::limit(strip_tags($email->body_plain ?? ''), 40) }}</span>
                                        </span>
                                        <div class="date">
                                            {{ $email->date ? \Carbon\Carbon::parse($email->date)->format('M. j') : '-' }}
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="text-center p-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    No emails found in this folder
                                </li>
                                @endforelse
                            </ul>
                        </div>

                        {{-- Pagination --}}
                        @if($totalPages > 1)
                        <div class="row mb-3">
                            <div class="col-7 align-self-center">
                                Showing {{ ($currentPage - 1) * $perPage + 1 }} - {{ min($currentPage * $perPage, $total) }} of {{ $total }}
                            </div>
                            <div class="col-5">
                                <div class="btn-group float-end">
                                    <button class="btn btn-sm btn-de-secondary" wire:click="previousPage" {{ $currentPage <= 1 ? 'disabled' : '' }}>
                                        <i class="fa fa-chevron-left"></i>
                                    </button>
                                    <button class="btn btn-sm btn-de-secondary" wire:click="nextPage" {{ $currentPage >= $totalPages ? 'disabled' : '' }}>
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif

                        {{-- Single Email Preview --}}
                       {{-- Single Email Preview --}}
@if($selectedEmail)
<div class="gmail-email-container mt-3">
    {{-- Email Header --}}
    <div class="gmail-email-header">
        <h1 class="gmail-email-subject">{{ $selectedEmail['email']->subject ?? '(No Subject)' }}</h1>
        
        <div class="gmail-email-meta">
            <div class="gmail-sender-info">
                <div class="gmail-avatar">
                    {{ strtoupper(substr($selectedEmail['email']->from ?? 'U', 0, 2)) }}
                </div>
                <div class="gmail-sender-details">
                    <div class="gmail-sender-name">
                        {{ $selectedEmail['email']->from_name ?? ($selectedEmail['email']->from ?? 'Unknown') }}
                    </div>
                    <div class="gmail-sender-email">{{ $selectedEmail['email']->from ?? '(Unknown)' }}</div>
                </div>
            </div>
            <div class="gmail-timestamp">
                {{ $selectedEmail['email']->date ? \Carbon\Carbon::parse($selectedEmail['email']->date)->format('M j, Y g:i A') : '-' }}
            </div>
            <div class="gmail-actions">
                <button class="gmail-action-btn" title="Reply">
                    <i class="fas fa-reply"></i>
                </button>
                <button class="gmail-action-btn" title="Forward">
                    <i class="fas fa-share"></i>
                </button>
                <button class="gmail-action-btn" title="More">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </div>

        <div class="gmail-recipients-row">
            <span class="gmail-recipients-label">to</span>
            <span>{{ $selectedEmail['email']->to ?? '-' }}</span>
        </div>
        @if($selectedEmail['email']->cc)
        <div class="gmail-recipients-row">
            <span class="gmail-recipients-label">cc</span>
            <span>{{ $selectedEmail['email']->cc }}</span>
        </div>
        @endif
    </div>

    {{-- Email Body --}}
    <div class="gmail-email-body">
        {!! $selectedEmail['email']->body !!}
    </div>

    {{-- Attachments --}}
    @if(!empty($selectedEmail['attachments']))
    <div class="gmail-attachments-section">
        <div class="gmail-attachments-header">
            <i class="fas fa-paperclip"></i>
            {{ count($selectedEmail['attachments']) }} Attachment{{ count($selectedEmail['attachments']) > 1 ? 's' : '' }}
        </div>
        <ul class="gmail-attachment-list">
            @foreach($selectedEmail['attachments'] as $file)
            <li>
                <a href="{{ $file['url'] }}" target="_blank" class="gmail-attachment-item">
                    @php
                        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $icon = match($extension) {
                            'pdf' => 'fas fa-file-pdf',
                            'doc', 'docx' => 'fas fa-file-word',
                            'xls', 'xlsx' => 'fas fa-file-excel',
                            'ppt', 'pptx' => 'fas fa-file-powerpoint',
                            'zip', 'rar' => 'fas fa-file-archive',
                            'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image',
                            default => 'fas fa-file'
                        };
                    @endphp
                    <i class="{{ $icon }}"></i>
                    {{ $file['name'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Threaded Conversation --}}
    @if(!empty($conversation))
    <div class="gmail-conversation-section">
        <div class="gmail-conversation-header">
            <h6 class="gmail-conversation-title">
                <i class="fas fa-comments"></i>
                Conversation Thread
            </h6>
            <div class="gmail-thread-controls">
                <button type="button" class="gmail-thread-btn" id="expandAll">
                    <i class="fas fa-plus"></i> Expand All
                </button>
                <button type="button" class="gmail-thread-btn" id="collapseAll">
                    <i class="fas fa-minus"></i> Collapse All
                </button>
            </div>
        </div>

        <ul class="gmail-threaded-conversation">
            @include('livewire.emails.partials.reply_thread', ['conversation' => $conversation])
        </ul>
    </div>
    @endif

    {{-- Reply Form --}}
    <div class="gmail-reply-section">
        <div class="gmail-reply-header">
            <i class="fas fa-reply"></i>
            <h6>Reply</h6>
        </div>
        
        <form wire:submit.prevent="sendReply">
            <div class="gmail-reply-form">
                <textarea wire:model.defer="replyBody" class="gmail-reply-textarea" rows="6" placeholder="Type your reply..."></textarea>
                <div class="gmail-reply-toolbar">
                    <div class="gmail-reply-actions">
                        <button type="submit" class="gmail-btn-primary">Send</button>
                        
                        <div class="gmail-file-input-wrapper">
                            <input type="file" wire:model="attachments" multiple id="gmail-attachments" class="gmail-file-input">
                            <label for="gmail-attachments" class="gmail-file-input-label">
                                <i class="fas fa-paperclip"></i>
                                Attach files
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            @error('replyBody') 
            <div class="gmail-error-message mt-2">{{ $message }}</div> 
            @enderror
            
            @error('attachments.*') 
            <div class="gmail-error-message mt-1">{{ $message }}</div> 
            @enderror
        </form>
    </div>
</div>

{{-- JavaScript for thread functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Expand All functionality
    const expandBtn = document.getElementById('expandAll');
    const collapseBtn = document.getElementById('collapseAll');
    
    if (expandBtn) {
        expandBtn.addEventListener('click', function() {
            const allThreads = document.querySelectorAll('.thread-content, .gmail-thread-content');
            allThreads.forEach(thread => {
                thread.style.display = 'block';
            });
        });
    }
    
    if (collapseBtn) {
        collapseBtn.addEventListener('click', function() {
            const allThreads = document.querySelectorAll('.thread-content, .gmail-thread-content');
            allThreads.forEach(thread => {
                thread.style.display = 'none';
            });
        });
    }
});
</script>
@endif


                    </div>
                    <!-- End Right Content -->

                </div>
            </div>

        </div>
    </div>

</div>
@push('scripts')
<script>
    function initConversationCollapse() {
        // Toggle chevron icon rotation
        document.querySelectorAll('.toggle-replies').forEach(btn => {
            const icon = btn.querySelector('i');
            const collapseEl = document.querySelector(btn.dataset.bsTarget);

            if (!collapseEl) return;

            collapseEl.addEventListener('show.bs.collapse', () => icon.style.transform = 'rotate(90deg)');
            collapseEl.addEventListener('hide.bs.collapse', () => icon.style.transform = 'rotate(0deg)');
        });

        // Expand/Collapse all
        const expandAllBtn = document.getElementById('expandAll');
        const collapseAllBtn = document.getElementById('collapseAll');

        if (expandAllBtn) {
            expandAllBtn.onclick = () => {
                document.querySelectorAll('.threaded-conversation .collapse').forEach(el => {
                    const bsCollapse = new bootstrap.Collapse(el, {
                        toggle: false
                    });
                    bsCollapse.show();
                });
            };
        }

        if (collapseAllBtn) {
            collapseAllBtn.onclick = () => {
                document.querySelectorAll('.threaded-conversation .collapse').forEach(el => {
                    const bsCollapse = new bootstrap.Collapse(el, {
                        toggle: false
                    });
                    bsCollapse.hide();
                });
            };
        }
    }

    // Initialize on first load
    initConversationCollapse();

    // Re-initialize after Livewire updates
    Livewire.hook('message.processed', (message, component) => {
        initConversationCollapse();
    });
</script>
@endpush
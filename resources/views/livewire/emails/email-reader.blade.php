<div wire:poll.30s="loadEmails" class="page-wrapper">
    <style>
        /* Gmail-style collapse arrow */
        .reply-item .toggle-replies {
            background: none;
            border: none;
            cursor: pointer;
        }

        .reply-item .toggle-replies i {
            transition: transform 0.2s ease;
        }

        /* Rotate chevron when collapsed/expanded */
        .reply-item .collapse.show+.reply-item>.toggle-replies i,
        .reply-item .collapse.show~ul.collapse.show~.toggle-replies i {
            transform: rotate(90deg);
        }

        .reply-item .reply-body {
            margin-top: 4px;
            margin-bottom: 4px;
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
                                <li class="breadcrumb-item"><a href="#">Email</a></li>
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
                        <button type="button" class="btn btn-primary btn-sm w-100"
                            wire:click="$dispatch('openCompose')">
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
                                        <a href="#" wire:click.prevent="loadFolder('{{ $key }}')"
                                            class="{{ $folder == $key ? 'active' : '' }}">
                                            <i class="{{ $data['icon'] }} font-15 me-1"></i> {{ $data['label'] }}
                                            @if (!empty($newEmailCounts[$key]) && $newEmailCounts[$key] > 0)
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
                        @if (!$selectedEmail)
                            <div class="card my-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ ucfirst(str_replace(['[Gmail]/', '_'], ['', ' '], $folder)) }}
                                    </h6>
                                    <small class="text-muted">{{ $emails->count() }} of {{ $total }}
                                        emails</small>
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
                                                <p class="title mb-0">{{ Str::limit($email->from ?? '(Unknown)', 2000) }}
                                                </p>
                                                <span
                                                    class="star-toggle {{ $email->is_starred ? 'fas fa-star text-warning' : 'far fa-star' }}"></span>
                                            </div>

                                            <!-- Right side -->
                                            <div class="col-mail col-mail-2">
                                                <span class="subject">
                                                    {{ Str::limit($email->subject ?? '(No Subject)', 60) }}
                                                    @if ($email->has_attachments)
                                                        <i class="fas fa-paperclip ms-2 text-muted"></i>
                                                    @endif
                                                    – <span
                                                        class="teaser">{{ Str::limit(strip_tags($email->body_plain ?? ''), 40) }}</span>
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
                            @if ($totalPages > 1)
                                <div class="row mb-3">
                                    <div class="col-7 align-self-center">
                                        Showing {{ ($currentPage - 1) * $perPage + 1 }} -
                                        {{ min($currentPage * $perPage, $total) }} of {{ $total }}
                                    </div>
                                    <div class="col-5">
                                        <div class="btn-group float-end">
                                            <button class="btn btn-sm btn-de-secondary" wire:click="previousPage"
                                                {{ $currentPage <= 1 ? 'disabled' : '' }}>
                                                <i class="fa fa-chevron-left"></i>
                                            </button>
                                            <button class="btn btn-sm btn-de-secondary" wire:click="nextPage"
                                                {{ $currentPage >= $totalPages ? 'disabled' : '' }}>
                                                <i class="fa fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Single Email Preview --}}
                        @if ($selectedEmail)
                            <div class="card mt-3">
                                <div class="card-body">

                                    {{-- Email Header --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1">{{ $selectedEmail['email']->subject ?? '(No Subject)' }}
                                            </h6>
                                            <p class="text-muted mb-0">From:
                                                {{ $selectedEmail['email']->from ?? '(Unknown)' }}</p>
                                            <p class="text-muted mb-0">To: {{ $selectedEmail['email']->to ?? '-' }}</p>
                                            @if ($selectedEmail['email']->cc)
                                                <p class="text-muted mb-0">CC: {{ $selectedEmail['email']->cc }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <small
                                                class="text-muted">{{ $selectedEmail['email']->date ? \Carbon\Carbon::parse($selectedEmail['email']->date)->format('M j, Y H:i') : '-' }}</small>
                                        </div>
                                    </div>

                                    {{-- Email Body --}}
                                    <div class="email-body mb-3">
                                        {!! $selectedEmail['email']->body !!}
                                    </div>

                                    {{-- Attachments --}}
                                    @if (!empty($selectedEmail['attachments']))
                                        <hr>
                                        <h6>Attachments</h6>
                                        <ul class="list-unstyled ps-0">
                                            @foreach ($selectedEmail['attachments'] as $file)
                                                <li>
                                                    <a href="{{ $file['url'] }}"
                                                        target="_blank">{{ $file['name'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    {{-- Threaded Conversation --}}
                                    @if (!empty($conversation))
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6>Conversation Thread</h6>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-link p-0 me-2"
                                                    id="expandAll">
                                                    <i class="fas fa-plus"></i> Expand All
                                                </button>
                                                <button type="button" class="btn btn-sm btn-link p-0" id="collapseAll">
                                                    <i class="fas fa-minus"></i> Collapse All
                                                </button>
                                            </div>
                                        </div>

                                        <ul class="threaded-conversation list-unstyled ps-0">
                                            @include('livewire.emails.partials.reply_thread', [
                                                'conversation' => $conversation,
                                            ])
                                        </ul>
                                    @endif


                                    {{-- Reply Form --}}
                                    <hr>
                                    <h6>Reply</h6>
                                    <form wire:submit.prevent="sendReply">
                                        <textarea wire:model.defer="replyBody" class="form-control mb-2" rows="4" placeholder="Type your reply..."></textarea>
                                        @error('replyBody')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        <div class="mb-2">
                                            <input type="file" wire:model="attachments" multiple>
                                            @error('attachments.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm">Send Reply</button>
                                    </form>

                                </div>
                            </div>
                        @endif


                    </div>
                    <!-- End Right Content -->

                </div>
            </div>

        </div>
    </div>

</div>

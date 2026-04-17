<li class="reply-item mb-2">
    <div class="d-flex align-items-center">
        {{-- Collapse toggle arrow --}}
        @if($conversation->replies->count())
            <button class="btn btn-sm toggle-replies me-2 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#replies-{{ $conversation->id }}">
                <i class="fas fa-chevron-right"></i>
            </button>
        @else
            <span class="me-4"></span>
        @endif

        <strong>{{ $conversation->from ?? '(Unknown)' }}</strong>
        <span class="text-muted ms-2">{{ $conversation->date ? \Carbon\Carbon::parse($conversation->date)->format('M d, Y H:i') : '-' }}</span>
    </div>

    <div class="reply-body ps-4">
        {!! $conversation->body !!}
    </div>

    @if($conversation->replies->count())
        <ul class="collapse ps-4 mt-2" id="replies-{{ $conversation->id }}">
            @foreach($conversation->replies as $reply)
                @include('livewire.emails.partials.reply_thread', ['conversation' => $reply])
            @endforeach
        </ul>
    @endif
</li>

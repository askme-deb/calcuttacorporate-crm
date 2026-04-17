
<li class="dropdown notification-list" wire:poll.10s.visible="fetchNotifications">
    <div x-data="{ open: $wire.entangle('showDropdown') }">
        <a href="javascript:;" x-on:click="open = true" class="nav-link  arrow-none nav-icon"  role="button"
            aria-haspopup="false" aria-expanded="false">
            <i class="ti ti-bell fs-4"></i>
            @if(isset($notifications) && count($notifications) > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ count($notifications) }}
            </span>
            @endif

        </a>


        <div class="dropdown-menu-end dropdown-lg pt-0" style="position: absolute;inset: 0px 0px auto auto;margin: 0px;transform: translate(-8px, 38px);border: 1px solid rgb(236 238 240);display: block;box-shadow: 0 3px 12px rgba(214,228,241,.3);background: #ffffff;color: #000444;" x-show="open" x-on:click.outside="open = false">

            <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                Notifications <span class="badge bg-soft-primary badge-pill">{{ isset($notifications) ? count($notifications) : 0 }}</span>
            </h6>
            <div class="notification-menu" data-simplebar>
                @if (!empty($notifications) && count($notifications) > 0)
                @foreach ($notifications as $notification)
                <!-- item-->
                <a href="javascript:;" wire:click="markAsRead('{{ $notification->id }}')" class="dropdown-item py-3">
                    <small class="float-end text-muted ps-2">{{ timeAgo($notification->created_at)}}</small>
                    <div class="media">
                        <div class="avatar-md bg-soft-primary">
                            <i class="ti ti-chart-arcs"></i>
                        </div>
                        <div class="media-body align-self-center ms-2 text-truncate">
                            <h6 class="my-0 fw-normal text-dark">{{ $notification->data['title'] }}</h6>
                            <p style="text-wrap:auto; color:#7b8bbe;">{{ $notification->data['message'] }}</p>
                            <small class="text-muted mb-0">{{ $notification->data['name'] }}<b> Created by {{ $notification->data['created_by'] }}</b> </small>
                        </div><!--end media-body-->
                    </div><!--end media-->
                </a><!--end-item-->
                @endforeach

                @else
                <p class="text-center p-2">No new notifications</p>
                @endif
            </div>
            <!-- All-->
            <a href="{{ route('notification')}}" class="dropdown-item text-center text-primary">
                View all <i class="fi-arrow-right"></i>
            </a>
        </div>
    </div>
</li>


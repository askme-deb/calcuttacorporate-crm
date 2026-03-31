<div class="page-wrapper">
    <!-- Page Content -->
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Notifications</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Notifications</h4>
                    </div>
                </div>
            </div>
            <!-- End Page Title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if($notifications->count())
                            <div class="d-flex justify-content-end mb-2">
                                <button wire:click="markAllAsRead" class="btn btn-sm btn-success me-2">
                                    Mark All as Read
                                </button>
                                <button wire:click="clearAll" class="btn btn-sm btn-danger">
                                    Clear All
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Name</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notifications as $notification)
                                        <tr>
                                            <td>{{ $notification->data['title'] ?? 'Notification' }}</td>
                                            <td>{{ $notification->data['name'] ?? 'Notification' }}</td>
                                            <td>{{ $notification->data['created_by'] ?? 'Notification' }}</td>
                                            <td class="text-muted">{{ $notification->created_at->diffForHumans() }}</td>
                                            <td>
                                                <button wire:click="markAsRead('{{ $notification->id }}')" class="btn btn-primary btn-xs">
                                                    {{ $notification->read_at ? 'Read' : 'Mark as Read' }}
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $notifications->links() }}
                            </div>
                            @else
                            <div class="alert alert-warning text-center">
                                No new notifications.
                            </div>
                            @endif

                        </div> <!-- End card-body -->
                    </div> <!-- End card -->
                </div> <!-- End col -->
            </div> <!-- End row -->
        </div> <!-- End container -->

        <!-- Start Footer -->
        <livewire:layout.footer />
        <!-- End Footer -->
    </div> <!-- End page content -->
</div>
<script>
    document.addEventListener('livewire:init', function() {
        Livewire.on('notificationUpdated', () => {
            console.log('Notification updated event received.');
        });
    });
</script>
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

                                <li class="breadcrumb-item active">Employee Sales Target Report</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Employee Sales Target Report</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">

                            <div wire:ignore>
                                <div id="calendar"></div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const calendarEl = document.getElementById('calendar');

                                    let calendar = new FullCalendar.Calendar(calendarEl, {
                                        initialView: 'dayGridMonth',
                                        selectable: true,
                                        editable: false,
                                        events: [], // initial empty, will be updated later

                                        dateClick: function(info) {
                                            const title = prompt('Event Title:');
                                            if (title) {
                                                console.log(info.dateStr);
                                                Livewire.dispatch('calendar-event-added', {
                                                    title: title,
                                                    start: info.dateStr
                                                });
                                            }
                                        },

                                        eventClick: function(info) {
                                            if (confirm('Delete this event?')) {
                                                Livewire.dispatch('calendar-event-deleted', {
                                                    id: info.event.id
                                                });
                                            }
                                        }
                                    });

                                    calendar.render();

                                    // Now listen to Livewire events globally
                                    Livewire.on('calendar-mounted', ({
                                        events
                                    }) => {
                                        calendar.removeAllEvents();
                                        calendar.addEventSource(events);
                                        console.log(events);
                                    });

                                    Livewire.on('calendar-events-updated', ({
                                        events
                                    }) => {
                                        calendar.removeAllEvents();
                                        calendar.addEventSource(events);
                                    });
                                });
                            </script>


                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->






        </div><!-- container -->

        <!--Start Footer-->
        <livewire:layout.footer />



        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
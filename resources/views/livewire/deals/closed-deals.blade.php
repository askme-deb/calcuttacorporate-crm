<div class="page-wrapper">
    <style>
        /* Hide edit & delete icons by default */
        .action-icons {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        /* Show icons only when hovering over the card */
        .deal-card:hover .action-icons {
            opacity: 1;
        }
    </style>
    <!-- Page Content-->
    <div class="page-content-tab">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}" wire:navigate>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('deal')}}" wire:navigate>Deals</a>
                                </li>
                                <li class="breadcrumb-item active">Closed Deals</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Customers</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-5">

                            </div>
                            <div class="col-lg-7 mt-3 text-end">
                                <div class="text-end">
                                    <ul class="list-inline">
                                        <li class="list-inline-item" style="width: 60%;">
                                            <div class="input-group">
                                                <input type="text" id="example-input1-group2"
                                                    wire:model.live="search" class="form-control form-control-sm"
                                                    placeholder="Search">
                                                <button type="button" class="btn btn-primary"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </li>
                                        <li class="list-inline-item">
                                            <button type="button" class="btn btn-primary"><i
                                                    class="fas fa-filter"></i></button>
                                        </li>

                                    </ul>
                                </div>
                            </div><!--end col-->
                        </div>
                    </div>
                    <div class="card-body mb-n3">
                        <div class="row">
                            @foreach ($deals as $deal)
                            <div class="col-lg-3">
                                <div class="card deal-card">
                                    <div class="card-body">
                                        <div class="media mb-3">
                                            <img src="{{ asset('assets/images/users/male.png')}}"
                                                class="me-3 thumb-lg align-self-center rounded-circle"
                                                alt="...">
                                            <div class="media-body align-self-center">
                                                <h4 class="mt-0 mb-0 font-16">{{ optional($deal->lead)->name }}
                                                    {!! getstatusss(optional($deal->dealStatus)->name) !!}
                                                </h4>
                                                <p class="text-muted mb-0 font-12">
                                                    {{ optional($deal->lead)->address }}
                                                </p>
                                            </div>
                                        </div> 
                                        <ul class="list-unstyled mb-2">
                                            <li class=""><i data-feather="calendar"
                                                    class="align-self-center icon-xs icon-dual me-1"></i> <b>Created
                                                    on</b> : {{ formatDate($deal->created_at) }}</li>
                                            <li class="mt-2"><i data-feather="phone"
                                                    class="align-self-center icon-xs icon-dual me-1"></i> <b> phone
                                                </b> : {{ optional($deal->lead)->phone }}</li>
                                            <li class="mt-2"><i data-feather="mail"
                                                    class="align-self-center icon-xs icon-dual me-1"></i> <b> Email
                                                </b> : {{ optional($deal->lead)->email }}</li>
                                                <li class=""><i data-feather="calendar"
                                                    class="align-self-center icon-xs icon-dual me-1"></i> <b>Closing Date
                                                    </b> : {{ formatDate($deal->closing_date) }}</li>
                                                    <li class=""><i data-feather="calendar"
                                                    class="align-self-center icon-xs icon-dual me-1"></i> <b>Closed By
                                                    </b> : {{ $deal->closedBy->name }}</li>
                                        </ul>
                                        <p class="text-muted  mt-3">
                                            <span class="text-dark font-weight-semibold">Closing Notes :</span>
                                            {{ $deal->lead->notes }}
                                        </p>
                                        <!-- <div>
                                            <button type="button" class="btn btn-sm btn-de-primary">Send
                                                SMS</button>
                                            <button type="button" class="btn btn-sm btn-de-primary">Send
                                                Email</button>
                                        </div> -->
                                    </div><!--end card-body-->
                                </div> <!--end card-->
                            </div>
                            @endforeach

                            {{ $deals->links(data: ['scrollTo' => false]) }}
                        </div><!--end row-->
                    </div>
                </div>
            </div>

        </div><!-- container -->


        <!--end Rightbar-->

        <!-- Footer Start -->
        <livewire:layout.footer />
        <!-- end Footer -->

    </div>
    <!-- end page content -->
</div>
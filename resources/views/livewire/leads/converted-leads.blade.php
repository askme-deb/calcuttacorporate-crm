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
                                <li class="breadcrumb-item">
                                    <a wire:navigate href="{{ route('dashboard') }}">Dashboard</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item"><a href="{{route('leads')}}" wire:navigate>Leads</a>
                                </li>
                                <li class="breadcrumb-item active">Converted Leads</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Converted Leads</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-6">
                          
                                   
                                </div>
                                <div class="col-lg-6 mt-3 text-end">
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
                            <div class="table-responsive">

                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Name</th>
                                            <th>Source</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            {{-- <th>Address</th> --}}
                                            <!-- <th>Notes</th> -->
                                            <th>Dated</th>
                                            <th>Converted On</th>
                                        </tr><!--end tr-->
                                    </thead>

                                    <tbody>
                                        @php
                                        $i = 1;
                                        @endphp
                                        @foreach ($leads as $lead)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td><a wire:navigate
                                                    href="{{ route('lead.details', ['id' => Crypt::encryptString($lead->id)]) }}" class="text-primary">{{ $lead->name }}</a>
                                            </td>
                                            <td> {!! getstatusss(optional($lead->leadSource)->name) !!}</td>
                                            <td>{{ $lead->phone }}</td>
                                            <td>{!! getstatusss(optional($lead->leadStatus)->name) !!}
                                            </td>
                                            {{-- <td>{{ $lead->address}}</td> --}}
                                            <!-- <td>{{ $lead->notes }}</td> -->
                                            <td>{{ $lead->created_at }}</td>
                                            <td>{{ $lead->updated_at ? formatDate($lead->updated_at) : '' }}
                                            </td>
                                        </tr><!--end tr-->
                                        @endforeach
                                    </tbody>
                                </table>

                                {{ $leads->links(data: ['scrollTo' => false]) }}
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->

        </div><!-- container -->

        <!--Start Rightbar-->


        <!--Start Footer-->

        <!-- Footer Start -->
        <livewire:layout.footer />
      
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>

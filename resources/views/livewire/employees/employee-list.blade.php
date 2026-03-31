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
                                <li class="breadcrumb-item"> <a wire:navigate href="{{ route('dashboard')}}">Dashboard</a></li>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">Employee</li>
                            </ol>
                        </div>
                        <h4 class="page-title">All Employee</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body mb-n3">
                            @can('Create Employee')
                            <a href="{{ route('create-employee') }}" wire:navigate class="btn btn-outline-primary btn-sm px-4 mt-0 mb-3" >Add New <i class="fas fa-plus"></i></a>
                            @endcan
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Sl No.</th>
                                        <th>Employee Code</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Email</th>
                                        <th>Contact No</th>
                                        <th>Date of Joining</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($employees as $index => $emp)
                                            <tr>
                                                <td>{{ $index +1}}</td>
                                                <td>{{ $emp->emp_code }}</td>
                                                <td><img src="{{ empProfilePicture($emp->user_id) }}" alt="" class="thumb-sm rounded-circle me-2">{{ $emp->emp_first_name }} {{ $emp->emp_last_name }}</td>
                                                <td>{{ optional($emp->designation)->name}}</td>
                                                <td>{{ $emp->user->email }}</td>
                                                <td>{{ $emp->emp_contact_no }}</td>
                                                <td>{{ $emp->emp_date_of_joining }}</td>

                                                <td>
                                                    <a href="{{ route('edit-employee', ['id' => Crypt::encryptString($emp->id)]) }}" wire:navigate><i class="las la-pen text-secondary font-16 text-info"></i></a>
                                                    <a href="javascript:;" onclick="confirmDeletion({{ $emp->id }})"><i class="las la-trash-alt text-secondary font-16 text-danger"></i></a>
                                                </td>
                                            </tr><!--end tr-->
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- {{ $employees->links() }}                      --}}
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->

        </div><!-- container -->


        <!-- Footer Start -->
        <livewire:layout.footer />
        <!-- end Footer -->
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>

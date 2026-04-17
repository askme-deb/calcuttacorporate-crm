<div class="leftbar-tab-menu">
    <div class="main-icon-menu">
        <a href="{{ route('dashboard') }}" wire:navigate class="logo logo-metrica d-block text-center">
            <span>
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
        </a>
        <div class="main-icon-menu-body">
            <div class="position-reletive h-100" data-simplebar style="overflow-x: hidden;">
                {{-- <a href="{{ route('dashboard') }}" wire:click class="nav-link">
                <i class="ti ti-smart-home menu-icon"></i>
                </a> --}}
                <ul class="nav nav-tabs" role="tablist" id="tab-menu">
                    {{-- <li class="nav-item" data-bs-toggle="tooltip" title="Dashboard">
                        <a href="{{ route('dashboard') }}" wire:click class="nav-link">
                    <i class="ti ti-smart-home menu-icon"></i>
                    </a>
                    </li> --}}
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Human Resources"
                        data-bs-trigger="hover">
                        <a href="#codHr" id="hr-tab" class="nav-link">
                            <i class="ti ti-users menu-icon"></i>
                        </a>
                    </li>
                    @canany(['Manage Accounts'])
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Finance"
                        data-bs-trigger="hover">
                        <a href="#codFinance" id="fin-tab" class="nav-link">
                            <i class="ti ti-currency-rupee  menu-icon"></i>
                        </a>
                    </li>
                    @endcanany
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Projects"
                        data-bs-trigger="hover">
                        <a href="#codProjects" id="project-tab" class="nav-link">
                            <i class="ti ti-clipboard-list  menu-icon"></i>
                        </a>
                    </li>
                    @canany(['Manage Settings'])
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Settings"
                        data-bs-trigger="hover">
                        <a href="#codSettings" id="settings-tab" class="nav-link">
                            <i class="ti ti-settings  menu-icon"></i>
                        </a>
                    </li>
                    @endcanany
                </ul>
            </div>
        </div>
        <div class="pro-metrica-end">
            <a href="{{ route('profile') }}" class="profile">
                <img src="{{ empProfilePicture(auth()->id()) }}" alt="profile-user" class="rounded-circle thumb-sm">
            </a>
        </div>
    </div>

    <div class="main-menu-inner">
        <div class="topbar-left">
            <a href="{{ route('dashboard') }}" class="logo">
                <span>
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-large" class="logo-lg logo-light">
                </span>
            </a>
        </div>
        <div class="menu-body navbar-vertical tab-content" data-simplebar wire:ignore>
            <div id="codHr"
                class="main-icon-menu-pane tab-pane {{ request()->routeIs('employee.*', 'edit-employee', 'attendance.*') ? 'active show' : '' }}"
                role="tabpanel" aria-labelledby="hr-tab">
                <div class="title-box">
                    <h6 class="menu-title">Human Resources</h6>
                </div>
                <div class="collapse navbar-collapse" id="sidebarCollapse">
                    <ul class="navbar-nav">
                        @canany(['View Employee', 'Create Employee'])
                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarEmployee" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarEmployee">
                                Employees
                            </a>
                            <div class="collapse {{ request()->routeIs('employee.*', 'edit-employee') ? 'show' : '' }}"
                                id="sidebarEmployee">
                                <ul class="nav flex-column">
                                    @can('Create Employee')
                                    <li class="nav-item">
                                        <a wire:navigate href="{{ route('create-employee') }}" class="nav-link">Add New
                                            Employee</a>
                                    </li>
                                    @endcan
                                    @can('View Employee')
                                    <li class="nav-item">
                                        <a wire:navigate href="{{ route('employee') }}"
                                            class="nav-link {{ request()->routeIs('employee.*', 'edit-employee') ? 'active' : '' }}">Employee
                                            List</a>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                        @endcanany
                        @canany(['Create Attendance', 'View Attendance', 'Edit Attendance', 'Delete Attendance'])
                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarAttendance" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarAttendance">
                                Attendance
                            </a>
                            <div class="collapse " id="sidebarAttendance">
                                <ul class="nav flex-column">
                                    @can('Create Attendance')
                                    <!-- <li class="nav-item">
                                                                        <a class="nav-link" href="crypto-exchange.html">Add</a>
                                                                    </li> -->
                                    @endcan

                                    <!-- <li class="nav-item">
                                                        <a class="nav-link" href="#">Today's Logs</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#">Search Attendance</a>
                                                    </li>-->
                                    @can('Create Attendance')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('attendance.log') }}"
                                            wire:navigate>Attendance Log</a>
                                    </li>
                                    @endcan

                                    @can('View Attendance')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('attendance') }}" wire:navigate>Attendance
                                            Sheet</a>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                        @endcanany

                        @canany(['Create Leave', 'View Leave', 'Edit Leave', 'Delete Leave'])
                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarLeave" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarLeave">
                                Leave
                            </a>
                            <div class="collapse " id="sidebarLeave">
                                <ul class="nav flex-column">
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" href="crm-contacts.html">Weekly Holiday</a>
                                    </li> --}}
                                    @canany(['Create Holiday', 'View Holiday', 'Edit Holiday', 'Delete Holiday'])
                                    <li class="nav-item">
                                        <a class="nav-link" wire:navigate href="{{ route('holiday') }} ">Holidays</a>
                                    </li>
                                    @endcanany
                                    @canany([
                                    'Create Leave Type',
                                    'View Leave Type',
                                    'Edit Leave Type',
                                    'Delete Leave
                                    Type',
                                    ])
                                    <li class="nav-item">
                                        <a class="nav-link" wire:navigate href="{{ route('leaveType') }}">Leave
                                            Type</a>
                                    </li>
                                    @endcanany
                                    @canany(['Create Leave', 'View Leave', 'Edit Leave', 'Delete Leave'])
                                    <li class="nav-item">
                                        <a class="nav-link" wire:navigate href="{{ route('leave') }}">Leave
                                            Application</a>
                                    </li>
                                    @endcanany
                                </ul>
                            </div>
                        </li>
                        @endcanany


                        <li class="nav-item">
                            <a class="nav-link  {{ request()->routeIs('employee.resignation.submit', 'employee.resignation.status') ? 'active' : '' }}"
                                wire:navigate href="{{ route('employee.resignation.submit') }}">Resignation</a>
                        </li>

                        @canany(['Approve Resignation', 'Reject Resignation', 'Exit Checklisk', 'Approve Termination',
                        'Reject Termination','Device Information'])
                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarCoreHr" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarCoreHr">
                                Core HR
                            </a>
                            <div class="collapse " id="sidebarCoreHr">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('awards.index') }}">Awards</a>
                                    </li>
                                    @canany(['Approve Resignation', 'Reject Resignation', 'Exit Checklisk'])
                                    <li class="nav-item">
                                        <a class="nav-link" wire:navigate
                                            href="{{ route('hr.resignations') }} ">Resignations</a>
                                    </li>
                                    @endcanany
                                    @canany(['Approve Termination', 'Reject Termination'])
                                    <li class="nav-item">
                                        <a class="nav-link" wire:navigate
                                            href="{{ route('hr.termination') }}">Terminations</a>
                                    </li>
                                    @endcanany
                                    <li class="nav-item">
                                        <a href="#salaryManagement"
                                            class="nav-link"
                                            data-bs-toggle="collapse"
                                            role="button"
                                            aria-expanded="false"
                                            aria-controls="salaryManagement">
                                            Payroll Management
                                        </a>
                                        <div class="collapse" id="salaryManagement">
                                            <ul class="nav flex-column ms-3">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('salary.dashboard') }}" wire:navigate>
                                                        Dashboard
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('salary.setup') }}" wire:navigate>
                                                        Salary Setup
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('salary.payroll') }}" wire:navigate>
                                                        Payroll
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('salary.history') }}" wire:navigate>
                                                        Salary History
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @canany(['Device Information', 'Deregister Device'])
                                    <li class="nav-item">
                                        <a class="nav-link" wire:navigate
                                            href="{{ route('deviceinfo') }}">Device info</a>
                                    </li>
                                    @endcanany
                                </ul>
                            </div>
                        </li>
                        @endcanany


                        <!-- <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Awards</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Travels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Resignations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Complaints</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Warnings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Terminations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Employees Exit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">Employees Last Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apps-chat.html">HR Calendar</a>
                        </li> -->
                    </ul>
                </div>
            </div>
            @canany(['Manage Accounts','Create Invoice','View Invoice','Edit Invoice','Delete Invoice'])
            <div id="codFinance" class="main-icon-menu-pane  tab-pane" role="tabpanel" aria-labelledby="fin-tab">
                <div class="title-box">
                    <h6 class="menu-title">Finance</h6>
                </div>
                <div class="collapse navbar-collapse" id="sidebarCollapse_2">
                    <ul class="navbar-nav">
                        <!-- <li class="nav-item">
                                <a class="nav-link" href="pages-gallery.html">Chart of Account</a>
                            </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bank.index') }}" wire:navigate>Banks</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarinvoice" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarinvoice">
                                Invoice
                            </a>
                            <div class="collapse " id="sidebarinvoice">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('invoices') }}" wire:navigate>All
                                            Invoice</a>
                                    </li>
                                    @can('Create Invoice')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('invoice.create') }}" wire:navigate>
                                            Create Gst Invoice</a>
                                    </li>


                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('create.invoice') }}" wire:navigate>
                                            Create Non Gst Invoice</a>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <!-- <a class="nav-link" href="#sidebarElements" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarElements">
                                    Account Reports
                                </a> -->
                            <!-- <div class="collapse " id="sidebarElements">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-alerts.html">General Ledger</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-avatar.html">Profit Loss</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-buttons.html">Cash Flow</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-badges.html">Employee Wise Report</a>
                                        </li>
                                    </ul>
                                </div> -->
                        </li>
                    </ul>
                </div>
            </div>
            @endcanany
            @canany(['Create Project', 'View Project', 'Edit Project', 'Delete Project', 'Asign Project', 'Create Lead',
            'Edit Lead', 'View Lead', 'Delete Lead', 'Create Deal', 'View Deal', 'Edit Deal', 'Delete Deal'])
            <div id="codProjects" wire:ignore
                class="main-icon-menu-pane tab-pane {{ request()->routeIs('leads.*', 'lead.details', 'project.*', 'worksheet', 'project.edit', 'tasks', 'client') ? 'active show' : '' }}"
                role="tabpanel" aria-labelledby="project-tab">
                <div class="title-box">
                    <h6 class="menu-title">Projects</h6>
                </div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>

                    @canany(['Create Lead', 'Edit Lead', 'View Lead', 'Delete Lead'])
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarLeads" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarLeads">
                            Leads Managenent
                        </a>
                        <div class="collapse {{ request()->routeIs('leads', 'lead.details', 'leads.converted') ? 'show' : '' }}"
                            id="sidebarLeads">
                            <ul class="nav flex-column">

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('leads') ? 'active' : '' }}"
                                        href="{{ route('leads') }}">All Leads</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('leads.converted') ? 'active' : '' }}"
                                        href="{{ route('leads.converted') }}" wire:navigate>Converted</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endcanany

                    @canany(['Create Deal', 'View Deal', 'Edit Deal', 'Delete Deal'])
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarDeals" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarDeals">
                            Deals Managenent
                        </a>
                        <div class="collapse {{ request()->routeIs('deal') ? 'show' : '' }}" id="sidebarDeals">
                            <ul class="nav flex-column">

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('deal') ? 'active' : '' }}"
                                        href="{{ route('deal') }}" wire:navigate>All Deals</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('deal.closed') ? 'active' : '' }}"
                                        href="{{ route('deal.closed') }}" wire:navigate>Closed Deal</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endcanany

                    @canany(['Create Project', 'View Project', 'Edit Project', 'Delete Project'])
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarProject" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProject">
                            Projects
                        </a>
                        <div class="collapse {{ request()->routeIs('project.create', 'worksheet', 'completedworksheet', 'project.edit', 'tasks') ? 'show' : '' }}"
                            id="sidebarProject">
                            <ul class="nav flex-column">
                                @can('Create Project')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('project.create') ? 'active' : '' }}"
                                        href="{{ route('project.create') }}" wire:navigate>Create New</a>
                                </li>
                                @endcan
                                @can('View Project')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('worksheet', 'project.edit', 'tasks') ? 'active' : '' }}"
                                        href="{{ route('worksheet') }}" wire:navigate>Worksheet</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('completedworksheet', 'tasks') ? 'active' : '' }}"
                                        href="{{ route('completedworksheet') }}" wire:navigate>Completed List</a>
                                </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                    @endcanany
                    @canany(['Create Log', 'View Log', 'Edit Log', 'Delete Log'])
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('logsheet') ? 'active' : '' }}"
                            href="{{ route('logsheet') }}">Logsheet</a>
                    </li>
                    @endcanany
                    @canany(['Create Client', 'View Client', 'Edit Client', 'Delete Client'])
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client') ? 'active' : '' }}"
                            href="{{ route('client') }}">Clients</a>
                    </li>
                    @endcanany
                    @canany(['Create Team', 'View Team', 'Edit Team', 'Delete Team'])
                    <li class="nav-item">
                        <a class="nav-link" href="pages-treeview.html">Team</a>
                    </li>
                    @endcanany
                </ul>
            </div>
            @endcanany

            <div id="codSettings" wire:ignore
                class="main-icon-menu-pane tab-pane
            {{ request()->routeIs('users.*', 'user.edit', 'role.*', 'permissions.*', 'permissions.asign') ? 'active show' : '' }}"
                role="tabpanel" aria-labelledby="settings-tab">
                <div class="title-box">
                    <h6 class="menu-title">Settings</h6>
                </div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarSettings">
                            Users
                        </a>
                        <div class="collapse {{ request()->routeIs('users.*', 'user.edit') ? 'show' : '' }}"
                            id="sidebarSettings">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('users.*', 'user.edit') ? 'active' : '' }}"
                                        href="{{ route('users') }}" wire:navigate>Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('createUser') }}" wire:navigate>Add New</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarProject" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProject">
                            Roles & Permissions
                        </a>
                        <div class="collapse {{ request()->routeIs('permissions.*', 'permissions.asign') ? 'show' : '' }}"
                            id="sidebarProject">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link " href="{{ route('role') }}" wire:navigate>Roles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('permissions.*', 'permissions.asign') ? 'active' : '' }}"
                                        href="{{ route('permissions') }}">Permissions</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarMasterData" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarMasterData">
                            Manage Master Data
                        </a>
                        <div class="collapse " id="sidebarMasterData">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#sidebarLeadMaster " class="nav-link" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebarLeadMaster">
                                        Leads
                                    </a>
                                    <div class="collapse " id="sidebarLeadMaster">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('leadPriority') }}"
                                                    wire:navigate>Lead Priority</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('leadSector') }}"
                                                    wire:navigate>Lead Sector</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('leadSource') }}"
                                                    wire:navigate>Lead Source</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('leadStatus') }}"
                                                    wire:navigate>Lead Status</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="#sidebarWorkListMaster " class="nav-link" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebarWorkListMaster">
                                        Work Lists
                                    </a>
                                    <div class="collapse " id="sidebarWorkListMaster">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('worklist') }}" wire:navigate>Work
                                                    List</a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="#sidebarEmployeeMaster" class="nav-link" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebarEmployeeMaster">
                                        Employee
                                    </a>
                                    <div class="collapse" id="sidebarEmployeeMaster">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" wire:navigate
                                                    href="{{ route('designation') }}">Designation</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" wire:navigate
                                                    href="{{ route('appellations') }}">Appellation</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" wire:navigate
                                                    href="{{ route('gender') }}">Genders</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" wire:navigate
                                                    href="{{ route('emptype') }}">Employee Types</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" wire:navigate
                                                    href="{{ route('institute') }}">Institutes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" wire:navigate
                                                    href="{{ route('org') }}">Appointed Organisation</a>
                                            </li>
                                        </ul><!--end nav-->
                                    </div><!--end sidebarEmployee-->
                                </li>

                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
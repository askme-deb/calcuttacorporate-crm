<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PayslipController;
use App\Livewire\Accounts\Banks\BankAccountManagement;
use App\Livewire\Actions\LockScreen as ActionsLockScreen;
use App\Livewire\Attendance\AttendanceList;
use App\Livewire\Attendance\AttendanceManagement;
use App\Livewire\Clients\ClientManagement;
use Illuminate\Support\Facades\Route;


use App\Livewire\Counter;
use App\Livewire\Dashboard;
use App\Livewire\Deals\ClosedDeals;
use App\Livewire\Deals\DealsManagement;
use App\Livewire\Deals\Master\Dealstatus;
use App\Livewire\Documents\DocumentWorkMapping;
use App\Livewire\Documents\Listofdocument;
use App\Livewire\Employees\AwardManager;
use App\Livewire\Employees\EmployeeCreate;
use App\Livewire\Employees\EmployeeEdit;
use App\Livewire\Employees\EmployeeList;
use App\Livewire\Employees\ExitChecklist;
use App\Livewire\Employees\Master\Appellations;
use App\Livewire\Employees\Master\AppointedOrganisation;
use App\Livewire\Employees\Master\Designations;
use App\Livewire\Employees\Master\EmployeeType;
use App\Livewire\Employees\Master\Gender;
use App\Livewire\Employees\Master\Institute;
use App\Livewire\Employees\Promotions;
use App\Livewire\Employees\ResignationManagement;
use App\Livewire\Employees\ResignationStatus;
use App\Livewire\Employees\SubmitResignation;
use App\Livewire\Employees\TerminationModule;
use App\Livewire\General\Logsheet;
use App\Livewire\Hr\DeviceInfo;
use App\Livewire\Hr\SalaryManagement\PayrollManagement;
use App\Livewire\Hr\SalaryManagement\SalaryDashboard;
use App\Livewire\Hr\SalaryManagement\SalaryHistory;
use App\Livewire\Hr\SalaryManagement\SalarySetup;
use App\Livewire\ImageUploadComponent;
use App\Livewire\Leads\ConvertedLeads;
use App\Livewire\Leads\CreateLead;
use App\Livewire\Leads\EditLead;
use App\Livewire\Leads\LeadDetails;
use App\Livewire\Leads\LeadsManagement;
use App\Livewire\Leads\Master\LeadPriority;
use App\Livewire\Leads\Master\LeadSector;
use App\Livewire\Leads\Master\LeadSource;
use App\Livewire\Leads\Master\LeadStatus;
use App\Livewire\Leave\Holidays;
use App\Livewire\Leave\LeaveManagement;
use App\Livewire\Leave\LeaveTypes;
use App\Livewire\LockScreen;
use App\Livewire\NotificationManagement\NotificationList;
use App\Livewire\Profile\Profile;
use App\Livewire\Projects\ProjectCreate;
use App\Livewire\Projects\ProjectManagement;
use App\Livewire\Projects\ProjectUpdate;
use App\Livewire\Projects\WorksheetsManagement;
use App\Livewire\RolesPermission\AsignPermission;
use App\Livewire\RolesPermission\Permissions;
use App\Livewire\RolesPermission\RoleManagement;
use App\Livewire\Tasks\TaskManagement;
use App\Livewire\Users\CreateUser;
use App\Livewire\Users\EditUser;
use App\Livewire\Users\Usermanagement;
use App\Livewire\WorksSheet\Master\WorkListMaster;
use App\Livewire\Projects\CompletedWorksheetList;
use App\Livewire\Projects\Dashboard as ProjectsDashboard;
use App\Livewire\Projects\ProjectDetails;
use App\Livewire\Reports\WorksheetReport;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Artisan;
use Livewire\Volt\Volt;
use App\Livewire\Hr\Dashboard as HrDashboard;
use App\Livewire\Sales\Invoice;
use App\Livewire\Sales\InvoiceEdit;
use App\Livewire\Sales\InvoiceList;
use App\Livewire\Sales\InvoiceNonGst;
use App\Livewire\Sales\InvoiceNonGstEdit;
use App\Http\Controllers\ProposalController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // Project status-wise pages
    Route::get('/projects/status/{status}', \App\Livewire\Projects\ProjectStatusList::class)->name('project.status');

     //Route::get('/appellations', Appellations::class)->name('appellations');
    Route::get('/appellations', Appellations::class)->name('appellations');
    Route::get('/genders', Gender::class)->name('gender');
    Route::get('/employee-types', EmployeeType::class)->name('emptype');
    Route::get('/institutes', Institute::class)->name('institute');
    Route::get('/appointed-organisations', AppointedOrganisation::class)->name('org');
    Route::get('/designations', Designations::class)->name('designation');

    Route::get('/employee', EmployeeList::class)->name('employee');
    Route::get('/employee/create', EmployeeCreate::class)->name('create-employee');
    Route::get('/employee/{id}/edit', EmployeeEdit::class)->name('edit-employee');

    Route::get('/roles', RoleManagement::class)->name('role');
    Route::get('/permissions', Permissions::class)->name('permissions');
    Route::get('permissions/{roleId}/asign-permissions', AsignPermission::class)->name('permissions.asign');

    Route::get('/lock-screen', ActionsLockScreen::class)->name('lock-screen');

    Route::get('/users', Usermanagement::class)->name('users');
    Route::get('/users/create' , CreateUser::class)->name('createUser');
    Route::get('/users/{id}/edit', EditUser::class)->name('user.edit');

    Route::get('/leads', LeadsManagement::class)->name('leads');
    Route::get('/leads/converted', ConvertedLeads::class)->name('leads.converted');
    Route::get('/leads/pipeline', \App\Livewire\Leads\LeadPipelineBoard::class)->name('leads.pipeline');
    Route::get('/leads/{id}', LeadDetails::class)->name('lead.details');
    Route::get('/lead-priority', LeadPriority::class)->name('leadPriority');
    Route::get('/lead-sector', LeadSector::class)->name('leadSector');
    Route::get('/lead-source', LeadSource::class)->name('leadSource');
    Route::get('/lead-status', LeadStatus::class)->name('leadStatus');
    Route::get('/deal-status', Dealstatus::class)->name('dealStatus');

    Route::get('/deals', DealsManagement::class)->name('deal');
    Route::get('/deals/closed-deals', ClosedDeals::class)->name('deal.closed');

    Route::get('/worklist', WorkListMaster::class)->name('worklist');
    Route::get('/list-of-documents', Listofdocument::class)->name('documentlist');
    Route::get('/list-of-documents-map', DocumentWorkMapping::class)->name('documentlistmap');

    Route::get('/leave-types', LeaveTypes::class)->name('leaveType');
    Route::get('/leave', LeaveManagement::class)->name('leave');
    Route::get('/holidays', Holidays::class)->name('holiday');
    Route::get('/attendance', AttendanceList::class)->name('attendance');
    Route::get('/attendance-log', AttendanceManagement::class)->name('attendance.log');
    Route::get('/projects', ProjectManagement::class)->name('projects');
    Route::get('/projects/create', ProjectCreate::class)->name('project.create');
    Route::get('/worksheet/{id}/edit', ProjectUpdate::class)->name('project.edit');
    Route::get('/worksheet', WorksheetsManagement::class)->name('worksheet');
    Route::get('/completed-worksheets', CompletedWorksheetList::class)->name('completedworksheet');
    // Route::get('/tasks', TaskManagement::class)->name('task');
    Route::get('/tasks/{id}', TaskManagement::class)->name('tasks');
    Route::get('/project-details/{id}', ProjectDetails::class)->name('project.details');
    Route::get('/clients', ClientManagement::class)->name('client');
    Route::get('/profile', Profile::class)->name('profile');

    Route::get('/logsheet', Logsheet::class)->name('logsheet');



    //Reports
    Route::get('/work-report', WorksheetReport::class)->name('workreport');

    Route::get('project/dashdoard', ProjectsDashboard::class)->name('project.dashboard');

    Route::get('/imageupload', ImageUploadComponent::class);



     Route::get('/notifications', NotificationList::class)->name('notification');
});

    Route::get('/bank-accounts', BankAccountManagement::class)->name('bank.index');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

// Route::middleware('api')->group(function () {
//     Route::apiResource('users', AuthenticationController::class);
// });

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/resignation', SubmitResignation::class)->name('resignation.submit');
        Route::get('/resignation/status', ResignationStatus::class)->name('resignation.status');
    });

    Route::get('/hr/resignations', ResignationManagement::class)->name('hr.resignations');
    Route::get('/hr/resignation/{resignation}/exit-checklist', ExitChecklist::class)->name('hr.exit-checklist');
    Route::get('/hr/termination', TerminationModule::class)->name('hr.termination');
    Route::get('/hr/awards', AwardManager::class)->name('awards.index');
    Route::get('/hr/promotions', Promotions::class)->name('promotion');
    Route::prefix('hr/salary')->group(function () {
    Route::get('/setup', SalarySetup::class)->name('salary.setup');
    Route::get('/payroll', PayrollManagement::class)->name('salary.payroll');
    Route::get('/history', SalaryHistory::class)->name('salary.history');
    Route::get('/dashboard', SalaryDashboard::class)->name('salary.dashboard');




    // Payslip routes
    Route::get('/payslip/{id}/download', [PayslipController::class, 'download'])->name('payslip.download');
    Route::get('/payslip/{id}/preview', [PayslipController::class, 'preview'])->name('payslip.preview');
    Route::get('/payslip/{id}/email', [PayslipController::class, 'email'])->name('payslip.email');
    });


Route::get('/hr/device-info', DeviceInfo::class)->name('deviceinfo');
Route::get('/hr/dashboard', HrDashboard::class)->name('hr.dashboard');


    //gst
    Route::get('/gst/invoice/create', Invoice::class)->name('invoice.create');
    Route::get('/gst/invoice/edit/{id}', InvoiceEdit::class)->name('invoice.edit');
    Route::get('/invoices', InvoiceList::class)->name('invoices');
    Route::get('/gst/invoice/print/{id}', [InvoiceController::class, 'gstPrint'])->name('gst-invoice.print');

    //non gst
    Route::get('/invoice/create', InvoiceNonGst::class)->name('create.invoice');
    Route::get('/invoice/print/{id}', [InvoiceController::class, 'nonGstprint'])->name('non-gst-invoice.print');
    Route::get('/invoice/edit/{id}', InvoiceNonGstEdit::class)->name('non-gst-invoice.edit');

    Route::get('proposals/{proposal}/preview', [ProposalController::class, 'preview'])->name('proposals.preview');
    Route::get('proposals/{proposal}/download', [ProposalController::class, 'download'])->name('proposals.download');
    Route::get('proposals/{proposal}/email', [ProposalController::class, 'email'])->name('proposals.email');


Route::get('/clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'All caches cleared!';
});





Route::get('/test-view', function () {
    return view('invoices.non-gstprint-preview');
});




Route::get('/create-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link has been created successfully!';
});

require __DIR__.'/auth.php';



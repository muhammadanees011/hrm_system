<?php

use App\Http\Controllers\AccountListController;
use App\Http\Controllers\AiTemplateController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\AllowanceOptionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AppraisalController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttendanceEmployeeController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\AwardTypeController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HolidayPlannerController;
use App\Http\Controllers\BonusRequestController;
use App\Http\Controllers\LeaveEntitlementController;
use App\Http\Controllers\AdvanceSalaryRequestController;
use App\Http\Controllers\DisciplinaryWarningController;
use App\Http\Controllers\LeaveEncashmentRequestController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\CommissionRequestController;
use App\Http\Controllers\AllowanceRequestController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CarryOverController;
use App\Http\Controllers\CaseCategoryController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CaseDiscussionController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CompanyPolicyController;
use App\Http\Controllers\CompetenciesController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomQuestionController;
use App\Http\Controllers\DeductionOptionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DucumentUploadController;
use App\Http\Controllers\EclaimController;
use App\Http\Controllers\EclaimTypeController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExitProcedureController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\GPNoteController;
use App\Http\Controllers\GoalTrackingController;
use App\Http\Controllers\HealthAssessmentController;
use App\Http\Controllers\HolidayCarryOverController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomeTypeController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\InterviewScheduleController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobStageController;
use App\Http\Controllers\JobWordCountController;
use App\Http\Controllers\LandingPageSectionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveSummaryController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanOptionController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationTemplatesController;
use App\Http\Controllers\OtherPaymentController;
use App\Http\Controllers\OverTimePolicyController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PaySlipController;
use App\Http\Controllers\PayeesController;
use App\Http\Controllers\PayerController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\PayslipTypeController;
use App\Http\Controllers\PensionOptInController;
use App\Http\Controllers\PensionOptoutController;
use App\Http\Controllers\PensionSchemeController;
use App\Http\Controllers\PerformanceTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonalizedOnboardingController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ProvidentFundsPolicyController;
use App\Http\Controllers\QuestionTemplateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResignationController;
use App\Http\Controllers\RetirementController;
use App\Http\Controllers\RetirementTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaturationDeductionController;
use App\Http\Controllers\SelfCertificationController;
use App\Http\Controllers\SetSalaryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaxRuleController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TerminationController;
use App\Http\Controllers\TerminationTypeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingTypeController;
use App\Http\Controllers\TransferBalanceController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WarningController;
use App\Http\Controllers\ZoomMeetingController;
use App\Http\Controllers\EmployementCheckController;
use App\Http\Controllers\EmployementCheckTypeController;
use App\Http\Controllers\JobTemplateController;
use App\Http\Controllers\FlexiTimeController;
use App\Http\Controllers\PerformanceCycleController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TrainingEventController;
use App\Http\Controllers\TrainingEventRequestController;
use App\Http\Controllers\WorkforcePlanningController;
use App\Http\Controllers\GoalResultController;
use App\Http\Controllers\EmployeeReviewController;
use App\Http\Controllers\HolidayConfigurationController;
use App\Http\Controllers\ReviewQuestionController;
use App\Http\Controllers\MeetingTemplateController;
use App\Http\Controllers\MeetingTemplatePointController;
use App\Http\Controllers\DocumentDirectoryController;
use App\Http\Controllers\CompensationReviewController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Models\Employee;
use App\Models\JobTemplate;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    // return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard.dashboard');
// })->middleware(['auth'])->name('dashboard');


require __DIR__ . '/auth.php';

Route::get('/check', [HomeController::class, 'check'])->middleware(
    [
        'auth',
        'XSS',
    ]
);
// Route::get('/password/resets/{lang?}', 'Auth\LoginController@showLinkRequestForm')->name('change.langPass');

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware(['XSS']);

Route::get('career/{id}/{lang}', [JobController::class, 'career'])->name('career');

Route::get('job/requirement/{code}/{lang}', [JobController::class, 'jobRequirement'])->name('job.requirement');
Route::get('job/apply/{code}/{lang}', [JobController::class, 'jobApply'])->name('job.apply');
Route::post('job/apply/data/{code}', [JobController::class, 'jobApplyData'])->name('job.apply.data');

// Onboarding personalized template
Route::get('personalized-onboarding/{id}/{jobApplicationId?}', [PersonalizedOnboardingController::class, 'show'])->name('onboarding.personalized.show');
Route::post('personalized-onboarding/response/store', [PersonalizedOnboardingController::class, 'responseStore'])->name('onboarding.personalized.response.store');
Route::get('employee-onboarding/response/{jobApplicationId}', [PersonalizedOnboardingController::class, 'responseShow'])->name('onboarding.personalized.response.show');

// cookie consent
Route::any('/cookie-consent', [SettingsController::class, 'CookieConsent'])->name('cookie-consent');

Route::group(['middleware' => ['verified']], function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'XSS'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('/home/getlanguvage', [HomeController::class, 'getlanguvage'])->name('home.getlanguvage');

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS',
            ],
        ],
        function () {

            Route::resource('settings', SettingsController::class);
            Route::post('email-settings', [SettingsController::class, 'saveEmailSettings'])->name('email.settings');
            Route::post('company-settings', [SettingsController::class, 'saveCompanySettings'])->name('company.settings');
            Route::post('system-settings', [SettingsController::class, 'saveSystemSettings'])->name('system.settings');
            Route::get('company-setting', [SettingsController::class, 'companyIndex'])->name('company.setting');
            Route::post('company-email-setting/{name?}', [EmailTemplateController::class, 'updateStatus'])->name('company.email.setting');
            // Route::post('company-email-setting/{name}', 'EmailTemplateController@updateStatus')->name('status.email.language')->middleware(['auth']);

            Route::post('pusher-settings', [SettingsController::class, 'savePusherSettings'])->name('pusher.settings');
            Route::post('business-setting', [SettingsController::class, 'saveBusinessSettings'])->name('business.setting');

            Route::post('zoom-settings', [SettingsController::class, 'zoomSetting'])->name('zoom.settings');

            Route::get('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
            Route::post('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
            Route::post('test-mail/send', [SettingsController::class, 'testSendMail'])->name('test.send.mail');

            Route::get('create/ip', [SettingsController::class, 'createIp'])->name('create.ip');
            Route::post('create/ip', [SettingsController::class, 'storeIp'])->name('store.ip');
            Route::get('edit/ip/{id}', [SettingsController::class, 'editIp'])->name('edit.ip');
            Route::post('edit/ip/{id}', [SettingsController::class, 'updateIp'])->name('update.ip');
            Route::delete('destroy/ip/{id}', [SettingsController::class, 'destroyIp'])->name('destroy.ip');

            Route::get('create/webhook', [SettingsController::class, 'createWebhook'])->name('create.webhook');
            Route::post('create/webhook', [SettingsController::class, 'storeWebhook'])->name('store.webhook');
            Route::get('edit/webhook/{id}', [SettingsController::class, 'editWebhook'])->name('edit.webhook');
            Route::post('edit/webhook/{id}', [SettingsController::class, 'updateWebhook'])->name('update.webhook');
            Route::delete('destroy/webhook/{id}', [SettingsController::class, 'destroyWebhook'])->name('destroy.webhook');
        }
    );

    // Email Templates
    Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language')->middleware(['auth', 'XSS']);
    Route::post('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language')->middleware(['auth']);
    Route::post('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language')->middleware(['auth']);

    Route::resource('email_template', EmailTemplateController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('email_template_lang', EmailTemplateLangController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get(
        '/test',

        [SettingsController::class, 'testEmail']
    )->name('test.email')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post(
        '/test/send',
        [SettingsController::class, 'testEmailSend']

    )->name('test.email.send')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    // End

    Route::resource('user', UserController::class)
        ->middleware(
            [
                'auth',
                'XSS',
            ]
        );
    Route::post('employee/json', [EmployeeController::class, 'json'])->name('employee.json')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('employee/tourdone', [EmployeeController::class, 'tourdone'])->name('employee.tourdone')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('employee/getdepartment', [EmployeeController::class, 'getdepartment'])->name('employee.getdepartment')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('branch/employee/json', [EmployeeController::class, 'employeeJson'])->name('branch.employee.json')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('employee-profile', [EmployeeController::class, 'profile'])->name('employee.profile')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('meet-team', [EmployeeController::class, 'meetTeam'])->name('employee.meetTeam')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('show-employee-profile/{id}', [EmployeeController::class, 'profileShow'])->name('show.employee.profile')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('lastlogin', [EmployeeController::class, 'lastLogin'])->name('lastlogin')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    // user log
    Route::get('userlogsView/{id}', [EmployeeController::class, 'view'])->name('userlog.view')->middleware(['auth', 'XSS']);

    Route::delete('lastlogin/{id}', [EmployeeController::class, 'logindestroy'])->name('employee.logindestroy')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('employee-probation', [EmployeeController::class, 'showEmployeeProbation'])->name('employee.probation.index')->middleware(['auth', 'XSS']);
    Route::put('employee-probation/update/{id}', [EmployeeController::class, 'EmployeeTypeUpdate'])->name('employee.probation.update')->middleware(['auth', 'XSS']);
    Route::get('personalFile/{id}', [EmployeeController::class, 'showPersonalFile'])->name('employee.personalFile')->middleware(['auth', 'XSS']);
    Route::post('personalFile/store', [EmployeeController::class, 'storePersonalFile'])->name('employee.storePersonalFile')->middleware(['auth', 'XSS']);


    Route::resource('employee', EmployeeController::class)->middleware(['auth','XSS',]);

    Route::resource('teams', TeamsController::class)->middleware(['auth','XSS',]);
    Route::get('/teams/{team}/members', [TeamsController::class, 'getMembers'])->name('teams.members');

    Route::post('employee/attendance_overview', [EmployeeController::class, 'attendance_overview'])->name('employee.attendance_overview')->middleware(['auth','XSS',]);
    

    Route::get('otherpayments/create/{eid}', [OtherPaymentController::class, 'otherpaymentCreate'])->name('otherpayments.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('otherpayment', OtherPaymentController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('paymenttype', PaymentTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('department', DepartmentController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('designation', DesignationController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('document', DocumentController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('/documentDirectory', [DocumentController::class, 'documentDirectory'])->name('document.directory')->middleware(['auth', 'XSS',]);

    Route::resource('privacy-policy', PrivacyPolicyController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
        );


    Route::get('/privacypolicy/modal', [PrivacyPolicyController::class, 'modal'])->name('privacy-policy.modal')->middleware(['auth', 'XSS',]);
    
    Route::post('/privacypolicy/accept', [PrivacyPolicyController::class, 'accept'])->name('privacy-policy.accept')->middleware(['auth', 'XSS',]);
    
    Route::resource('branch', BranchController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('awardtype', AwardTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('award', AwardController::class)->middleware(['auth','XSS']);
    Route::get('create_notes/award/{id?}', [AwardController::class,'createNotes']);
    Route::post('store_notes/award', [AwardController::class,'storeNotes']);
    Route::get('download/award/{id?}', [AwardController::class,'award_file'])->name('download.award_file');

    Route::get('termination/{id}/description', [TerminationController::class, 'description'])->name('termination.description');

    Route::resource('termination', TerminationController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('terminationtype', TerminationTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('announcement/getdepartment', [AnnouncementController::class, 'getdepartment'])->name('announcement.getdepartment')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('announcement/getemployee', [AnnouncementController::class, 'getemployee'])->name('announcement.getemployee')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('announcement', AnnouncementController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::get('holiday/calender', [HolidayController::class, 'calender'])->name('holiday.calender')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('holiday/update/status/{id}', [HolidayController::class, 'updateStatus'])->name('holiday.update.status')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('holiday', HolidayController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('holiday-carryover', HolidayCarryOverController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('holiday-carryover/update/status/{id}', [HolidayCarryOverController::class, 'updateStatus'])->name('holiday-carryover.update.status')->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::get('employee/salary/{eid}', [SetSalaryController::class, 'employeeBasicSalary'])->name('employee.basic.salary')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('allowances/create/{eid}', [AllowanceController::class, 'allowanceCreate'])->name('allowances.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('commissions/create/{eid}', [CommissionController::class, 'commissionCreate'])->name('commissions.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('loans/create/{eid}', [LoanController::class, 'loanCreate'])->name('loans.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('saturationdeductions/create/{eid}', [SaturationDeductionController::class, 'saturationdeductionCreate'])->name('saturationdeductions.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('overtimes/create/{eid}', [OvertimeController::class, 'overtimeCreate'])->name('overtimes.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    //payslip
    Route::resource('paysliptype', PayslipTypeController::class)->middleware(['auth', 'XSS',]);
    Route::resource('allowance', AllowanceController::class)->middleware(['auth', 'XSS',]);
    Route::resource('commission', CommissionController::class)->middleware(['auth', 'XSS',]);
    Route::resource('allowanceoption', AllowanceOptionController::class)->middleware(['auth', 'XSS',]);
    Route::resource('loanoption', LoanOptionController::class)->middleware(['auth', 'XSS',]);
    Route::resource('deductionoption', DeductionOptionController::class)->middleware(['auth', 'XSS',]);
    Route::resource('loan', LoanController::class)->middleware(['auth', 'XSS',]);
    Route::resource('saturationdeduction', SaturationDeductionController::class)->middleware(['auth', 'XSS',]);
    Route::resource('overtime', OvertimeController::class)->middleware(['auth', 'XSS',]);

    //payroll-setup
    Route::get('bonus/create/{id?}', [BonusController::class,'create'])->name('bonus.create')->middleware(['auth', 'XSS',]);
    Route::post('bonus/store', [BonusController::class,'store'])->name('bonus.store')->middleware(['auth', 'XSS',]);
    Route::get('bonus/edit/{id?}', [BonusController::class,'edit'])->name('bonus.edit')->middleware(['auth', 'XSS',]);
    Route::put('bonus/update/{id?}', [BonusController::class,'update'])->name('bonus.update')->middleware(['auth', 'XSS',]);
    Route::delete('bonus/delete/{id?}', [BonusController::class, 'destroy'])->name('bonus.delete')->middleware(['auth', 'XSS',]);

    //bonus request
    Route::resource('bonusrequest', BonusRequestController::class)->middleware(['auth', 'XSS',]);
    //overtime request
    Route::resource('overtimerequest', OvertimeRequestController::class)->middleware(['auth', 'XSS',]);
    //commission request
    Route::resource('commissionrequest', CommissionRequestController::class)->middleware(['auth', 'XSS',]);
   //allowance request
    Route::resource('allowancerequest', AllowanceRequestController::class)->middleware(['auth', 'XSS',]);
    //loan request
    Route::resource('loanrequest',LoanRequestController::class)->middleware(['auth', 'XSS',]);
    //adavnce salary request
    Route::resource('advancesalaryrequest',AdvanceSalaryRequestController::class)->middleware(['auth', 'XSS',]);
    //leave encashment request
    Route::resource('leaveencashmentrequest',LeaveEncashmentRequestController::class)->middleware(['auth', 'XSS',]);

    Route::resource('taxrules', TaxRuleController::class)->middleware(['auth', 'XSS',]);
    Route::resource('providentfundspolicy', ProvidentFundsPolicyController::class)->middleware(['auth', 'XSS',]);
    Route::resource('overtimepolicy', OverTimePolicyController::class)->middleware(['auth', 'XSS',]);

    //exits
    Route::resource('retirementtype', RetirementTypeController::class)->middleware(['auth', 'XSS',]);
    Route::resource('retirement', RetirementController::class)->middleware(['auth', 'XSS',]);
    Route::get('retirement/description/{id}', [RetirementController::class, 'description'])->name('retirement.description')->middleware(['auth', 'XSS',]);

    Route::resource('exitprocedure', ExitProcedureController::class)->middleware(['auth', 'XSS',]);

    //health and fitness
    Route::resource('healthassessment', HealthAssessmentController::class)->middleware(['auth', 'XSS',]);
    Route::post('healthassessment/result/{id}', [HealthAssessmentController::class, 'assessmentResultStore'])->name('healthassessment.result.store')->middleware(['auth', 'XSS',]);
    Route::post('/healthassessment/{id}/file', [HealthAssessmentController::class, 'fileUpload'])->name('healthassessment.file.upload')->middleware(['auth', 'XSS']);
    Route::get('/healthassessment/{id}/file/{fid}',  [HealthAssessmentController::class, 'fileDownload'])->name('healthassessment.file.download')->middleware(['auth', 'XSS']);
    Route::get('/healthassessment/{id}/file/delete/{fid}', [HealthAssessmentController::class, 'fileDelete'])->name('healthassessment.file.delete')->middleware(['auth', 'XSS']);

    Route::resource('gpnote', GPNoteController::class)->middleware(['auth', 'XSS',]);
    Route::post('gpnote/details/{id}', [GPNoteController::class, 'assessmentDetailsStore'])->name('gpnote.detail.store')->middleware(['auth', 'XSS',]);
    Route::post('/gpnote/{id}/file', [GPNoteController::class, 'fileUpload'])->name('gpnote.file.upload')->middleware(['auth', 'XSS']);
    Route::get('/gpnote/{id}/file/{fid}',  [GPNoteController::class, 'fileDownload'])->name('gpnote.file.download')->middleware(['auth', 'XSS']);
    Route::get('/gpnote/{id}/file/delete/{fid}', [GPNoteController::class, 'fileDelete'])->name('gpnote.file.delete')->middleware(['auth', 'XSS']);

    Route::resource('selfcertification', SelfCertificationController::class)->middleware(['auth', 'XSS',]);
    Route::post('selfcertification/details/{id}', [SelfCertificationController::class, 'detailsStore'])->name('selfcertification.detail.store')->middleware(['auth', 'XSS',]);
    Route::post('/selfcertification/{id}/file', [SelfCertificationController::class, 'fileUpload'])->name('selfcertification.file.upload')->middleware(['auth', 'XSS']);
    Route::get('/selfcertification/{id}/file/{fid}',  [SelfCertificationController::class, 'fileDownload'])->name('selfcertification.file.download')->middleware(['auth', 'XSS']);
    Route::get('/selfcertification/{id}/file/delete/{fid}', [SelfCertificationController::class, 'fileDelete'])->name('selfcertification.file.delete')->middleware(['auth', 'XSS']);

    //employement checks
    Route::resource('employementchecktype', EmployementCheckTypeController::class)->middleware(['auth', 'XSS',]);
    Route::resource('employementcheck', EmployementCheckController::class)->except(['edit', 'destroy', 'update', 'show'])->middleware(['auth', 'XSS',]);
    Route::get('employementcheck/{id}', [EmployementCheckController::class, 'deleteFile'])->name('employementcheck.delete')->middleware(['auth', 'XSS',]);
    Route::get('employementcheck/file/{filename}', [EmployementCheckController::class, 'viewFile'])->name('employementcheck.view.file')->middleware(['auth', 'XSS',]);
    Route::get('employementcheck/download/file/{filename}', [EmployementCheckController::class, 'downloadFile'])->name('employementcheck.download.file')->middleware(['auth', 'XSS',]);

    //document directories
    Route::resource('documentdirectories', DocumentDirectoryController::class)->middleware(['auth', 'XSS',]);
    Route::get('document/create/{dir_id?}', [DocumentDirectoryController::class,'createfile'])->name('create.document')->middleware(['auth', 'XSS',]);
    Route::post('document_dir_store', [DocumentDirectoryController::class,'storefile'])->name('store.document')->middleware(['auth', 'XSS',]);
    Route::get('view/document/{id?}', [DocumentDirectoryController::class,'viewFile'])->name('view.document_dir')->middleware(['auth', 'XSS',]);
    Route::get('delete/document/{file_id?}/{dir_id?}', [DocumentDirectoryController::class,'deletefile'])->name('delete.document_dir')->middleware(['auth', 'XSS',]);
    Route::get('download/document/{file_id?}/{dir_id?}', [DocumentDirectoryController::class,'downloadFile'])->name('download.document_dir')->middleware(['auth', 'XSS',]);

    //performance
    Route::resource('performancecycle', PerformanceCycleController::class)->middleware(['auth', 'XSS',]);
    Route::get('performancecycle/reviews/{id?}', [PerformanceCycleController::class, 'reviews'])->name('performancecycle.reviews')->middleware(['auth', 'XSS',]);

    Route::resource('compensationreview', CompensationReviewController::class)->middleware(['auth', 'XSS',]);
    Route::get('compensationreview/edit-rewiewee/{id?}', [CompensationReviewController::class,'editreviewee'])->name('compensationreview.edit-rewiewee')->middleware(['auth', 'XSS',]);
    Route::put('compensationreview/update-rewiewee/{id?}', [CompensationReviewController::class,'updatereviewee'])->name('compensationreview.update-rewiewee')->middleware(['auth', 'XSS',]);
    Route::get('compensationreview/edit-comments/{reviewee_id?}/{review_id?}', [CompensationReviewController::class,'editcomments'])->name('compensationreview.edit-comments')->middleware(['auth', 'XSS',]);
    Route::post('compensationreview/update-comments', [CompensationReviewController::class,'updatecomments'])->name('compensationreview.update-comments')->middleware(['auth', 'XSS',]);

    //manage leaves
    // Route::resource('leavesummary', LeaveSummaryController::class)->middleware(['auth','XSS',]);
    Route::get('leavesummary/create/{id}', [LeaveSummaryController::class, 'create'])->name('leavesummary.create')->middleware(['auth', 'XSS',]);
    Route::post('leavesummary/{id}', [LeaveSummaryController::class, 'store'])->name('leavesummary.store')->middleware(['auth', 'XSS',]);

    Route::get('/employees/leavesummary', [LeaveSummaryController::class, 'employees'])->name('leavesummary.employees')->middleware(['auth', 'XSS',]);
    Route::get('/employees/leavesummary/{id}', [LeaveSummaryController::class, 'employeeLeaveSummary'])->name('leavesummary.employee')->middleware(['auth', 'XSS',]);
    Route::get('/delete/leavesummary/{id}/{employee_id}', [LeaveSummaryController::class, 'destroy'])->name('leavesummary.destroy')->middleware(['auth', 'XSS',]);
    
    Route::resource('carryover', CarryOverController::class)->middleware(['auth', 'XSS',]);
    Route::get('carryover/{id}/action', [CarryOverController::class, 'action'])->name('carryover.action')->middleware(['auth', 'XSS',]);
    Route::post('carryover/changeaction', [CarryOverController::class, 'changeaction'])->name('carryover.changeaction')->middleware(['auth', 'XSS',]);
    Route::get('leave/team', [LeaveController::class, 'teamTimeOff'])->name('leave.team')->middleware(['auth', 'XSS',]);
    Route::any('leave/team_off', [LeaveController::class, 'teamOff'])->name('leave.team_off')->middleware(['auth','XSS',]);
    Route::get('leave/setting', [LeaveController::class, 'leave_setting'])->name('leave.leave_setting')->middleware(['auth', 'XSS',]);
    Route::post('leave_clash', [LeaveController::class, 'leave_clash'])->name('leave.leave_clash')->middleware(['auth', 'XSS',]);

    Route::post('event/getdepartment', [EventController::class, 'getdepartment'])->name('event.getdepartment')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('event/getemployee', [EventController::class, 'getemployee'])->name('event.getemployee')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('event/data/{id}', [EventController::class, 'showData'])->name('eventsshow');
    Route::resource('event', EventController::class)->middleware([ 'auth','XSS',]);

    Route::resource('trainingevent', TrainingEventController::class)->middleware([ 'auth','XSS',]);
    Route::post('trainingevent/getdepartment', [TrainingEventController::class, 'getdepartment'])->name('trainingevent.getdepartment')->middleware(['auth','XSS',]);
    Route::post('trainingevent/getemployee', [TrainingEventController::class, 'getemployee'])->name('trainingevent.getemployee')->middleware(['auth','XSS',]);
    Route::any('trainingevent/get_event_data', [TrainingEventController::class, 'get_event_data'])->name('trainingevent.get_event_data')->middleware(['auth','XSS',]);

    Route::post('trainingeventrequest/store', [TrainingEventRequestController::class, 'store'])->name('trainingeventrequest.store')->middleware(['auth','XSS',]);
    Route::get('trainingeventrequest', [TrainingEventRequestController::class, 'index'])->name('trainingeventrequest.index')->middleware(['auth','XSS',]);
    Route::delete('trainingeventrequest/{id?}', [TrainingEventRequestController::class, 'destroy'])->name('trainingeventrequest.destroy')->middleware(['auth','XSS',]);
    Route::get('trainingeventrequest/{id?}', [TrainingEventRequestController::class, 'edit'])->name('trainingeventrequest.edit')->middleware(['auth','XSS',]);
    Route::put('trainingeventrequest/{id?}', [TrainingEventRequestController::class, 'update'])->name('trainingeventrequest.update')->middleware(['auth','XSS',]);

    Route::get('import/event/file', [EventController::class, 'importFile'])->name('event.file.import');
    Route::post('import/event', [EventController::class, 'import'])->name('event.import');
    Route::get('export/event', [EventController::class, 'export'])->name('event.export');

    Route::post('meeting/getdepartment', [MeetingController::class, 'getdepartment'])->name('meeting.getdepartment')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('meeting/getemployee', [MeetingController::class, 'getemployee'])->name('meeting.getemployee')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('calender/meeting', [MeetingController::class, 'calender'])->name('meeting.calender')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('employee/update/sallary/{id}', [SetSalaryController::class, 'employeeUpdateSalary'])->name('employee.salary.update')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('salary/employeeSalary', [SetSalaryController::class, 'employeeSalary'])->name('employeesalary')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('pension-opt-ins', PensionOptInController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('pension-opt-ins/employee', [PensionOptInController::class, 'getEmployee'])->name('pensionOptIn.emp')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('pension-optout', PensionOptoutController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('pension-optout/employee', [PensionOptoutController::class, 'getEmployee'])->name('pensionOptout.emp')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('pension-schemes', PensionSchemeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::resource('setsalary', SetSalaryController::class)->middleware(['auth','XSS',]);
    Route::get('payroll/manage', [SetSalaryController::class,'payrollmanage'])->name('payroll.manage')->middleware(['auth','XSS',]);
    Route::get('payroll/setup', [SetSalaryController::class,'payrollsetup'])->name('payroll.setup')->middleware(['auth','XSS',]);
    Route::post('payroll/setup', [SetSalaryController::class,'payrollsetupUpdate'])->name('payroll_setup.update')->middleware(['auth','XSS',]);

    Route::get('payslip/paysalary/{id}/{date}', [PaySlipController::class, 'paysalary'])->name('payslip.paysalary')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('payslip/bulk_pay_create/{date}', [PaySlipController::class, 'bulk_pay_create'])->name('payslip.bulk_pay_create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('payslip/bulkpayment/{date}', [PaySlipController::class, 'bulkpayment'])->name('payslip.bulkpayment')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('payslip/search_json', [PaySlipController::class, 'search_json'])->name('payslip.search_json')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('payslip/employeepayslip', [PaySlipController::class, 'employeepayslip'])->name('payslip.employeepayslip')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('payslip/showemployee/{id}', [PaySlipController::class, 'showemployee'])->name('payslip.showemployee')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('payslip/editemployee/{id}', [PaySlipController::class, 'editemployee'])->name('payslip.editemployee')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('payslip/editemployee/{id}', [PaySlipController::class, 'updateEmployee'])->name('payslip.updateemployee')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('payslip/pdf/{id}/{m}', [PaySlipController::class, 'pdf'])->name('payslip.pdf')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('payslip/payslipPdf/{id}', [PaySlipController::class, 'payslipPdf'])->name('payslip.payslipPdf')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('payslip/send/{id}/{m}', [PaySlipController::class, 'send'])->name('payslip.send')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('payslip/delete/{id}', [PaySlipController::class, 'destroy'])->name('payslip.delete')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('payslip', PaySlipController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::resource('resignation', ResignationController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('travel', TravelController::class)->middleware(['auth', 'XSS']);
    Route::get('create_notes/travel/{id?}', [TravelController::class,'createNotes']);
    Route::post('store_notes/travel', [TravelController::class,'storeNotes']);

    Route::resource('promotion', PromotionController::class)->middleware([ 'auth','XSS']);
    Route::get('create_notes/promotion/{id?}', [PromotionController::class,'createNotes']);
    Route::post('store_notes/promotion', [PromotionController::class,'storeNotes']);
    Route::get('download/promotion/{id?}', [PromotionController::class,'promotion_file'])->name('download.promotion_file');
    Route::resource('transfer', TransferController::class)->middleware(['auth','XSS']);
    Route::get('create_notes/transfer/{id?}', [TransferController::class,'createNotes']);
    Route::post('store_notes/transfer', [TransferController::class,'storeNotes']);

    Route::resource('complaint', ComplaintController::class)->middleware(['auth','XSS']);
    Route::get('create_notes/complaint/{id?}', [ComplaintController::class,'createNotes']);
    Route::post('store_notes/complaint', [ComplaintController::class,'storeNotes']);

    Route::resource('warning', WarningController::class)->middleware(['auth','XSS']);
    Route::get('create_notes/warning/{id?}', [WarningController::class,'createNotes']);
    Route::post('store_notes/warning', [WarningController::class,'storeNotes']);
    Route::get('download/warning/{id?}', [WarningController::class,'warning_file'])->name('download.warning_file');

    Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('edit-profile', [UserController::class, 'editprofile'])->name('update.account')->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::resource('accountlist', AccountListController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('accountbalance', [AccountListController::class, 'account_balance'])->name('accountbalance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::get('leave/{id}/action', [LeaveController::class, 'action'])->name('leave.action')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('leave/changeaction', [LeaveController::class, 'changeaction'])->name('leave.changeaction')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('leave/jsoncount', [LeaveController::class, 'jsoncount'])->name('leave.jsoncount')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('leave', LeaveController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('calender/leave', [LeaveController::class, 'calender'])->name('leave.calender')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('ticket/{id}/reply', [TicketController::class, 'reply'])->name('ticket.reply')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('ticket/changereply', [TicketController::class, 'changereply'])->name('ticket.changereply')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('ticket', TicketController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('attendanceemployee/bulkattendance', [AttendanceEmployeeController::class, 'bulkAttendance'])->name('attendanceemployee.bulkattendance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('attendanceemployee/bulkattendance', [AttendanceEmployeeController::class, 'bulkAttendanceData'])->name('attendanceemployee.bulkattendance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('attendanceemployee/attendance', [AttendanceEmployeeController::class, 'attendance'])->name('attendanceemployee.attendance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('attendanceemployee/getoverview', [AttendanceEmployeeController::class, 'getOverView'])->name('attendanceemployee.getoverview')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('user-graph/{id}', [AttendanceEmployeeController::class, 'userGraph'])->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('attendanceemployee/getSingleUserAttendance', [AttendanceEmployeeController::class, 'getSingleUserAttendance'])->name('attendanceemployee.getSingleUserAttendance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('attendanceemployee', AttendanceEmployeeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('attendanceemployee/user-timesheets/{employee}', [AttendanceEmployeeController::class, 'getUserAttendance'])->name('attendanceemployee.getUserAttendance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    //import attendance
    Route::get('import/attendance/file', [AttendanceEmployeeController::class, 'importFile'])->name('attendance.file.import');
    Route::post('import/attendance', [AttendanceEmployeeController::class, 'import'])->name('attendance.import');

    Route::resource('timesheet', TimeSheetController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::resource('expensetype', ExpenseTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('incometype', IncomeTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('leavetype', LeaveTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('payees', PayeesController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('payer', PayerController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('deposit', DepositController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('expense', ExpenseController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('transferbalance', TransferBalanceController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS',
            ],
        ],
        function () {
            Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
            Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
            Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
            Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
            Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
            Route::delete('/lang/{id}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
            Route::post('disable-language', [LanguageController::class, 'disableLang'])->name('disablelanguage');
        }
    );

    Route::get('change-role/{role}', [RoleController::class, 'changeRole'])->name('change.role')->middleware(['auth', 'XSS']);

    Route::resource('roles', RoleController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('permissions', PermissionController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');

    Route::resource('coupons', CouponController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('account-assets', AssetController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('document-upload', DucumentUploadController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('appraisal', AppraisalController::class)->middleware(['auth', 'XSS',]);
    Route::resource('goaltype', GoalTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('/disciplinarywarning/create/{employee_id?}/{performance_cycle_id?}', [DisciplinaryWarningController::class, 'create'])->name('disciplinarywarning.create')->middleware(['auth', 'XSS']);
    Route::post('/disciplinarywarning', [DisciplinaryWarningController::class, 'store'])->name('disciplinarywarning.store')->middleware(['auth', 'XSS']);
    Route::get('/disciplinarywarning/{id?}/edit', [DisciplinaryWarningController::class, 'edit'])->name('disciplinarywarning.edit')->middleware(['auth', 'XSS']);
    Route::put('/disciplinarywarning/{id?}/update', [DisciplinaryWarningController::class, 'update'])->name('disciplinarywarning.update')->middleware(['auth', 'XSS']);
    Route::get('/disciplinarywarning/{employee_id?}/{performance_cycle_id?}', [DisciplinaryWarningController::class, 'index'])->name('disciplinarywarning.index')->middleware(['auth', 'XSS']);
    Route::delete('/disciplinarywarning/delete/{warning_id?}',[DisciplinaryWarningController::class,'destroy'])->name('disciplinarywarning.delete')->middleware( ['auth', 'XSS',]);

    Route::get('/goaltracking/create', [GoalTrackingController::class, 'create'])->name('goaltracking.create')->middleware(['auth', 'XSS']);
    Route::post('/goaltracking', [GoalTrackingController::class, 'store'])->name('goaltracking.store')->middleware(['auth', 'XSS']);
    Route::get('/goaltracking/{id?}/edit', [GoalTrackingController::class, 'edit'])->name('goaltracking.edit')->middleware(['auth', 'XSS']);
    Route::put('/goaltracking/{id}/update', [GoalTrackingController::class, 'update'])->name('goaltracking.update')->middleware(['auth', 'XSS']);
    Route::get('/goaltracking/{performance_cycle_id?}', [GoalTrackingController::class, 'index'])->name('goaltracking.index')->middleware(['auth', 'XSS']);
    Route::get('goaltracking/details/{id?}',[GoalTrackingController::class,'goaldetails'])->name('goaltracking.goal.details')->middleware( ['auth', 'XSS',]);
    Route::delete('/goaltracking/{id?}',[GoalTrackingController::class,'destroy'])->name('goaltracking.destroy')->middleware( ['auth', 'XSS',]);

    Route::get('goal/goals/{id?}',[GoalTrackingController::class,'goals'])->name('goaltracking.goals')->middleware( ['auth', 'XSS',]);
    Route::get('goal/status/{id?}/{status?}',[GoalTrackingController::class,'changeGoalStatus'])->name('goaltracking.goals.status')->middleware( ['auth', 'XSS',]);
    Route::get('goal/visibility/{id?}/{visibility?}',[GoalTrackingController::class,'changeVisibility'])->name('goaltracking.goals.visibility')->middleware( ['auth', 'XSS',]);
    Route::delete('goal/delete/{id?}',[GoalTrackingController::class,'destroy'])->name('goaltracking.delete')->middleware( ['auth', 'XSS',]);
    Route::put('goal/update/{id?}',[GoalTrackingController::class,'update'])->name('goaltracking.update')->middleware( ['auth', 'XSS',]);

    Route::get('goal/result/{id?}',[GoalResultController::class,'create'])->name('goal.result.create')->middleware( ['auth', 'XSS',]);
    Route::post('goal/result/store/{id?}',[GoalResultController::class,'store'])->name('goal.result.store')->middleware( ['auth', 'XSS',]);
    Route::get('goal/result/edit/{id?}',[GoalResultController::class,'edit'])->name('goal.result.edit')->middleware( ['auth', 'XSS',]);
    Route::put('goal/result/update/{id?}',[GoalResultController::class,'update'])->name('goal.result.update')->middleware( ['auth', 'XSS',]);
    Route::get('goal/result/delete/{id?}',[GoalResultController::class,'destroy'])->name('goal.result.delete')->middleware( ['auth', 'XSS',]);
    Route::get('goal/result/achieved/{id?}',[GoalResultController::class,'achieved'])->name('goal.result.achieved')->middleware( ['auth', 'XSS',]);

    Route::resource('employeereviews', EmployeeReviewController::class)->middleware( ['auth', 'XSS',]);
    Route::get('employeereviews/complete/{id?}', [EmployeeReviewController::class,'completeReview'])->name('employeereviews.complete')->middleware( ['auth', 'XSS',]);
    Route::put('employeereviews/reviewees/{id?}', [EmployeeReviewController::class,'updateReviewees'])->name('employeereviews.reviewees')->middleware( ['auth', 'XSS',]);
    Route::get('employeereviews/revieweeslist/{id?}', [EmployeeReviewController::class,'revieweesList'])->name('employeereviews.reviewees.list')->middleware( ['auth', 'XSS',]);
    Route::get('employeereviews/review-questions/{review_id?}/{user_id?}', [EmployeeReviewController::class,'reviewQuestions'])->name('employeereviews.review.questions')->middleware( ['auth', 'XSS',]);
    Route::post('submit_review_result', [EmployeeReviewController::class,'submit_review_result'])->name('submit_review_result')->middleware( ['auth', 'XSS',]);
    Route::get('employeereviews/feedback/{review_id}/{user_id}/{reviewer_id?}', [EmployeeReviewController::class,'feedback'])->name('employeereviews.feedback')->middleware( ['auth', 'XSS',]);

    Route::get('reviewquestions/create/{id?}', [ReviewQuestionController::class,'create'])->name('reviewquestions.create')->middleware( ['auth', 'XSS',]);
    Route::post('reviewquestions/store/{id?}', [ReviewQuestionController::class,'store'])->name('reviewquestions.store')->middleware( ['auth', 'XSS',]);
    Route::get('reviewquestions/delete/{id?}/{review_id?}', [ReviewQuestionController::class,'destroy'])->name('reviewquestions.delete')->middleware( ['auth', 'XSS',]);
    Route::get('reviewquestions/edit/{id?}', [ReviewQuestionController::class,'edit'])->name('reviewquestions.edit')->middleware( ['auth', 'XSS',]);
    Route::put('reviewquestions/update/{id?}/{review_id?}', [ReviewQuestionController::class,'update'])->name('reviewquestions.update')->middleware( ['auth', 'XSS',]);

    Route::resource('meetingtemplate', MeetingTemplateController::class)->middleware( ['auth', 'XSS',]);

    // Route::resource('meeting', MeetingController::class)->middleware(['auth','XSS'],);
    Route::get('meeting/details/{id?}', [MeetingController::class, 'details'])->name('meeting.details')->middleware(['auth','XSS']);
    Route::get('meetinglist/{meeting_template_id?}', [MeetingController::class,'list'])->name('meeting.list')->middleware( ['auth', 'XSS',]);
    Route::get('/meeting/create/{id?}', [MeetingController::class,'create'])->name('meeting.create')->middleware( ['auth', 'XSS',]);
    Route::post('/meeting', [MeetingController::class,'store'])->name('meeting.store')->middleware( ['auth', 'XSS',]);
    Route::get('/meeting/{id}', [MeetingController::class,'show'])->name('meeting.show')->middleware( ['auth', 'XSS',]);
    Route::get('/meeting/edit/{meeting}/{meeting_template_id?}', [MeetingController::class,'edit'])->name('meeting.edit')->middleware( ['auth', 'XSS',]);
    Route::put('/meeting/{meeting}', [MeetingController::class,'update'])->name('meeting.update')->middleware( ['auth', 'XSS',]);
    Route::delete('/meeting/{meeting}', [MeetingController::class,'destroy'])->name('meeting.destroy')->middleware( ['auth', 'XSS',]);

    Route::get('meetingtemplatepoint/create/{template_id?}', [MeetingTemplatePointController::class,'create'])->name('meetingtemplatepoint.create')->middleware( ['auth', 'XSS',]);
    Route::post('meetingtemplatepoint/store/{template_id?}', [MeetingTemplatePointController::class,'store'])->name('meetingtemplatepoint.store')->middleware( ['auth', 'XSS',]);
    Route::get('meetingtemplatepoint/delete/{template_id?}/{point_id?}', [MeetingTemplatePointController::class,'destroy'])->name('meetingtemplatepoint.delete')->middleware( ['auth', 'XSS',]);
    Route::get('meetingtemplatepoint/edit/{template_id?}/{point_id?}', [MeetingTemplatePointController::class,'edit'])->name('meetingtemplatepoint.edit')->middleware( ['auth', 'XSS',]);
    Route::put('meetingtemplatepoint/update/{template_id?}/{point_id?}', [MeetingTemplatePointController::class,'update'])->name('meetingtemplatepoint.update')->middleware( ['auth', 'XSS',]);
    Route::put('meetingnotes/notes/{meeting_id?}', [MeetingController::class,'notes'])->name('meeting.notes')->middleware( ['auth', 'XSS',]);

    Route::resource('company-policy', CompanyPolicyController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('leaveentitlement',[LeaveEntitlementController::class,'index'])->name('leaveentitlement.index');
    Route::get('leaveentitlement/create',[LeaveEntitlementController::class,'create'])->name('leaveentitlement.create');
    Route::post('leaveentitlement/store',[LeaveEntitlementController::class,'store'])->name('leaveentitlement.store');
    Route::get('leaveentitlement/{id?}/edit',[LeaveEntitlementController::class,'edit'])->name('leaveentitlement.edit');
    Route::put('leaveentitlement/update/{id?}',[LeaveEntitlementController::class,'update'])->name('leaveentitlement.update');
    Route::delete('leaveentitlement/delete/{id?}',[LeaveEntitlementController::class,'destroy'])->name('leaveentitlement.destroy');

    Route::get('holidayplanner',[HolidayPlannerController::class,'index'])->name('holidayplanner.index');
    Route::post('getholidayplanner',[HolidayPlannerController::class,'getHolidayPlanner'])->name('getholidayplanner');

    Route::get('notifications',[NotificationController::class,'index'])->name('notifications.list');
    Route::get('clear-notifications',[NotificationController::class,'clearAll'])->name('notifications.clear');
    Route::get('seen-notifications',[NotificationController::class,'seenAll'])->name('notifications.seen');

    Route::resource('notification-templates', NotificationTemplatesController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('notification-templates/{id?}/{lang?}/', [NotificationTemplatesController::class, 'index'])->name('notification-templates.index')->middleware(['auth', 'XSS']);

    Route::resource('trainingtype', TrainingTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('trainer', TrainerController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('training/status', [TrainingController::class, 'updateStatus'])->name('training.status')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::resource('training', TrainingController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('report/income-expense', [ReportController::class, 'incomeVsExpense'])->name('report.income-expense')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('report/sessions', [ReportController::class, 'sessions'])->name('report.sessions')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('report/p11-report', [ReportController::class, 'p11report'])->name('report.p11-report');
    Route::get('report/leave', [ReportController::class, 'leave'])->name('report.leave')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', [ReportController::class, 'employeeLeave'])->name('report.employee.leave')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('report/account-statement', [ReportController::class, 'accountStatement'])->name('report.account.statement')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('report/payroll', [ReportController::class, 'payroll'])->name('report.payroll')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('report/monthly/attendance', [ReportController::class, 'monthlyAttendance'])->name('report.monthly.attendance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('monthly/getdepartment', [ReportController::class, 'getdepartment'])->name('monthly.getdepartment')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('monthly/getemployee', [ReportController::class, 'getemployee'])->name('monthly.getemployee')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('report/attendance/{month}/{branch}/{department}/{employee}', [ReportController::class, 'exportCsv'])->name('report.attendance')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('report/timesheet', [ReportController::class, 'timesheet'])->name('report.timesheet')->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    //------------------------------------  Recurtment --------------------------------

    Route::resource('job-category', JobCategoryController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::resource('job-stage', JobStageController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-stage/order', [JobStageController::class, 'order'])->name('job.stage.order')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('personlized-onboarding', [PersonalizedOnboardingController::class, 'index'])->name('personlized-onboarding.index');
    Route::get('personlized-onboarding/create', [PersonalizedOnboardingController::class, 'create'])->name('personlized-onboarding.create');
    Route::post('personlized-onboarding', [PersonalizedOnboardingController::class, 'store'])->name('personlized-onboarding.store');
    Route::get('personlized-onboarding/{id}/edit', [PersonalizedOnboardingController::class, 'edit'])->name('personlized-onboarding.edit');
    Route::put('personlized-onboarding/{id}/update', [PersonalizedOnboardingController::class, 'update'])->name('personlized-onboarding.update');
    Route::delete('personlized-onboarding/{id}', [PersonalizedOnboardingController::class, 'destroy'])->name('personlized-onboarding.destroy');
    Route::delete('personlized-onboarding/question/{id}', [PersonalizedOnboardingController::class, 'destroyQuestion'])->name('personlized-onboarding.question.destroy');
    Route::delete('personlized-onboarding/file/{id}', [PersonalizedOnboardingController::class, 'destroyFile'])->name('personlized-onboarding.file.destroy');

    Route::resource('job-template', JobTemplateController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-template/make-job/{id}', [JobTemplateController::class, 'makeJob'])->name('job-template.make-job');
    Route::post('job-template/files/upload', [JobTemplateController::class, 'filesUpload'])->name('job-template.files.upload');
    Route::post('job-template/edit/files/upload', [JobTemplateController::class, 'editFilesUpload'])->name('job-template.edit.files.upload');
    Route::delete('job-template/files/delete/{id}/{redirect}', [JobTemplateController::class, 'fileDelete'])->name('job-template.files.delete');

    Route::get('job/copy/{id}', [JobController::class, 'copyJob'])->name('job.copy');

    Route::resource('job', JobController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job/files/upload', [JobController::class, 'filesUpload'])->name('job.files.upload');
    Route::post('job/edit/files/upload', [JobController::class, 'editFilesUpload'])->name('job.edit.files.upload');
    Route::delete('job/files/delete/{id}/{redirect}', [JobController::class, 'fileDelete'])->name('job.files.delete');

    // Route::get('career/{id}/{lang}', [JobController::class, 'career'])->name('career');
    // Route::get('job/requirement/{code}/{lang}', [JobController::class, 'jobRequirement'])->name('job.requirement');
    // Route::get('job/apply/{code}/{lang}', [JobController::class, 'jobApply'])->name('job.apply');
    // Route::post('job/apply/data/{code}', [JobController::class, 'jobApplyData'])->name('job.apply.data');

    Route::put('holiday-configuration/{id}', [HolidayConfigurationController::class, 'update'])->name('holiday-configuraton.update');
    Route::resource('holiday-configuration', HolidayConfigurationController::class)->middleware(['auth', 'XSS']);



    Route::resource('position', PositionController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('case', CaseController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('case/closed/{id}', [CaseController::class, 'caseClosed'])->name('case.closed')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('case-category', CaseCategoryController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('case-discussion', CaseDiscussionController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('workforce-planning/analytics', [WorkforcePlanningController::class, 'analytics'])->name('workforce-planning.analytics')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('workforce-planning/kpis', [WorkforcePlanningController::class, 'kpis'])->name('workforce-planning.kpis')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('workforce-planning/kpis/growth', [WorkforcePlanningController::class, 'kpisTotalGrowth'])->name('workforce-planning.kpis.totalgrowth')->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::get('candidates-job-applications', [JobApplicationController::class, 'candidate'])->name('job.application.candidate')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('job-application', JobApplicationController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('job-application/order', [JobApplicationController::class, 'order'])->name('job.application.order')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-application/{id}/rating', [JobApplicationController::class, 'rating'])->name('job.application.rating')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::delete('job-application/{id}/archive', [JobApplicationController::class, 'archive'])->name('job.application.archive')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('job-application/{id}/skill/store', [JobApplicationController::class, 'addSkill'])->name('job.application.skill.store')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-application/{id}/note/store', [JobApplicationController::class, 'addNote'])->name('job.application.note.store')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::delete('job-application/{id}/note/destroy', [JobApplicationController::class, 'destroyNote'])->name('job.application.note.destroy')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-application/getByJob', [JobApplicationController::class, 'getByJob'])->name('get.job.application')->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::get('job-onboard', [JobApplicationController::class, 'jobOnBoard'])->name('job.on.board')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('onboarding-checklist', [JobApplicationController::class, 'onboardingChecklist'])->name('onboarding-checklist.index')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('new-employees-checklist', [JobApplicationController::class, 'newEmployeesChecklist'])->name('onboarding-checklist.new-employees-checklist')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('job-onboard/create/{id}', [JobApplicationController::class, 'jobBoardCreate'])->name('job.on.board.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-onboard/store/{id}', [JobApplicationController::class, 'jobBoardStore'])->name('job.on.board.store')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('job-onboard/edit/{id}', [JobApplicationController::class, 'jobBoardEdit'])->name('job.on.board.edit')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-onboard/update/{id}', [JobApplicationController::class, 'jobBoardUpdate'])->name('job.on.board.update')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::delete('job-onboard/delete/{id}', [JobApplicationController::class, 'jobBoardDelete'])->name('job.on.board.delete')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('job-onboard/convert/{id}', [JobApplicationController::class, 'jobBoardConvert'])->name('job.on.board.convert')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('job-onboard/convert/{id}', [JobApplicationController::class, 'jobBoardConvertData'])->name('job.on.board.convert')->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::post('job-application/stage/change', [JobApplicationController::class, 'stageChange'])->name('job.application.stage.change')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('custom-question', CustomQuestionController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('question-template', QuestionTemplateController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::get('question-template/branching/{id}', [QuestionTemplateController::class, 'manageBranching'])->name('question-template.branching')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::post('question-template/branching/{id}', [QuestionTemplateController::class, 'processBranchingLogic'])->name('question-template.branching.store')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('config-word-count', JobWordCountController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );


    Route::resource('interview-schedule', InterviewScheduleController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('interview-schedule/create/{id?}', [InterviewScheduleController::class, 'create'])->name('interview-schedule.create')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    //================================= Custom Landing Page ====================================//

    // Route::get('/landingpage', 'LandingPageSectionController@index')->name('custom_landing_page.index')->middleware(['auth', 'XSS']);
    Route::get('/LandingPage/show/{id}', [LandingPageSectionController::class, 'show']);
    Route::post('/LandingPage/setConetent', [LandingPageSectionController::class, 'setConetent'])->middleware(['auth', 'XSS']);
    Route::post('/LandingPage/removeSection/{id}', [LandingPageSectionController::class, 'removeSection'])->middleware(['auth', 'XSS']);
    Route::post('/LandingPage/setOrder', [LandingPageSectionController::class, 'setOrder'])->middleware(['auth', 'XSS']);
    Route::post('/LandingPage/copySection', [LandingPageSectionController::class, 'copySection'])->middleware(['auth', 'XSS']);


    Route::resource('competencies', CompetenciesController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('performanceType', PerformanceTypeController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    //employee Import & Export
    Route::get('import/employee/file', [EmployeeController::class, 'importFile'])->name('employee.file.import');
    Route::post('import/employee', [EmployeeController::class, 'import'])->name('employee.import');
    Route::get('export/employee', [EmployeeController::class, 'export'])->name('employee.export');



    // Timesheet Import & Export

    Route::get('import/timesheet/file', [TimeSheetController::class, 'importFile'])->name('timesheet.file.import');
    Route::post('import/timesheet', [TimeSheetController::class, 'import'])->name('timesheet.import');
    Route::get('export/timesheet', [TimeSheetController::class, 'export'])->name('timesheet.export');
    Route::get('export/timesheet/export', [ReportController::class, 'exportTimeshhetReport'])->name('timesheet.report.export');

    //leave export
    Route::get('export/leave', [LeaveController::class, 'export'])->name('leave.export');
    Route::get('export/leave/report', [ReportController::class, 'LeaveReportExport'])->name('leave.report.export');

    //Account Statement Export
    Route::get('export/accountstatement/report', [ReportController::class, 'AccountStatementReportExport'])->name('accountstatement.report.export');

    //Payroll Export
    Route::get('export/payroll/{month}/{branch}/{department}', [ReportController::class, 'PayrollReportExport'])->name('payroll.report.export');

    // payslip export
    Route::post('export/payslip', [PaySlipController::class, 'PayslipExport'])->name('payslip.export');

    //deposite Export
    Route::get('export/deposite', [DepositController::class, 'export'])->name('deposite.export');

    //expense Export
    Route::get('export/expense', [ExpenseController::class, 'export'])->name('expense.export');

    //Transfer Balance Export
    Route::get('export/transfer-balance', [TransferBalanceController::class, 'export'])->name('transfer_balance.export');

    //Training Import & Export
    Route::get('export/training', [TrainingController::class, 'export'])->name('training.export');

    //Trainer Export
    Route::get('export/trainer', [TrainerController::class, 'export'])->name('trainer.export');
    Route::get('import/training/file', [TrainerController::class, 'importFile'])->name('trainer.file.import');
    Route::post('import/training', [TrainerController::class, 'import'])->name('trainer.import');

    //Holiday Export & Import
    Route::get('export/holidays', [HolidayController::class, 'export'])->name('holidays.export');
    Route::get('import/holidays/file', [HolidayController::class, 'importFile'])->name('holidays.file.import');
    Route::post('import/holidays', [HolidayController::class, 'import'])->name('holidays.import');

    //Asset Import & Export
    Route::get('export/assets', [AssetController::class, 'export'])->name('assets.export');
    Route::get('import/assets/file', [AssetController::class, 'importFile'])->name('assets.file.import');
    Route::post('import/assets', [AssetController::class, 'import'])->name('assets.import');

    //zoom meeting
    Route::any('zoommeeting/calendar', [ZoomMeetingController::class, 'calender'])->name('zoom_meeting.calender')->middleware(['auth', 'XSS']);
    Route::resource('zoom-meeting', ZoomMeetingController::class)->middleware(['auth', 'XSS']);

    //slack
    Route::post('setting/slack', [SettingsController::class, 'slack'])->name('slack.setting');

    //telegram
    Route::post('setting/telegram', [SettingsController::class, 'telegram'])->name('telegram.setting');

    //twilio
    Route::post('setting/twilio', [SettingsController::class, 'twilio'])->name('twilio.setting');

    // recaptcha
    Route::post('/recaptcha-settings', [SettingsController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store')->middleware(['auth', 'XSS']);

    // user reset password
    Route::get('user-login/{id}', [UserController::class, 'LoginManage'])->name('user.login');
    Route::any('user-reset-password/{id}', [UserController::class, 'userPassword'])->name('user.reset');
    Route::post('user-reset-password/{id}', [UserController::class, 'userPasswordReset'])->name('user.password.update');

    //contract
    Route::resource('contract_type', ContractTypeController::class)->middleware(['auth', 'XSS']);
    Route::resource('contract', ContractController::class)->middleware(['auth', 'XSS']);
    Route::post('/contract_status_edit/{id}', [ContractController::class, 'contract_status_edit'])->name('contract.status')->middleware(['auth', 'XSS']);
    Route::post('/contract/{id}/file', [ContractController::class, 'fileUpload'])->name('contracts.file.upload')->middleware(['auth', 'XSS']);
    Route::get('/contract/{id}/file/{fid}',  [ContractController::class, 'fileDownload'])->name('contracts.file.download')->middleware(['auth', 'XSS']);
    Route::get('/contract/{id}/file/delete/{fid}', [ContractController::class, 'fileDelete'])->name('contracts.file.delete')->middleware(['auth', 'XSS']);
    Route::post('/contract/{id}/notestore', [ContractController::class, 'noteStore'])->name('contracts.note.store')->middleware(['auth']);
    Route::get('/contract/{id}/note', [ContractController::class, 'noteDestroy'])->name('contracts.note.destroy')->middleware(['auth']);

    Route::post('contract/{id}/description', [ContractController::class, 'descriptionStore'])->name('contracts.description.store')->middleware(['auth']);


    Route::post('/contract/{id}/commentstore', [ContractController::class, 'commentStore'])->name('comment.store');
    Route::get('/contract/{id}/comment', [ContractController::class, 'commentDestroy'])->name('comment.destroy');


    Route::get('/contract/copy/{id}', [ContractController::class, 'copycontract'])->name('contracts.copy')->middleware(['auth', 'XSS']);
    Route::post('/contract/copy/store/{id}', [ContractController::class, 'copycontractstore'])->name('contracts.copystore')->middleware(['auth', 'XSS']);

    Route::get('contract/{id}/get_contract', [ContractController::class, 'printContract'])->name('get.contract');
    Route::get('contract/pdf/{id}', [ContractController::class, 'pdffromcontract'])->name('contract.download.pdf');

    Route::get('/dual-contract',  [ContractController::class, 'dual_contracts'])->name('contract.dual')->middleware(['auth', 'XSS']);


    // Route::get('/signature/{id}', 'ContractController@signature')->name('signature')->middleware(['auth','XSS']);
    // Route::post('/signaturestore', 'ContractController@signatureStore')->name('signaturestore')->middleware(['auth','XSS']);

    Route::get('/contract/{id}/mail', [ContractController::class, 'sendmailContract'])->name('send.mail.contract');
    Route::get('/signature/{id}', [ContractController::class, 'signature'])->name('signature')->middleware(['auth', 'XSS']);
    Route::post('/signaturestore', [ContractController::class, 'signatureStore'])->name('signaturestore')->middleware(['auth', 'XSS']);

    //offer Letter
    Route::post('setting/offerlatter/{lang?}', [SettingsController::class, 'offerletterupdate'])->name('offerlatter.update');
    Route::get('setting/offerlatter', [SettingsController::class, 'index'])->name('get.offerlatter.language');
    Route::get('job-onboard/pdf/{id}', [JobApplicationController::class, 'offerletterPdf'])->name('offerlatter.download.pdf');
    Route::get('job-onboard/doc/{id}', [JobApplicationController::class, 'offerletterDoc'])->name('offerlatter.download.doc');

    //joining Letter
    Route::post('setting/joiningletter/{lang?}', [SettingsController::class, 'joiningletterupdate'])->name('joiningletter.update');
    Route::get('setting/joiningletter/', [SettingsController::class, 'index'])->name('get.joiningletter.language');
    Route::get('employee/pdf/{id}', [EmployeeController::class, 'joiningletterPdf'])->name('joiningletter.download.pdf');
    Route::get('employee/doc/{id}', [EmployeeController::class, 'joiningletterDoc'])->name('joininglatter.download.doc');



    Route::post('employee/changes/{id}/accept', [EmployeeController::class, 'acceptChanges'])->name('employee.changes.accept');
    Route::post('employee/changes/{id}/reject', [EmployeeController::class, 'rejectChanges'])->name('employee.changes.reject');


    //Experience Certificate
    Route::post('setting/exp/{lang?}', [SettingsController::class, 'experienceCertificateupdate'])->name('experiencecertificate.update');
    Route::get('setting/exp', [SettingsController::class, 'index'])->name('get.experiencecertificate.language');
    Route::get('employee/exppdf/{id}', [EmployeeController::class, 'ExpCertificatePdf'])->name('exp.download.pdf');
    Route::get('employee/expdoc/{id}', [EmployeeController::class, 'ExpCertificateDoc'])->name('exp.download.doc');

    //NOC
    Route::post('setting/noc/{lang?}', [SettingsController::class, 'NOCupdate'])->name('noc.update');
    Route::get('setting/noc', [SettingsController::class, 'index'])->name('get.noc.language');
    Route::get('employee/nocpdf/{id}', [EmployeeController::class, 'NocPdf'])->name('noc.download.pdf');
    Route::get('employee/nocdoc/{id}', [EmployeeController::class, 'NocDoc'])->name('noc.download.doc');

    //appricalStar
    Route::post('/appraisals', [AppraisalController::class, 'empByStar'])->name('empByStar')->middleware(['auth', 'XSS']);
    Route::post('/appraisals1', [AppraisalController::class, 'empByStar1'])->name('empByStar1')->middleware(['auth', 'XSS']);
    Route::post('/getemployee', [AppraisalController::class, 'getemployee'])->name('getemployee');

    //storage Setting
    Route::post('storage-settings', [SettingsController::class, 'storageSettingStore'])->name('storage.setting.store')->middleware(['auth', 'XSS']);
    // });

    // Google Calendar
    Route::post('setting/google-calender', [SettingsController::class, 'saveGoogleCalenderSettings'])->name('google.calender.settings')->middleware(['auth', 'XSS']);

    // SEO Settings
    Route::post('setting/seo-setting', [SettingsController::class, 'SeoSettings'])->name('seo.settings')->middleware(['auth', 'XSS']);

    // cache Settings
    Route::post('setting/cache-setting', [SettingsController::class, 'CacheSettings'])->name('clear.cache')->middleware(['auth', 'XSS']);

    // cookie consent
    Route::post('cookie-setting', [SettingsController::class, 'saveCookieSettings'])->name('cookie.setting')->middleware(['auth', 'XSS']);

    // get calender
    Route::any('event/get_event_data', [EventController::class, 'get_event_data'])->name('event.get_event_data')->middleware(['auth', 'XSS']);

    Route::any('zoom-meeting/get_zoom_meeting_data', [ZoomMeetingController::class, 'get_zoom_meeting_data'])->name('zoommeeting.get_zoom_meeting_data')->middleware(['auth', 'XSS']);

    Route::any('holiday/get_holiday_data', [HolidayController::class, 'get_holiday_data'])->name('holiday.get_holiday_data')->middleware(['auth', 'XSS']);

    Route::any('/interview-schedule/get_interview-schedule_data', [InterviewScheduleController::class, 'get_interview_schedule_data'])->name('interview-schedule.get_interview-schedule_data')->middleware(['auth', 'XSS']);

    Route::any('leave/get_leave_data', [LeaveController::class, 'get_leave_data'])->name('leave.get_leave_data')->middleware(['auth', 'XSS']);

    Route::any('/meeting/get_meeting_data', [MeetingController::class, 'get_meeting_data'])->name('meeting.get_meeting_data')->middleware(['auth', 'XSS']);

    Route::post('chatgptkey', [SettingsController::class, 'chatgptkey'])->name('settings.chatgptkey')->middleware(['auth', 'XSS']);
    Route::get('generate/{template_name}', [AiTemplateController::class, 'create'])->name('generate')->middleware(['auth', 'XSS']);
    Route::post('generate/keywords/{id}', [AiTemplateController::class, 'getKeywords'])->name('generate.keywords')->middleware(['auth', 'XSS']);
    Route::post('generate/response', [AiTemplateController::class, 'AiGenerate'])->name('generate.response')->middleware(['auth', 'XSS']);

    // Grammer Check With AI
    Route::get('grammar/{template}', [AiTemplateController::class, 'grammar'])->name('grammar')->middleware(['auth', 'XSS']);
    Route::post('grammar/response', [AiTemplateController::class, 'grammarProcess'])->name('grammar.response')->middleware(['auth', 'XSS']);

    Route::resource('eclaim_type', EclaimTypeController::class)->middleware(['auth', 'XSS']);

    // Eclaim Routes
    Route::get('eclaim/{id}/reject', [EclaimController::class, 'rejectForm'])->middleware(['auth',  'XSS']);
    Route::post('eclaim/save-reject-form/{id}', [EclaimController::class, 'saveRejectionForm'])->middleware(['auth',  'XSS']);

    Route::get('eclaim/{id}/approve', [EclaimController::class, 'renderApprovalForm'])->middleware(['auth',  'XSS']);
    Route::post('eclaim/save-approval-form/{id}', [EclaimController::class, 'saveApprovalForm'])->middleware(['auth',  'XSS']);
    Route::resource('eclaim', EclaimController::class)->middleware(['auth',  'XSS']);
    Route::post('eclaim/{id}/edit', [EclaimController::class, 'edit']);
    Route::get('eclaim/showHistory/{id}', [EclaimController::class, 'showHistory']);
    Route::get('eclaim/showReceipt/{id}', [EclaimController::class, 'showReceipt']);

    // videos
    Route::resource('video', VideoController::class)->middleware(['auth',  'XSS']);

    //Flexi Time
    Route::post('flexi-time/save-reject-form/{id}', [FlexiTimeController::class, 'saveRejectionForm']);
    Route::get('flexi-time/{id}/reject', [FlexiTimeController::class, 'rejectForm'])->name('flexi-time.reject');
    Route::get('flexi-time/{id}/approve', [FlexiTimeController::class, 'approve'])->name('flexi-time.approve');
    Route::resource('flexi-time', FlexiTimeController::class)->middleware(['auth',  'XSS']);

    // cache
    Route::get('/config-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        return redirect()->back()->with('success', 'Cache Clear Successfully');
    })->name('config.cache');
});

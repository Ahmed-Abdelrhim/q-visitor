<?php

use App\Http\Controllers\Admin\CompanionController;
use App\Http\Controllers\Admin\ContractorController;
use App\Http\Controllers\Admin\OcrController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\WorkerController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\QrController;
use App\Http\Controllers\Admin\LogsController;

Route::group(['middleware' => 'prevent-back-history'], function () {


    //Route::get('code',[LoginController::class,'insertSomeCodes']);
    Route::get('Code/Activation/Page', [LoginController::class, 'showCodeActivationPage'])->name('code.activation.page');
    Route::get('session', [LoginController::class, 'session'])->name('session');
    Route::post('authenticated/user/logout', [LoginController::class, 'logout'])->name('auth.logout');


    Route::group(['middleware' => ['installed']], function () {
        Auth::routes(['verify' => false]);
    });
    Route::group(['prefix' => 'install', 'as' => 'LaravelInstaller::', 'middleware' => ['web', 'install']], function () {
        Route::post('environment/saveWizard', [
            'as' => 'environmentSaveWizard',
            'uses' => 'EnvironmentController@saveWizard',
        ]);

        Route::get('purchase-code', [
            'as' => 'purchase_code',
            'uses' => 'PurchaseCodeController@index',
        ]);

        Route::post('purchase-code', [
            'as' => 'purchase_code.check',
            'uses' => 'PurchaseCodeController@action',
        ]);
    });

    Route::redirect('/index.php/', '/index.php/admin/dashboard')->middleware('backend_permission');
    Route::redirect('/admin', '/index.php/admin/dashboard')->middleware('backend_permission');

    Route::group(['prefix' => 'admin', 'middleware' => ['installed'], 'namespace' => 'Admin', 'as' => 'admin.'], function () {
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('show.login.form');
        Route::post('login', [LoginController::class,'login'])->name('custom.login');
        Route::post('Code/Activation', [LoginController::class, 'codeActivation'])->name('code.activation');
    });


    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'installed', 'backend_permission'], 'namespace' => 'Admin', 'as' => 'admin.'], function () {

        Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');

        Route::get('profile', 'ProfileController@index')->name('profile');
        Route::put('profile/update/{profile}', 'ProfileController@update')->name('profile.update');
        Route::put('profile/change', 'ProfileController@change')->name('profile.change');
        Route::resource('adminusers', 'AdminUserController');
        Route::get('get-adminusers', 'AdminUserController@getAdminUsers')->name('adminusers.get-adminusers');
        Route::resource('role', 'RoleController');
        Route::post('role/save-permission/{id}', 'RoleController@savePermission')->name('role.save-permission');

        //designations
        Route::resource('designations', 'DesignationsController');
        Route::get('get-designations', 'DesignationsController@getDesignations')->name('designations.get-designations');

        //types
        Route::resource('types', 'TypesController');
        // Route::get('typesForRoles');
        Route::get('get-types', 'TypesController@getTypes')->name('types.get-types');


        //Shipments···
        Route::resource('shipment','ShipmentController');

        //departments
        Route::resource('departments', 'DepartmentsController');
        Route::get('get-departments', 'DepartmentsController@getDepartments')->name('departments.get-departments');

        //employee route
        Route::resource('employees', 'EmployeeController');
        Route::get('get-employees', 'EmployeeController@getEmployees')->name('employees.get-employees');
        Route::get('employees/get-pre-registers/{id}', 'EmployeeController@getPreRegister')->name('employees.get-pre-registers');
        Route::get('employees/get-visitors/{id}', 'EmployeeController@getVisitor')->name('employees.get-visitors');
        Route::put('employees/check/{id}', 'EmployeeController@checkEmployee')->name('employees.check');

        //pre-registers
        Route::resource('pre-registers', 'PreRegisterController');
        Route::get('get-pre-registers', 'PreRegisterController@getPreRegister')->name('pre-registers.get-pre-registers');
        Route::get('Approve/Pre-register/{id}', 'PreRegisterController@approvePreRegister')->name('pre-registers.approve');

        //visitors
        Route::resource('visitors', 'VisitorController');
        Route::get('get-visitors', 'VisitorController@getVisitor')->name('visitors.get-visitors');
        Route::get('send-sms/{visitingDetail}', 'VisitorController@sendSms')->name('visitors.send.sms');
        // Route::post('Visit/First/Approve', [VisitorController::class,'visitFirstApprove'])->name('visit.first.approve');
        // Route::post('Visit/Second/Approve', [VisitorController::class,'visitSecondApprove'])->name('visit.second.approve');
        Route::get('Visit/Approve/{approval_status}', [VisitorController::class, 'visitApprove'])->name('visit.approval');

        Route::get('visitors/{id}/Companions',[VisitorController::class,'companions'])->name('visitors.companions');
        Route::get('visitors/Qulaity/Approve/{id}',[VisitorController::class,'visitApproveFromQulaity'])->name('visitors.qulaity.approve');

        // Contractor Controller···
        Route::get('Contractor/Index',[ContractorController::class,'index'])->name('contractor.index');
        Route::get('Contractor/Create/{contractor_id}',[ContractorController::class,'create'])->name('contractor.create');
        Route::post('Contractor/Store/{contractor_id}',[ContractorController::class,'store'])->name('contractor.store');

        Route::post('Workers/Search',[WorkerController::class,'search'])->name('workers.search'); 
        Route::post('Workers/Find',[WorkerController::class,'findWorkerWithNationalNum'])->name('find.this.worker');
        Route::post('Workers/Import/To/Excel/{visit_id}',[WorkerController::class,'importToExcel'])->name('import.excel');


        // Ocr Resource Controller···
        Route::resource('OCR', 'OcrController');
        Route::get('get-last-car-plate', [OcrController::class, 'getLastCarPlate'])->name('get.last.car.plate');
        Route::get('ocr-clear', [OcrController::class, 'ocrClear'])->name('ocr.clear');
        Route::get('ocr-indexxarr', [OcrController::class, 'ocrIndexxar'])->name('ocr.indexxar');
        Route::get('ocr-print/{id?}', [OcrController::class, 'ocrPrint'])->name('ocr.print');
        Route::post('ocr-save', [OcrController::class, 'ocrSave'])->name('ocr.save');

        Route::get('ocr/view', [OcrController::class, 'viewFirstPage'])->name('reload.ocr.view');
        Route::get('Optical/Character/Recognition/View/Scan/{visit_id?}', [OcrController::class, 'viewScanPage'])->name('view.scan.page');

        // QRController···
        Route::get('Qr/Index',[QrController::class,'index'])->name('qr.index');
        Route::post('Scan/Qr',[QrController::class,'scanQr'])->name('scan.qr');

        Route::post('Accept/Quality-Control/Visit/Check',[QrController::class,'acceptVisit'])->name('accept.visit.from.quality');
        Route::post('Reject/Quality-Control/Visit/Check',[QrController::class,'rejectVisit'])->name('reject.visit.from.quality');



        // New Scan To Create A Visit...
        Route::get('New/Scan/{car_type}', [OcrController::class, 'newScan'])->name('new.scan');
        Route::post('New/Scan/Post', [OcrController::class, 'newScanSaveData'])->name('new.scan.post');
        Route::get('Search/Car/Plate', [OcrController::class, 'searchWithCarPlateNumber'])->name('search.car.plate');

        Route::get('Approving/New/Scan/Only/{visit_id}/{approval_status}',[VisitorController::class,'approvingNewScansOnly'])->name('new.scan.approving');

        Route::get('Visits/Search', [OcrController::class, 'searchVisitingDetails'])->name('ocr.search.visitors');
        Route::get('Visits/Destroy/{id}', [OcrController::class, 'destroy'])->name('ocr.destroy');


        // Companion...
        Route::get('Companion/{id}', [CompanionController::class, 'index'])->name('companion.index');
        Route::get('Add/Visit/Companion/{visit_id}', [CompanionController::class, 'addVisitCompanion'])->name('add.companion.to.visit');
        Route::post('Add/Another/Companion', [CompanionController::class, 'addAnotherCompanion'])->name('add.another.companion');
        Route::post('Add/Last/Companion', [CompanionController::class, 'addLastCompanion'])->name('add.last.companion');
        Route::post('Remove/Companion/{id}',[CompanionController::class,'removeCompanion'])->name('remove.companion');



        // Logs ···
        Route::get('Index' , [LogsController::class,'index'])->name('logs.index');
        Route::get('Search' , [LogsController::class,'search'])->name('logs.search');
        Route::get('print/logs',[LogsController::class,'printLogs'])->name('print.logs');

        Route::get('Logs/Download/Report/{logs_date}',[LogsController::class,'downloadPdf'])->name('logs.download.pdf');





        // Settings Routes···
        Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::get('/', 'SettingController@index')->name('index');
            Route::post('/', 'SettingController@siteSettingUpdate')->name('site-update');
            Route::get('sms', 'SettingController@smsSetting')->name('sms');
            Route::post('sms', 'SettingController@smsSettingUpdate')->name('sms-update');
            Route::get('email', 'SettingController@emailSetting')->name('email');
            Route::post('email', 'SettingController@emailSettingUpdate')->name('email-update');
            Route::get('notification', 'SettingController@notificationSetting')->name('notification');
            Route::post('notification', 'SettingController@notificationSettingUpdate')->name('notification-update');
            Route::get('emailtemplate', 'SettingController@emailTemplateSetting')->name('email-template');
            Route::post('emailtemplate', 'SettingController@mailTemplateSettingUpdate')->name('email-template-update');
            Route::get('homepage', 'SettingController@homepageSetting')->name('homepage');
            Route::post('homepage', 'SettingController@homepageSettingUpdate')->name('homepage-update');
        });
    });


    Route::group(['middleware' => ['installed']], function () {
        Route::group(['middleware' => ['frontend']], function () {
            Route::get('/', 'CheckInController@index')->name('/');

            Route::get('/check-in', [
                'as' => 'check-in',
                'uses' => 'CheckInController@index'
            ]);

            Route::get('/check-in/create-step-one', [
                'as' => 'check-in.step-one',
                'uses' => 'CheckInController@createStepOne'
            ]);
            Route::post('/check-in/create-step-one', [
                'as' => 'check-in.step-one.next',
                'uses' => 'CheckInController@postCreateStepOne'
            ]);

            Route::get('/check-in/create-step-two', [
                'as' => 'check-in.step-two',
                'uses' => 'CheckInController@createStepTwo'
            ]);
            Route::post('/check-in/create-step-two', [
                'as' => 'check-in.step-two.next',
                'uses' => 'CheckInController@store'
            ]);

            Route::get('/check-in/show/{id}', [
                'as' => 'check-in.show',
                'uses' => 'CheckInController@show'
            ]);
            Route::get('/check-in/return', [
                'as' => 'check-in.return',
                'uses' => 'CheckInController@visitor_return'
            ]);
            Route::post('/check-in/return', [
                'as' => 'check-in.find.visitor',
                'uses' => 'CheckInController@find_visitor'
            ]);

            Route::get('/check-in/pre-registered', [
                'as' => 'check-in.pre.registered',
                'uses' => 'CheckInController@pre_registered'
            ]);
            Route::post('/check-in/pre-registered', [
                'as' => 'check-in.find.pre.visitor',
                'uses' => 'CheckInController@find_pre_visitor'
            ]);

            Route::get('/foo', function () {
                Artisan::call('storage:link');
            });

            Route::get('email/check', [\App\Http\Controllers\Admin\VisitorController::class, 'checkEmail']);


        });
        Route::get('change_locale/{iso}', [HomeController::class, 'changeLocaleLanguage'])->name('change_locale');
        Route::get('Play', [\App\Http\Controllers\Admin\VisitorController::class, 'play']);
        Route::get('playy', [OcrController::class, 'playy']);

        Route::get('Licence/Plate/Recognition/Camera',[OcrController::class,'xmlData'])->name('xml.data');
    });
    //Route::get('play',function (){
    //    return auth()->user()->getDirectPermissions();
    //});
});


// show only pending visits in the dashboard

// git add .
// git commit -m "aya update"
// git pull  <already up to date>>
// git push


// git config --global user.email "aya2donia25@gmail.com"
//  git config --global user.name "aya25donia"

// http://127.0.0.1:8000


// php artisan storage:link

//Arc Dark
//Atom one dark (material)

//127811

// sign_up / verification_code


// translation files ar and en
// style-ar css file
// Departments => create & edit blade , DepartmentsController
// DesignationsController (Positions) => create & edit blade , DesignationsController
// EmployeeController => DesignationsController
// VisitorController => VisitorController
// PreRegisterController => PreRegisterController
// AdminUserController => AdminUserController


// Roles => index & create & edit & show blade  , RoleController done
// Types => index & create & edit blade , TypesController

// ============================================================================================================================================
// I Need to upload the types···
// BackEnd Middleware···
// Upload the VisitingDetails and Type Models.
// VisitorController create , edit , show and VisitorService.
// Web .php , Translation Files.
// CheckInController , DashboardController , dashboard index.blade.php , OcrController
// DesignationsController
// ============================================================================================================================================



// Shipment Number => 127638
// Shipment ID => 2


// ^7.1.3


//DB_CONNECTION_TWO=sqlsrv
//DB_HOST_TWO=N1NWPLSK12SQL-v01.shr.prod.ams1.secureserver.net
//DB_PORT_TWO=1433
//DB_DATABASE_TWO=ph20922595621_polimek
//DB_USERNAME_TWO=polimek_user
//DB_PASSWORD_TWO=j9@8Nt8q




// Heerr

//DB_CONNECTION_TWO=sqlsrv
//DB_HOST_TWO=N1NWPLSK12SQL-v01.shr.prod.ams1.secureserver.net
//DB_PORT_TWO=1433
//DB_DATABASE_TWO=ph20922595621_polimek
//DB_USERNAME_TWO=dbeditor
//DB_PASSWORD_TWO=py2%1Oj8



// eruuszjdfgjktzpu

// name nat_id , visit_id
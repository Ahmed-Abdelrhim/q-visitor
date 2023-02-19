<?php

use App\Http\Controllers\Admin\OcrController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


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
        Route::get('get-types', 'TypesController@getTypes')->name('types.get-types');

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

        // Ocr Resource Controller···
//        Route::group(['middleware' => ['role_or_permission:Admin,ocr|ocr_create']], function () {
        Route::group(['middleware' => ['role:Admin|OCR','permission:ocr_create']], function () {
            Route::resource('OCR','OcrController');
            Route::get('get-last-car-plate',[OcrController::class,'getLastCarPlate'])->name('get.last.car.plate');
            Route::get('ocr-clear',[OcrController::class,'ocrClear'])->name('ocr.clear');
            Route::get('ocr-indexxarr',[OcrController::class,'ocrIndexxar'])->name('ocr.indexxar');
            Route::get('ocr-print/{id?}',[OcrController::class,'ocrPrint'])->name('ocr.print');
            Route::post('ocr-save',[OcrController::class,'ocrSave'])->name('ocr.save');
        });
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


    /*Multi step form*/

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
        });
        Route::get('play', [\App\Http\Controllers\Admin\VisitorController::class, 'play']);
        Route::get('playy', [OcrController::class, 'playy']);
    });
//Route::get('play',function (){
//    return auth()->user()->getDirectPermissions();
//});

});
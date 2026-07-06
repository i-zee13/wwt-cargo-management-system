<?php

use App\Http\Controllers\Auth\ClientForgotPasswordController;
use App\Http\Controllers\Auth\ClientResetPasswordController;
use App\Http\Controllers\Auth\ClientsLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\EmailContentController;
use App\Http\Controllers\Employee;
use App\Http\Controllers\FreightRateController;
use App\Http\Controllers\GeographicalSettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DesignationAccessRightsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TranslationsController;
use App\Http\Controllers\TranslationAssetController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Http\Controllers\ClientController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Carbon\Carbon;

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

Route::get('/js/messages.js', TranslationAssetController::class)->name('translations.js');

Route::post('/change-language', function (Request $request) {
    $language = $request->input('languageToggle', config('app.locale'));
    $supportedLanguages = config('translation.supported_locales', ['en', 'es']);
    if (!in_array($language, $supportedLanguages)) {
        return response()->json(['error' => 'Unsupported language'], 400);
    }
    app()->setLocale($language);
    Session::put('locale', $language);
    return response()->json(['message' => 'Language changed successfully', 'locale' => $language]);
})->name('change-language');



Route::group(['middleware' => ['lang_set']], function () {

    // Route::get('/login', [App\Http\Controllers\Auth\ClientsLoginController::class, 'showLoginForm'])->name('login');
    Route::get('/', function () {

        if (Auth::guard('web')->check()) {
            return redirect('/admin/home');
        } else if (Auth::guard('clients')->check()) {
            return redirect('/customer-home');
        } else {
            return redirect('/customer-login');
        }
    });
    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        return 'Caches cleared and optimized successfully.';
    });

    Route::get('/migrate', function () {
        Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = Artisan::output();

        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return response(
            "Migrations completed.\n\n{$migrateOutput}\n\nCaches cleared.",
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    });

    Route::prefix('client')->group(function () {
        Auth::routes();
        Route::get('password/reset', [ClientForgotPasswordController::class, 'showLinkRequestForm'])->name('client.request');
        Route::post('password/email', [ClientForgotPasswordController::class, 'sendResetLinkEmail'])->name('client.email');
        Route::get('password/reset/{token}', [ClientResetPasswordController::class, 'showResetForm'])->name('client.reset');
        Route::post('password/reset', [ClientResetPasswordController::class, 'reset'])->name('client.update');
        
    });
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::group(['middleware' => ['auth:web']], function () {
        Route::get('/reset-password-first', function () {
            if (Auth::user()->password_changed != 1) {
                return view('auth.reset-password-first');
            } else {
                return redirect('/');
            }
        });
    });

    Route::prefix('admin')->group(function () {
        Auth::routes();
        Route::get('/change_password', [App\Http\Controllers\Auth\LoginController::class, 'change_password'])->name('change_password');
        Route::post('/ChangeUserPassword', [App\Http\Controllers\Auth\LoginController::class, 'ChangeUserPassword'])->name('ChangeUserPassword');
        Route::get('/reset-password-first', [App\Http\Controllers\HomeController::class, 'ResetPasswordFirst'])->name('reset-password-first');
        Route::post('/update_user_password_first', [App\Http\Controllers\HomeController::class, 'UpdatePasswordFirst'])->name('update_user_password_first');
        Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin-login');
        Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
        Route::post('/resetPasswordFirst', [HomeController::class, 'UpdatePasswordFirst'])->name('password.reset-first');
        Route::post('/mylogin', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('mylogin');

    });




    Route::group(['prefix' => 'admin', 'middleware' => ['auth:web', 'is_password_changed', 'is_route_assigned']], function () {
        /**
         * Organization Routes
         */
        Route::get('/organization-create', [OrganizationController::class, 'create'])->name('admin.organization-create');
        Route::get('/getOrganizationPhones', [OrganizationController::class, 'getOrganizationPhones'])->name('admin.getOrganizationPhones');
        Route::post('/save-organization', [OrganizationController::class, 'store'])->name('admin.save-organization');
        Route::post('/deleteOrganizationPhone', [App\Http\Controllers\OrganizationController::class, 'destroy'])->name('deleteOrganizationPhone');
        Route::resource('clients-uploads', \App\Http\Controllers\ClientsImportController::class);


        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
        Route::post('/update_user_password', [App\Http\Controllers\Employee::class, 'update_user_password'])->name('update_user_password');
        Route::post('/update_user_profile_pic', [App\Http\Controllers\Employee::class, 'update_user_profile_pic'])->name('update_user_profile_pic');

        // // User Profile
        Route::get('/Profile/{id}', [Employee::class, 'edit_profile'])->name('Profile');
        ;
        //login logs 
        Route::get('/loginLogs', [Employee::class, 'loginLogs'])->name('admin.loginLogs');
        Route::get('/search-logs', [Employee::class, 'getLogs'])->name('get-logs');

        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/EmployeesList', [App\Http\Controllers\Auth\RegisterController::class, 'EmployeesList'])->name('EmployeesList');
        Route::post('/UploadUserImage', [App\Http\Controllers\Auth\RegisterController::class, 'uploadUserImage'])->name('uploadUserImage');
        Route::get('/Employee/{id}', [App\Http\Controllers\Employee::class, 'getEmployeeInfo'])->name('Employee');
        Route::get('/create_employee/{id?}', [App\Http\Controllers\Employee::class, 'employee_create'])->name('employee_create_page');
        Route::post('/UpdateEmployee/{id}', [App\Http\Controllers\Employee::class, 'UpdateEmployee'])->name('UpdateEmployee');
        Route::post('/ChangeEmpStatus', [App\Http\Controllers\Auth\RegisterController::class, 'ChangeEmpStatus'])->name('ChangeEmpStatus');

        //<--Manage Settings Routes--->
        Route::get('/manage_settings', [SettingsController::class, 'manage_settings'])->name('manage_settings');
        Route::get('/GetDesignationsData', [SettingsController::class, 'GetDesignationsData'])->name('GetDesignationsData');
        Route::get('/GetDepartmentData', [SettingsController::class, 'GetDepartmentData'])->name('GetDepartmentData');
        Route::get('/GetLanguageData', [SettingsController::class, 'GetLanguageData'])->name('GetLanguageData');
        Route::get('/GetDesignation/{id}', [SettingsController::class, 'GetDesignation'])->name('GetDesignation');
        Route::get('/GetBranchData/{id?}', [SettingsController::class, 'GetBranchData'])->name('GetBranchData');
        Route::get('/GetBranchData/{id?}', [SettingsController::class, 'GetBranchData'])->name('GetBranchData');
        Route::get('/GetDocumentTypes/{id?}', [SettingsController::class, 'GetDocumentTypes'])->name('GetDocumentTypes');
        Route::get('/getLanguage/{id}', [SettingsController::class, 'getLanguage'])->name('getLanguage');
        Route::post('/save_settings', [SettingsController::class, 'save_settings'])->name('save_settings');
        Route::post('/upate-language-status', [SettingsController::class, 'updateLanguageStatus'])->name('update-language-status');
        Route::post('/upate-setting-status', [SettingsController::class, 'updateStatus'])->name('upate-setting-status');
        //<<--- Designation Access Rights -->
        Route::resource('/DesignationAccessRights', DesignationAccessRightsController::class);
        Route::get('/list-designation-rights', [DesignationAccessRightsController::class, 'listAllRights']);
        Route::get('/revoke-designation-acc-right/{empId}', [DesignationAccessRightsController::class, 'revokeAccRight']);
        Route::get('/update-module-for-in-controller', [Admin::class, 'updateModuleForInController'])->name('update-module-for-in-controller');
        //<<--- Site Settings -->
        Route::get('/Admin', [App\Http\Controllers\Admin::class, 'index'])->name('Admin');
        Route::post('/delete_from_settings', [SettingsController::class, 'delete_from_settings'])->name('delete_from_settings');
        Route::post('/Admin/SaveSubMod', [App\Http\Controllers\Admin::class, 'SaveSubMod'])->name('SaveSubMod');
        Route::post('/Admin/DeleteSubNavItem', [App\Http\Controllers\Admin::class, 'DeleteSubNavItem'])->name('DeleteSubNavItem');
        Route::post('/Admin/UpdateSubModPriority', [App\Http\Controllers\Admin::class, 'UpdateSubModPriority'])->name('UpdateSubModPriority');
        Route::post('/Admin/UpdateParentMod', [App\Http\Controllers\Admin::class, 'UpdateParentMod'])->name('UpdateParentMod');
        Route::post('/Admin/UpdateParentModPriority', [App\Http\Controllers\Admin::class, 'UpdateParentModPriority'])->name('UpdateParentModPriority');
        Route::post('/Admin/SaveParentMod', [App\Http\Controllers\Admin::class, 'SaveParentMod'])->name('SaveParentMod');
        Route::post('/Admin/DeleteParentMod', [App\Http\Controllers\Admin::class, 'DeleteParentMod'])->name('DeleteParentMod');

        //GeographicalSettings
        Route::get('/geographicalsetting', [GeographicalSettingsController::class, 'geographicalsetting'])->name('geographicalsetting');
        Route::get('/GetGeoData', [GeographicalSettingsController::class, 'GetGeoData'])->name('GetGeoData');
        Route::get('/GetStatesagianstCountry/{id}', [GeographicalSettingsController::class, 'GetStatesagianstCountry'])->name('GetStatesagianstCountry');
        Route::get('/GetCitiesagianstStates/{id}', [GeographicalSettingsController::class, 'GetCitiesagianstStates'])->name('GetCitiesagianstStates');
        Route::get('/GetCitiesagianstStatesforPostal/{id}', [GeographicalSettingsController::class, 'GetCitiesagianstStatesforPostal'])->name('GetCitiesagianstStatesforPostal');
        Route::get('/GetStatesagianstCountryforPostal/{id}', [GeographicalSettingsController::class, 'GetStatesagianstCountryforPostal'])->name('GetStatesagianstCountryforPostal');
        Route::post('/save_country', [GeographicalSettingsController::class, 'save_country'])->name('save_country');
        Route::get('/GetCountry/{id}', [GeographicalSettingsController::class, 'GetCountry'])->name('GetCountry');
        Route::post('/delete_geographical', [GeographicalSettingsController::class, 'delete_geographical'])->name('delete_geographical');
        Route::get('/GetState/{id}', [GeographicalSettingsController::class, 'GetState'])->name('GetState');
        Route::get('/GetCity/{id}', [GeographicalSettingsController::class, 'GetCity'])->name('GetCity');
        Route::get('/GetPostalCode/{id}', [GeographicalSettingsController::class, 'GetPostalCode'])->name('GetPostalCode');
        Route::get('/GetCitiesforPostal/{id}', [GeographicalSettingsController::class, 'GetCitiesforPostal'])->name('GetCitiesforPostal');
        Route::get('/get-cities-against-state/{id}', [GeographicalSettingsController::class, 'get_cities_against_state'])->name('get-cities-against-state');
        Route::get('/get-postal-code-against-city/{id}', [GeographicalSettingsController::class, 'get_postal_code_against_city'])->name('get-postal-code-against-city');
        Route::get('/get-postal-code-against-cities/{id}', [GeographicalSettingsController::class, 'get_postal_code_against_city'])->name('get-postal-code-against-cities');
        Route::get('/get-states-cities', [GeographicalSettingsController::class, 'getStatesCities'])->name('get-states-cities');
        Route::get('/get-states-cities-postals', [GeographicalSettingsController::class, 'getStatesCitiesPostals'])->name('get-states-cities-postals');
        Route::get('/get-searched-cities', [GeographicalSettingsController::class, 'getSearchedCities'])->name('get-searched-cities');

        //client routes

        Route::post('/save-client', [ClientsController::class, 'store'])->name('save-client');
        Route::post('/delete-client', [ClientsController::class, 'destroy'])->name('delete-client');
        Route::post('/verify-client', [ClientsController::class, 'verifyClient'])->name('verify-client');
        Route::get('/clients', [ClientsController::class, 'index'])->name('clients');
        Route::get('/getClients', [ClientsController::class, 'getClients'])->name('getClients');

        //origins
        Route::get('/origins', [OriginController::class, 'index'])->name('origins');
        Route::post('/save-origin', [OriginController::class, 'store'])->name('save-origin');
        Route::post('/delete-origin', [OriginController::class, 'destroy'])->name('delete-origin');
        Route::get('/create-origin/{origin_id?}', [OriginController::class, 'create'])->name('create-origin');
        Route::get('/getOrigins', [OriginController::class, 'getOrigins'])->name('getOrigins');

        //freight rates
        Route::post('/save-rate', [FreightRateController::class, 'store'])->name('save-client');
        Route::post('/delete-rate', [FreightRateController::class, 'destroy'])->name('delete-client');
        Route::get('/freight-rates', [FreightRateController::class, 'index'])->name('freight-rates');
        Route::get('/getRates', [FreightRateController::class, 'getRates'])->name('getRates');

        //packages routes

        Route::get('/packages/{status?}', [PackageController::class, 'index'])->name('packages');
        Route::post('/save-package', [PackageController::class, 'store'])->name('save-package');
        Route::post('/delete-package', [PackageController::class, 'destroy'])->name('delete-package');
        Route::get('/create-package/{paclage_id?}', [PackageController::class, 'create'])->name('create-package');
        Route::get('/getPackages', [PackageController::class, 'getPackages'])->name('getPackages');
        Route::get('/getPackageClient/{suit}', [PackageController::class, 'getPackageClient'])->name('getPackageClient');
        Route::get('/package-print-label/{id}', [PackageController::class, 'printLabel'])->name('package-print-label');
        Route::get('/package-tracking', [PackageController::class, 'packageTracking'])->name('package-tracking');
        Route::post('/change-package-status', [PackageController::class, 'changeStatus'])->name('change-package-status');
        Route::post('/change-package-tracking-status', [PackageController::class, 'changeTrackingStatus'])->name('change-package-tracking-status');
        Route::get('/print-packages-label/{waybill?}', [PackageController::class, 'printPackageLabel'])->name('print-packages-label');
        Route::post('/change-package-payment-status', [PackageController::class, 'changePaymentStatus'])->name('change-package-payment-status');
        Route::post('/save-package-sidebar', [PackageController::class, 'saveSidebarPackage'])->name('save-package-sidebar');
  Route::get('/updateAllPackagesBarcode', [PackageController::class, 'updateAllPackagesBarcode'])->name('updateAllPackagesBarcode');
        //Email content management routes 
        Route::get('/verification-email', [EmailContentController::class, 'verification_email'])->name('verification-email');
        Route::get('/welcome-email', [EmailContentController::class, 'welcome_email'])->name('welcome-email');
        Route::get('/package-updates-email', [EmailContentController::class, 'status_change_email'])->name('package-updates-email');
                Route::get('/package-create-email', [EmailContentController::class, 'package_create'])->name('package-create-email');

        Route::post('/save-email-content', [EmailContentController::class, 'store'])->name('save-email-content');
        
        //translation crud 
        Route::get('/translation-management', [TranslationsController::class, 'index'])->name('translation-management');
        Route::get('/getTranslations', [TranslationsController::class, 'getTranslations'])->name('getTranslations');
        Route::post('/save-translation', [TranslationsController::class, 'store'])->name('update-translation');
        

        //reporting routes
        Route::get('/reporting', [ReportingController::class, 'reporting'])->name('admin.reporting');
        Route::get('/generate-report', [ReportingController::class, 'generate_report'])->name('admin.generate-report');
        
        
    });
    Route::get('/admin/export-packages-excel', [ReportingController::class, 'exportExcel'])->name('admin.export-packages-excel');
    Route::get('/waybill/{waybill}', [PackageController::class, 'waybill'])->name('waybill-detail');


    //clients routes
    Route::get('/customer-login', [App\Http\Controllers\Auth\ClientsLoginController::class, 'showLoginForm'])->name('customer-login');
    Route::get('/customer-register', [App\Http\Controllers\Auth\ClientsLoginController::class, 'showCustomerForm'])->name('customer.register');
    Route::post('/customer-submit', [App\Http\Controllers\Auth\ClientsLoginController::class, 'store'])->name('customer.submitSignUp');
    Route::post('/customer-mylogin', [App\Http\Controllers\Auth\ClientsLoginController::class, 'login'])->name('customer-mylogin');
    Route::get('/customer-logout', [App\Http\Controllers\Auth\ClientsLoginController::class, 'logout'])->name('customer-logout');
    Route::get('/customer-index', [HomeController::class, 'customer_index'])->name('customer.index');
    Route::group(['middleware' => ['auth:clients', 'is_route_assigned', 'verified']], function () {
        Route::get('/customer-home', [HomeController::class, 'customer_index'])->name('customer.home');
        Route::get('/customer-packages', [PackageController::class, 'index'])->name('customer.packages');
        Route::get('/getCustomerPackages', [PackageController::class, 'getPackages'])->name('customer.packages-list');
        Route::get('/customer-profile/{id}', [Employee::class, 'edit_profile'])->name('customer.profile');
        Route::post('/save-customer-package', [PackageController::class, 'store'])->name('save--customer-package');
        Route::get('/create-customer-package/{paclage_id?}', [PackageController::class, 'create'])->name('create-customer-package');
        Route::get('/print-customer-packages-label/{id?}', [PackageController::class, 'printCustomerPackage'])->name('print-customer-packages-label');


    });
    Route::post('/customer-password-update', [ClientsLoginController::class, 'update_user_password'])->name('customer.update-password-user');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        // Ensure the user is authenticated via the 'clients' guard
       
    
        // Fulfill the email verification request
        $request->fulfill();
    
        // Redirect to the customer home with a success message
        return redirect()->route('customer.home')->with('message', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');
    Route::group(['middleware' => ['auth:clients']], function () {
        Route::get('/email/verify', function () {
            if(Auth::user()->email_verified_at == null){ 
                return view('client_auth.verify-email');
            }else{
                return redirect()->route('customer.home');
            }
                  
        })->name('verification.notice');
        
        Route::post('/email/verification-notification', function (Request $request) {
            // $request->user()->sendEmailVerificationNotification();
            if(Auth::user()->email_verified_at == null){
              function generateVerificationUrl($user)
                {
                    return URL::temporarySignedRoute(
                        'verification.verify',
                        Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                        ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
                    );
                }
                $subject        = emailContentSettings('verification')->subject ?? 'Email Verifications Required';
                $headerContent  = emailContentSettings('verification')->header_text ?? 'Welcome, {{ client_name }}!';
                $bodyText       = emailContentSettings('verification')->body_text;
                $footerText     = emailFooterText(emailContentSettings('verification')->footer_text ?? null); 
                    $loginUser = Auth::user();
                   $placeholders = [
                                    '{{ first_name }}' => $loginUser->first_name,
                                    '{{ email }}' => $loginUser->email,
                                    '{{ suite }}' => $loginUser->suite,
                                ]; 
                $headerContent  = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
                $bodyText       = str_replace(array_keys($placeholders), array_values($placeholders), $bodyText);
                $footerText     = str_replace(array_keys($placeholders), array_values($placeholders), $footerText);
                
            
                $verificationUrl = generateVerificationUrl($loginUser);
                $htmlContent     = view('client_auth.verification-email-temp', [
                                        'headerContent' => $headerContent,    
                                        'bodyContent'   => $bodyText,    
                                        'footerContent' => $footerText,   
                                         'url'          => $verificationUrl 
                                    ])->render(); 
                $loginUser      = Auth::user();
                SendInBlue($loginUser->email,$loginUser->first_name,$subject,$htmlContent);
                    return back()->with('message', 'Verification link sent!');
            } else{
                return redirect()->route('customer.home');
            }
                 
      
        })->middleware(['throttle:6,1'])->name('verification.send');
    });

    // Route::get('/profile', [App\Http\Controllers\admin\HomeController::class, 'investorProfile'])->name('investor.profile');
    // Route::post('/update-customer-password', [App\Http\Controllers\admin\HomeController::class, 'changeInvestorPassword'])->name('update-investor-password');



});


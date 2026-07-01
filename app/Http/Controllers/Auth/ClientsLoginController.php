<?php

namespace App\Http\Controllers\Auth;

use App;
use App\Country;
use App\Models\Branches;
use App\Models\ClientsModel;
use App\Models\DocumentTypes;
use App\State;
use Auth;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use URL;

class ClientsLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/customer-home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {


    }
    protected function redirectPath()
    {
        if (FacadesAuth::guard('clients')->check()) {
            return '/customer-home';
        }

        return '/admin/home'; // Fallback if not logged in as a client
    }
    public function update_user_password(Request $request)
    {

        $employee = ClientsModel::find(GetActiveGuardDetail()->id);
        $hashedPassword = $employee->password;

        if ($request->current_password) {
            if (Hash::check($request->current_password, $hashedPassword)) {
                $employee->password = bcrypt($request->confirm_password);
                if ($employee->save()) {
                    echo json_encode("success");
                    die;
                } else {
                    echo json_encode("failed");
                    die;
                }
            } else {
                echo json_encode('not_match');
                die;
            }
        } else {
            echo json_encode("empty");
            die;
        }
    }
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email|max:255|same:confirm_email',
            'confirm_email' => 'required|email',
            'phone' => 'required|string|max:20',
            'country' => 'required|integer|exists:countries,id',
            'state' => 'required|integer|exists:states,id',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:6|same:confirm_password',
            'confirm_password' => 'required|string|min:6',
            'document_type' => 'required|integer|exists:documents_types,id',
            'document_number' => 'required|string|max:255',
            'branch' => 'required|integer|exists:branches,id',
            'client_type' => 'required|string|in:person,company',
            'postal_code' => 'nullable',
        ], [
            'email.unique' => 'The email has already been taken.',
            'password.same' => 'The password and confirm password must match.',
            'email.same' => 'The email and confirm email must match.',
            'document_type.exists' => 'The selected document type is invalid.',
            'branch.exists' => 'The selected branch is invalid.',
            'country.exists' => 'The selected country is invalid.',
            'state.exists' => 'The selected State is invalid.',
        ]);

        // Prepare data for client creation
        $validatedData['document_type_id'] = $validatedData['document_type'];
        $validatedData['branch_id'] = $validatedData['branch'];
        $validatedData['country_id'] = $validatedData['country'];
        $validatedData['state_id'] = $validatedData['state'];
        $validatedData['password'] = Hash::make($validatedData['password']);
        unset($validatedData['confirm_password']);
        unset($validatedData['document_type']);
        unset($validatedData['branch']);
        unset($validatedData['state']);
        unset($validatedData['country']);
        unset($validatedData['confirm_email']);

        // Get branch and create a unique suite ID
        $branch = Branches::findOrFail($validatedData['branch_id']);
        $branch_name = $branch->branch;
        $mBranch = strtoupper(substr($branch_name, 0, 2));
        $lastClient = ClientsModel::orderBy('id', 'desc')->first();
        $nextClientId = $lastClient ? $lastClient->id + 1 : 1;
        $validatedData['suite'] = 'COMM' . $mBranch . $nextClientId;
        $client = ClientsModel::create($validatedData);
        Auth::guard('clients')->login($client);
        function generateVerificationUrl($user)
        {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
            );
        }
        $subject = emailContentSettings('verification')->subject ?? 'Email Verifications Required';
        $headerContent = emailContentSettings('verification')->header_text ?? 'Welcome, {{ client_name }}!';
        $bodyText = emailContentSettings('verification')->body_text;
        $footerText = emailContentSettings('verification')->footer_text ?? 'Best regards, The WWC Team';
        $loginUser = Auth::guard('clients')->user();
        $placeholders = [
            '{{ first_name }}' => GetActiveGuardDetail()->first_name,
            '{{ email }}' => GetActiveGuardDetail()->email,
            '{{ suite }}' => GetActiveGuardDetail()->suite,
        ];
        $headerContent = str_replace(array_keys($placeholders), array_values($placeholders), $headerContent);
        $bodyText = str_replace(array_keys($placeholders), array_values($placeholders), $bodyText);
        $footerText = str_replace(array_keys($placeholders), array_values($placeholders), $footerText);


        $verificationUrl = generateVerificationUrl($loginUser);
        $htmlContent = view('client_auth.verification-email-temp', [
            'headerContent' => $headerContent,
            'bodyContent' => $bodyText,
            'footerContent' => $footerText,
            'url' => $verificationUrl
        ])->render();
        $loginUser = Auth::guard('clients')->user();
        SendInBlue($loginUser->email, $loginUser->first_name, $subject, $htmlContent);

        return redirect()->route('customer.home')->with('success', 'Client created successfully!');

    }

    public function showLoginForm()
    {
        App::setLocale('es');
        return view('client_auth.login');
    }
    public function showCustomerForm()
    {
        if (FacadesAuth::guard('clients')->check()) {
            return redirect()->route('customer.home');
        }
        FacadesAuth::guard('web')->logout();
        FacadesAuth::guard('clients')->logout();
        $branches = Branches::all();
        $document_types = DocumentTypes::all();
        $countries = Country::all();
        $states = State::all();
        return view('client_auth.register', compact('branches', 'document_types', 'countries', 'states'));
    }

    public function login(Request $request)
    {
        FacadesAuth::guard('web')->logout();
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');

        $client = FacadesAuth::guard('clients');

        if ($client->attempt($credentials)) {

            return redirect()->route('customer.home');
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function change_password()
    {
        return view('client_auth.change_password');
    }

    public function ChangeUserPassword(Request $request)
    {
        $user_data = DB::table('clients')->whereRaw('email = "' . $request->pass_email . '"')->first();
        if (Hash::check($request->old_pass, $user_data->password)) {
            if (Hash::check($request->new_password, $user_data->password)) {
                echo json_encode(101);
            } else {
                $update = DB::table('clients')->whereRaw('email = "' . $request->pass_email . '"')->update([
                    'password' => bcrypt($request->new_password),
                    'password_changed' => 1
                ]);
                if ($update) {
                    echo json_encode(200);
                } else {
                    echo json_encode(202);
                }
            }
        } else {
            echo json_encode(201);
        }
    }

    //    public function __construct()
    //    {
    //        $this->middleware('guest')->except('logout');
    //    }

    public function logout(Request $request)
    {
        FacadesAuth::guard('clients')->logout();
        Session::flush();
        return redirect()->route('customer-login');
    }

    public function username()
    {
        return 'email';
    }
}

<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\SessionKey;
use App\Enums\ViewPaths\Vendor\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\LoginRequest;
use App\Repositories\VendorWalletRepository;
use App\Services\VendorService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param VendorRepositoryInterface $vendorRepo
     * @param VendorService $vendorService
     * @param VendorWalletRepository $vendorWalletRepo
     */
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepo,
        private readonly VendorService             $vendorService,
        private readonly VendorWalletRepository    $vendorWalletRepo,
    )
    {
        // Middleware to ensure that only guests (not logged-in users) can access methods except 'logout'
        $this->middleware('guest:seller', ['except' => ['logout']]);
    }

    /**
     * Show the login view.
     *
     * @return View
     */
    public function getLoginView(): View
    {
        // Return the view for the vendor login page
        return view(Auth::VENDOR_LOGIN[VIEW]);
    }

    /**
     * Handle the login request.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Retrieve the vendor using the email from the request
        $vendor = $this->vendorRepo->getFirstWhere(['identity' => $request['email']]);
        
        // If the vendor does not exist, return an error response
        if (!$vendor) {
            return response()->json(['error' => translate('credentials_doesnt_match') . '!']);
        }

        // Verify the password provided in the request against the stored hashed password
        $passwordCheck = Hash::check($request['password'], $vendor['password']);
        
        // If the password is correct but the vendor status is not 'approved', return the status
        if ($passwordCheck && $vendor['status'] !== 'approved') {
            return response()->json(['status' => $vendor['status']]);
        }

        // Attempt to log in the vendor using the provided credentials and remember token
        if ($this->vendorService->isLoginSuccessful($request->email, $request->password, $request->remember)) {
            // Check if the vendor has an associated wallet; if not, create it
            if ($this->vendorWalletRepo->getFirstWhere(params: ['id' => auth('seller')->id()]) === false) {
                $this->vendorWalletRepo->add($this->vendorService->getInitialWalletData(vendorId: auth('seller')->id()));
            }
            
            // Display a success message and redirect to the vendor dashboard
            Toastr::info(translate('welcome_to_your_dashboard') . '.');
            return response()->json([
                'success' => translate('login_successful') . '!',
                'redirectRoute' => route('vendor.dashboard.index'),
            ]);
        } else {
            // If the login attempt is not successful, return an error response
            return response()->json(['error' => translate('credentials_doesnt_match') . '!']);
        }
    }

    /**
     * Handle vendor logout.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        // Perform logout action using the VendorService
        $this->vendorService->logout();
        
        // Display a success message and redirect to the login page
        Toastr::success(translate('logged_out_successfully') . '.');
        return redirect()->route('vendor.auth.login');
    }
}

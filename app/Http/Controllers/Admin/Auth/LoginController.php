<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Enums\UserRole;
use App\Enums\ViewPaths\Admin\Auth;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\Admin;
use App\Services\AdminService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    public function __construct(
        private readonly Admin $admin,
        private readonly AdminService $adminService
    ) {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    /**
     * Show the login view based on the user role.
     *
     * @param Request|null $request
     * @param string|null $type
     * @return View
     */
    public function index(?Request $request, string $type = null): View
    {
        $loginTypes = [
            UserRole::ADMIN => getWebConfig(name: 'admin_login_url'),
            UserRole::EMPLOYEE => getWebConfig(name: 'employee_login_url')
        ];

        $userType = array_search($type, $loginTypes);
        abort_if(!$userType, 404);

        return view(Auth::ADMIN_LOGIN)->with(['role' => $userType]);
    }

    /**
     * Handle the login request.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $admin = $this->admin->where('email', $request->email)->first();

        if ($admin && in_array($request->role, [UserRole::ADMIN, UserRole::EMPLOYEE]) && $admin->status) {
            if ($this->adminService->isLoginSuccessful($request->email, $request->password, $request->remember)) {
                return redirect()->route('admin.dashboard.index');
            }
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors([translate('credentials does not match or your account has been suspended')]);
    }

    /**
     * Log out the admin user and redirect to login page.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->adminService->logout();
        session()->flash('success', translate('logged out successfully'));
        return redirect('login/' . getWebConfig(name: 'admin_login_url'));
    }
}

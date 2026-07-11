<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPasswordController;
use App\Http\Controllers\CodeRequestController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
| Public client-facing site
*/
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/services', [PublicController::class, 'services'])->name('public.services');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('public.contact.submit');

/*
| Client portal (separate "customer" auth guard)
*/
Route::middleware('guest:customer')->group(function () {
    Route::get('/portal/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/portal/register', [CustomerAuthController::class, 'register']);
    Route::get('/portal/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/portal/login', [CustomerAuthController::class, 'login']);

    // Email OTP verification
    Route::get('/portal/verify', [CustomerAuthController::class, 'showOtp'])->name('customer.otp');
    Route::post('/portal/verify', [CustomerAuthController::class, 'verifyOtp'])->name('customer.otp.verify');
    Route::post('/portal/verify/resend', [CustomerAuthController::class, 'resendOtp'])->name('customer.otp.resend');

    // Forgot / reset password
    Route::get('/portal/forgot-password', [CustomerPasswordController::class, 'showLinkRequestForm'])->name('customer.password.request');
    Route::post('/portal/forgot-password', [CustomerPasswordController::class, 'sendResetLink'])->name('customer.password.email');
    Route::get('/portal/reset-password/{token}', [CustomerPasswordController::class, 'showResetForm'])->name('customer.password.reset');
    Route::post('/portal/reset-password', [CustomerPasswordController::class, 'reset'])->name('customer.password.update');
});

Route::middleware(['auth:customer', 'customer.active'])->group(function () {
    Route::post('/portal/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    Route::get('/portal/track', [PortalController::class, 'track'])->name('portal.track');
    Route::get('/portal/review', [PortalController::class, 'showReviewForm'])->name('portal.review');
    Route::post('/portal/review', [PortalController::class, 'submitReview'])->name('portal.review.submit');
    Route::get('/portal/request-code', [PortalController::class, 'showRequestCode'])->name('portal.request-code');
    Route::post('/portal/request-code', [PortalController::class, 'submitRequestCode'])->name('portal.request-code.submit');
    Route::get('/portal/{code}/print', [PortalController::class, 'printProject'])->name('portal.print.project');
    Route::get('/portal/{code}/invoice/{invoice}/print', [PortalController::class, 'printInvoice'])->name('portal.print.invoice');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Password reset (forgot password) flow.
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->middleware('perm:dashboard,view')->name('dashboard');

    // Clients
    Route::get('/clients', [AdminDashboardController::class, 'indexClients'])->middleware('perm:clients,view')->name('clients.index');
    Route::get('/clients/create', [AdminDashboardController::class, 'createClient'])->middleware('perm:clients,create')->name('clients.create');
    Route::post('/clients/create', [AdminDashboardController::class, 'storeClient'])->middleware('perm:clients,create');
    Route::get('/clients/{client}', [AdminDashboardController::class, 'showClient'])->middleware('perm:clients,view')->name('clients.show');
    Route::get('/clients/{client}/edit', [AdminDashboardController::class, 'editClient'])->middleware('perm:clients,edit')->name('clients.edit');
    Route::post('/clients/{client}/edit', [AdminDashboardController::class, 'updateClient'])->middleware('perm:clients,edit');
    Route::post('/clients/{client}/delete', [AdminDashboardController::class, 'deleteClient'])->middleware('perm:clients,delete')->name('clients.delete');

    // Projects
    Route::get('/projects', [AdminDashboardController::class, 'indexProjects'])->middleware('perm:projects,view')->name('projects.index');
    Route::get('/projects/create', [AdminDashboardController::class, 'createProject'])->middleware('perm:projects,create')->name('projects.create');
    Route::post('/projects/create', [AdminDashboardController::class, 'storeProject'])->middleware('perm:projects,create');
    Route::get('/projects/{project}', [AdminDashboardController::class, 'showProject'])->middleware('perm:projects,view')->name('projects.show');
    Route::get('/projects/{project}/edit', [AdminDashboardController::class, 'editProject'])->middleware('perm:projects,edit')->name('projects.edit');
    Route::post('/projects/{project}/edit', [AdminDashboardController::class, 'updateProject'])->middleware('perm:projects,edit');
    Route::post('/projects/{project}/delete', [AdminDashboardController::class, 'deleteProject'])->middleware('perm:projects,delete')->name('projects.delete');

    // Team & Projects (team accounting)
    Route::get('/team', fn () => redirect()->route('team.members.index'))->middleware('perm:team,view');
    // Team members
    Route::get('/team/members', [TeamController::class, 'membersIndex'])->middleware('perm:team,view')->name('team.members.index');
    Route::get('/team/members/create', [TeamController::class, 'memberCreate'])->middleware('perm:team,create')->name('team.members.create');
    Route::post('/team/members/create', [TeamController::class, 'memberStore'])->middleware('perm:team,create');
    Route::get('/team/members/{member}/edit', [TeamController::class, 'memberEdit'])->middleware('perm:team,edit')->name('team.members.edit');
    Route::post('/team/members/{member}/edit', [TeamController::class, 'memberUpdate'])->middleware('perm:team,edit');
    Route::post('/team/members/{member}/delete', [TeamController::class, 'memberDelete'])->middleware('perm:team,delete')->name('team.members.delete');
    // Per-member payment summary (printable + Excel)
    Route::get('/team/members/{member}/summary', [TeamController::class, 'memberSummary'])->middleware('perm:team,view')->name('team.members.summary');
    Route::get('/team/members/{member}/summary/excel', [TeamController::class, 'memberSummaryExcel'])->middleware('perm:team,view')->name('team.members.summary.excel');
    // Team projects (assignment + payments)
    Route::get('/team/projects', [TeamController::class, 'projectsIndex'])->middleware('perm:team,view')->name('team.projects.index');
    Route::get('/team/projects/{project}/summary', [TeamController::class, 'summary'])->middleware('perm:team,view')->name('team.projects.summary');
    Route::get('/team/projects/{project}/summary/excel', [TeamController::class, 'summaryExcel'])->middleware('perm:team,view')->name('team.projects.summary.excel');
    Route::get('/team/projects/{project}', [TeamController::class, 'projectShow'])->middleware('perm:team,view')->name('team.projects.show');
    Route::post('/team/projects/{project}/assign', [TeamController::class, 'assignMembers'])->middleware('perm:team,edit')->name('team.projects.assign');
    // Payments to the team
    Route::post('/team/projects/{project}/pay', [TeamController::class, 'payStore'])->middleware('perm:team,edit')->name('team.payments.store');
    Route::post('/team/projects/{project}/pay/{payment}/edit', [TeamController::class, 'payUpdate'])->middleware('perm:team,edit')->name('team.payments.update');
    Route::post('/team/projects/{project}/pay/{payment}/delete', [TeamController::class, 'payDelete'])->middleware('perm:team,delete')->name('team.payments.delete');

    // Banks
    Route::get('/banks', [AdminDashboardController::class, 'indexBanks'])->middleware('perm:banks,view')->name('banks.index');
    Route::get('/banks/create', [AdminDashboardController::class, 'createBank'])->middleware('perm:banks,create')->name('banks.create');
    Route::post('/banks/create', [AdminDashboardController::class, 'storeBank'])->middleware('perm:banks,create');
    Route::get('/banks/{bank}', [AdminDashboardController::class, 'showBank'])->middleware('perm:banks,view')->name('banks.show');
    Route::get('/banks/{bank}/edit', [AdminDashboardController::class, 'editBank'])->middleware('perm:banks,edit')->name('banks.edit');
    Route::post('/banks/{bank}/edit', [AdminDashboardController::class, 'updateBank'])->middleware('perm:banks,edit');
    Route::post('/banks/{bank}/delete', [AdminDashboardController::class, 'deleteBank'])->middleware('perm:banks,delete')->name('banks.delete');

    // Invoices
    Route::get('/invoices/create', [AdminDashboardController::class, 'createInvoice'])->middleware('perm:invoices,create')->name('invoices.create');
    Route::post('/invoices/create', [AdminDashboardController::class, 'storeInvoice'])->middleware('perm:invoices,create');
    Route::get('/invoices', [AdminDashboardController::class, 'invoiceList'])->middleware('perm:invoices,view')->name('invoices.index');
    Route::get('/invoices/{invoice}', [AdminDashboardController::class, 'showInvoice'])->middleware('perm:invoices,view')->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [AdminDashboardController::class, 'editInvoice'])->middleware('perm:invoices,edit')->name('invoices.edit');
    Route::post('/invoices/{invoice}/edit', [AdminDashboardController::class, 'updateInvoice'])->middleware('perm:invoices,edit');
    Route::post('/invoices/{invoice}/delete', [AdminDashboardController::class, 'deleteInvoice'])->middleware('perm:invoices,delete')->name('invoices.delete');
    Route::get('/invoices/{id}/pdf', [AdminDashboardController::class, 'downloadInvoicePdf'])->middleware('perm:invoices,view')->name('invoices.pdf');

    // Fully paid
    Route::get('/fully-paid', [AdminDashboardController::class, 'fullyPaidIndex'])->middleware('perm:fully_paid,view')->name('fully-paid.index');
    Route::get('/fully-paid/pdf', [AdminDashboardController::class, 'fullyPaidPdf'])->middleware('perm:fully_paid,view')->name('fully-paid.pdf');
    Route::get('/fully-paid/excel', [AdminDashboardController::class, 'fullyPaidExcel'])->middleware('perm:fully_paid,view')->name('fully-paid.excel');

    // Roles management
    Route::get('/roles', [RoleController::class, 'index'])->middleware('perm:users,view')->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('perm:users,create')->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('perm:users,create')->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('perm:users,edit')->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('perm:users,edit')->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('perm:users,delete')->name('roles.destroy');

    // Portal customers management
    Route::get('/customers', [CustomerController::class, 'index'])->middleware('perm:customers,view')->name('customers.index');
    Route::post('/customers/{customer}/status', [CustomerController::class, 'setStatus'])->middleware('perm:customers,edit')->name('customers.status');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->middleware('perm:customers,delete')->name('customers.destroy');

    // Reviews management
    Route::get('/reviews', [ReviewController::class, 'index'])->middleware('perm:reviews,view')->name('reviews.index');
    Route::post('/reviews/{review}/toggle', [ReviewController::class, 'toggle'])->middleware('perm:reviews,edit')->name('reviews.toggle');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->middleware('perm:reviews,delete')->name('reviews.destroy');

    // Contact messages
    Route::get('/messages', [ContactMessageController::class, 'index'])->middleware('perm:contacts,view')->name('contacts.index');
    Route::post('/messages/{message}/read', [ContactMessageController::class, 'toggleRead'])->middleware('perm:contacts,view')->name('contacts.read');
    Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->middleware('perm:contacts,delete')->name('contacts.destroy');

    // History / activity log
    Route::get('/history', [HistoryController::class, 'index'])->middleware('perm:history,view')->name('history.index');
    Route::delete('/history/clear', [HistoryController::class, 'clear'])->middleware('perm:history,delete')->name('history.clear');

    // Tracking code resend requests
    Route::get('/code-requests', [CodeRequestController::class, 'index'])->middleware('perm:code_requests,view')->name('code-requests.index');
    Route::post('/code-requests/{codeRequest}/send', [CodeRequestController::class, 'send'])->middleware('perm:code_requests,edit')->name('code-requests.send');
    Route::delete('/code-requests/{codeRequest}', [CodeRequestController::class, 'destroy'])->middleware('perm:code_requests,delete')->name('code-requests.destroy');

    // Users management
    Route::get('/users', [UserController::class, 'index'])->middleware('perm:users,view')->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('perm:users,create')->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('perm:users,create')->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('perm:users,edit')->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('perm:users,edit')->name('users.update');
    Route::post('/users/{user}/generate-password', [UserController::class, 'generatePassword'])->middleware('perm:users,edit')->name('users.generate-password');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('perm:users,delete')->name('users.destroy');

    // Notifications (available to any logged-in admin)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/feed', [NotificationController::class, 'feed'])->name('notifications.feed');
    Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');

    // Settings — account is self-service; company settings gated inside the controller/views.
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/company', [SettingController::class, 'updateCompany'])->middleware('perm:settings,edit')->name('settings.company');
    Route::post('/settings/account', [SettingController::class, 'updateAccount'])->name('settings.account');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

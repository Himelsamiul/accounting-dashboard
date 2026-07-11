<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/clients', [AdminDashboardController::class, 'indexClients'])->name('clients.index');
    Route::get('/clients/create', [AdminDashboardController::class, 'createClient'])->name('clients.create');
    Route::post('/clients/create', [AdminDashboardController::class, 'storeClient']);
    Route::get('/clients/{client}', [AdminDashboardController::class, 'showClient'])->name('clients.show');
    Route::get('/clients/{client}/edit', [AdminDashboardController::class, 'editClient'])->name('clients.edit');
    Route::post('/clients/{client}/edit', [AdminDashboardController::class, 'updateClient']);
    Route::post('/clients/{client}/delete', [AdminDashboardController::class, 'deleteClient'])->name('clients.delete');

    Route::get('/projects', [AdminDashboardController::class, 'indexProjects'])->name('projects.index');
    Route::get('/projects/create', [AdminDashboardController::class, 'createProject'])->name('projects.create');
    Route::post('/projects/create', [AdminDashboardController::class, 'storeProject']);
    Route::get('/projects/{project}', [AdminDashboardController::class, 'showProject'])->name('projects.show');
    Route::get('/projects/{project}/edit', [AdminDashboardController::class, 'editProject'])->name('projects.edit');
    Route::post('/projects/{project}/edit', [AdminDashboardController::class, 'updateProject']);
    Route::post('/projects/{project}/delete', [AdminDashboardController::class, 'deleteProject'])->name('projects.delete');

    Route::get('/banks', [AdminDashboardController::class, 'indexBanks'])->name('banks.index');
    Route::get('/banks/create', [AdminDashboardController::class, 'createBank'])->name('banks.create');
    Route::post('/banks/create', [AdminDashboardController::class, 'storeBank']);
    Route::get('/banks/{bank}', [AdminDashboardController::class, 'showBank'])->name('banks.show');
    Route::get('/banks/{bank}/edit', [AdminDashboardController::class, 'editBank'])->name('banks.edit');
    Route::post('/banks/{bank}/edit', [AdminDashboardController::class, 'updateBank']);
    Route::post('/banks/{bank}/delete', [AdminDashboardController::class, 'deleteBank'])->name('banks.delete');

    Route::get('/invoices/create', [AdminDashboardController::class, 'createInvoice'])->name('invoices.create');
    Route::post('/invoices/create', [AdminDashboardController::class, 'storeInvoice']);
    Route::get('/invoices', [AdminDashboardController::class, 'invoiceList'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [AdminDashboardController::class, 'showInvoice'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [AdminDashboardController::class, 'editInvoice'])->name('invoices.edit');
    Route::post('/invoices/{invoice}/edit', [AdminDashboardController::class, 'updateInvoice']);
    Route::post('/invoices/{invoice}/delete', [AdminDashboardController::class, 'deleteInvoice'])->name('invoices.delete');
    Route::get('/invoices/{id}/pdf', [AdminDashboardController::class, 'downloadInvoicePdf'])->name('invoices.pdf');

    Route::get('/fully-paid', [AdminDashboardController::class, 'fullyPaidIndex'])->name('fully-paid.index');
    Route::get('/fully-paid/pdf', [AdminDashboardController::class, 'fullyPaidPdf'])->name('fully-paid.pdf');
    Route::get('/fully-paid/excel', [AdminDashboardController::class, 'fullyPaidExcel'])->name('fully-paid.excel');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

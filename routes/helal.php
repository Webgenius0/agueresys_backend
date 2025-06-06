<?php

/**
 * Backend Admin Routes for Web Application
 *
 * This file contains all the routes for managing the admin panel, including routes for
 * dashboard, system Settings, profile Settings, daily tips, blogs, and natural care.
 * Routes are grouped under the 'admin' prefix and require authentication with the 'admin' middleware.
 */


use App\Http\Controllers\Web\Backend\CMS\HomePageController;
use App\Http\Controllers\Web\Backend\CMS\HomePageSocialLinkContainerController;
use App\Http\Controllers\Web\Backend\GodsController;
use App\Http\Controllers\Web\Backend\RoleController;
use App\Http\Controllers\Web\Backend\VoteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\ProfileController;
use App\Http\Controllers\Web\Backend\DynamicPageController;
use App\Http\Controllers\Web\Backend\SystemSettingController;


Route::middleware(['auth:web', 'role_check'])->prefix('admin')->group(function () {
  // Route for the admin dashboard
  // Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


  // Routes for managing guests
  Route::get('/', function () {
    return view('backend.layouts.dashboard.index');
  })->name('admin.dashboard');

  Route::get('settings-profile', [ProfileController::class, 'index'])->name('profile_settings.index');
  Route::post('settings-profile', [ProfileController::class, 'update'])->name('profile_settings.update');
  Route::get('settings-profile-password', [ProfileController::class, 'passwordChange'])->name('profile_settings.password_change');
  Route::post('settings-profile-password', [ProfileController::class, 'UpdatePassword'])->name('profile_settings.password');
  Route::get('settings-cookie', [SystemSettingController::class, 'getCookieText'])->name('system_settings.cookie_get');
  Route::post('settings-cookie', [SystemSettingController::class, 'updateCookieText'])->name('system_settings.cookie_update');

  // Route for system settings index
  Route::get('system-settings', [SystemSettingController::class, 'index'])->name('system_settings.index');

  // Route for updating system settings
  Route::post('system-settings-update', [SystemSettingController::class, 'update'])->name('system_settings.update');


  // Routes for DynamicPageController
  Route::resource('/dynamic-page', DynamicPageController::class)->names('dynamic_page');
  Route::post('/dynamic-page/status/{id}', [DynamicPageController::class, 'status'])->name('dynamic_page.status');


  // Route Home Page CMS
  Route::get('/home-page/banner/index', [HomePageController::class, 'index'])->name('cms.home_page.banner.index');
  Route::get('/home-page/banner', [HomePageController::class, 'create'])->name('cms.home_page.banner.create');
  Route::Post('/home-page/banner', [HomePageController::class, 'updateBanner'])->name('cms.home_page.banner.update_banner');

  // Route Social link
  Route::resource('/home-page/social-link/index', HomePageSocialLinkContainerController::class)->names('cms.home_page.social_link')->except('show');
  Route::post('/home-page/social-link/status/{id}', [HomePageSocialLinkContainerController::class, 'status'])->name('cms.home_page.social_link.status');

  // Route GodsController
  Route::resource('/gods', GodsController::class)->names('gods');
  Route::post('/gods/status/{id}', [GodsController::class, 'status'])->name('gods.status');
  Route::delete('/ability/destroy/{id}', [GodsController::class, 'deleteAbility'])->name('ability.destroy');

  // Route VoteController
  Route::get('/reset-role-votes', [VoteController::class, 'index'])->name('vote.index');
  Route::get('/reset-role-votes/reset', [VoteController::class, 'ResetRoleVotes'])->name('vote.reset_role_votes');
  Route::get('/reset-god-votes/reset', [VoteController::class, 'ResetSingleGodVotes'])->name('vote.reset_god_votes');
  Route::get('/reset-counter-votes/reset', [VoteController::class, 'ResetCounterVotes'])->name('vote.reset_counter_votes');

  // Route for RoleController
  Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
  Route::get('/roles/show/{roleId}', [RoleController::class, 'show'])->name('roles.show');
});


// Public route for dynamic pages accessible to all users
Route::get('/pages/{slug}', [DynamicPageController::class, 'showDaynamicPage'])->name('pages');

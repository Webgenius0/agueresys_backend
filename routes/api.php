<?php



use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\SocialLoginController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\V1\AnonymousUserController;
use App\Http\Controllers\API\V1\CMS\HomePageController;
use App\Http\Controllers\API\V1\GodsController;
use App\Http\Controllers\API\V1\GodsCounterController;
use App\Http\Controllers\API\V1\RoleController;
use App\Http\Controllers\API\V1\VoteController;
use Illuminate\Support\Facades\Route;



// Route::group(['middleware' => 'guest:api'], function ($router) {
//     //register
//     Route::post('register', [RegisterController::class, 'register']);
//     Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
//     Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
//     //login
//     Route::post('login', [LoginController::class, 'login']);
//     //forgot password
//     Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
//     Route::post('/verify-otp', [ResetPasswordController::class, 'VerifyOTP']);
//     Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);
//     //social login
//     Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);
// });

// Route::group(['middleware' => 'auth:api'], function ($router) {
//     Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
//     Route::post('/logout', [LogoutController::class, 'logout']);
//     Route::get('/me', [UserController::class, 'me']);
//     Route::post('/update-profile', [UserController::class, 'updateProfile']);
//     Route::post('/update-password', [UserController::class, 'changePassword']);
//     Route::delete('/delete-profile', [UserController::class, 'deleteProfile']);
// });

// cms part
Route::get('/cms/home-page/banner', [HomePageController::class, 'getHomeBanner']);
Route::get('/cms/social-link', [HomePageController::class, 'getSocialLinks']);
Route::get('/cms/system-info', [HomePageController::class, 'getSystemInfo']);
Route::get('/cms/cookie-text', [HomePageController::class, 'getCookieText']);

// dynamic page
Route::get("dynamic-pages", [HomePageController::class, "getDynamicPages"]);
Route::get("dynamic-pages/single/{slug}", [HomePageController::class, "showDaynamicPage"]);

Route::group(['middleware' => 'check_anonymous_user'], function ($router) {
    // Route::get('/anonymous-users', [AnonymousUserController::class, 'index']);
    // Route::post('/anonymous-users/single/{fingerprint}', [AnonymousUserController::class, 'show']);
    Route::get('/gods', [GodsController::class, 'index']);
    Route::get('/gods/single/{godSlug}', [GodsController::class, 'show']);
    Route::get('/gods/counter-picks/{godSlug}', [GodsCounterController::class, 'getGodsCounters']);
});
Route::post('/anonymous-users/store', [AnonymousUserController::class, 'store']);
// Route vote
Route::post('/anonymous-users/vote', [VoteController::class, 'store']);
Route::post('/anonymous-users/individual/vote', [VoteController::class, 'individualVoteStore']);
Route::post('/anonymous-users/counter-picks/vote', [GodsCounterController::class, 'store']);
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/single/{id}', [RoleController::class, 'show']);
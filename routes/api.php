<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\SocialLoginController;
use App\Http\Controllers\API\Auth\UserController;
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
<?php  

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\FollowupController;
use App\Http\Controllers\Api\ApprenticeController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserRegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'getUser']);

// Rutas para apprentice
Route::get('apprentices', [ApprenticeController::class,'index'])->name('api.apprentices.index');
Route::post('apprentices', [ApprenticeController::class,'store'])->name('api.apprentice.store');
Route::get('apprentices/{apprentice}', [ApprenticeController::class,'show'])->name('api.apprentice.show');
Route::put('apprentices/{apprentice}', [ApprenticeController::class,'update'])->name('api.apprentice.update');
Route::delete('apprentices/{apprentice}', [ApprenticeController::class,'destroy'])->name('api.apprentice.delete');

// Rutas para log
Route::get('logs', [LogController::class,'index'])->name('api.logs.index');
Route::post('logs', [LogController::class,'store'])->name('api.log.store');
Route::get('logs/{log}', [LogController::class,'show'])->name('api.log.show');
Route::put('logs/{log}', [LogController::class,'update'])->name('api.log.update');
Route::delete('logs/{log}', [LogController::class,'destroy'])->name('api.log.delete');

// Rutas para Companies
Route::get('companies', [CompanyController::class,'index'])->name('api.companies.index');
Route::post('companies', [CompanyController::class,'store'])->name('api.companies.store');
Route::get('companies/{company}', [CompanyController::class,'show'])->name('api.companies.show');
Route::put('companies/{company}', [CompanyController::class,'update'])->name('api.companies.update');
Route::delete('companies/{company}', [CompanyController::class,'destroy'])->name('api.companies.delete');

// Rutas para Messages
Route::get('messages', [MessageController::class,'index'])->name('api.messages.index');
Route::post('messages', [MessageController::class,'store'])->name('api.messages.store');
Route::get('messages/{message}', [MessageController::class,'show'])->name('api.messages.show');
Route::put('messages/{message}', [MessageController::class,'update'])->name('api.messages.update');
Route::delete('messages/{message}', [MessageController::class,'destroy'])->name('api.messages.delete');

// Rutas para role
Route::get('roles', [RoleController::class,'index'])->name('api.roles.index');
Route::post('roles', [RoleController::class,'store'])->name('api.role.store');
Route::get('roles/{role}', [RoleController::class,'show'])->name('api.role.show');
Route::put('roles/{role}', [RoleController::class,'update'])->name('api.role.update');
Route::delete('roles/{role}', [RoleController::class,'destroy'])->name('api.role.delete');

// Rutas para role
Route::get('trainers', [TrainerController::class,'index'])->name('api.trainers.index');
Route::post('trainers', [TrainerController::class,'store'])->name('api.trainer.store');
Route::get('trainers/{trainer}', [TrainerController::class,'show'])->name('api.trainer.show');
Route::put('trainers/{trainer}', [TrainerController::class,'update'])->name('api.trainer.update');
Route::delete('trainers/{trainer}', [TrainerController::class,'destroy'])->name('api.trainer.delete');


// Rutas para Followups
Route::get('followups', [FollowupController::class,'index'])->name('api.followups.index');
Route::post('followups', [FollowupController::class,'store'])->name('api.followup.store');
Route::get('followups/{followup}', [FollowupController::class,'show'])->name('api.followup.show');
Route::put('followups/{followup}', [FollowupController::class,'update'])->name('api.followup.update');
Route::delete('followups/{followup}', [FollowupController::class,'destroy'])->name('api.followup.delete');

// Rutas para Notifications
Route::get('notifications', [NotificationController::class,'index'])->name('api.notifications.index');
Route::post('notifications', [NotificationController::class,'store'])->name('api.notification.store');
Route::get('notifications/{notification}', [NotificationController::class,'show'])->name('api.notification.show');
Route::put('notifications/{notification}', [NotificationController::class,'update'])->name('api.notification.update');
Route::delete('notifications/{notification}', [NotificationController::class,'destroy'])->name('api.notification.delete');

// Rutas para User Register API
Route::get('user_registers', [UserRegisterController::class, 'index'])->name('api.user_registers.index');
Route::post('user_registers', [UserRegisterController::class, 'store'])->name('api.user_register.store');
Route::get('user_registers/{user_register}', [UserRegisterController::class, 'show'])->name('api.user_register.show');
Route::put('user_registers/{user_register}', [UserRegisterController::class, 'update'])->name('api.user_register.update');
Route::delete('user_registers/{user_register}', [UserRegisterController::class, 'destroy'])->name('api.user_register.delete');




// Rutas para Contracts
Route::get('contracts', [ContractController::class,'index'])->name('api.contracts.index');
Route::post('contracts', [ContractController::class,'store'])->name('api.contract.store');
Route::get('contracts/{contract}', [ContractController::class,'show'])->name('api.contract.show');
Route::put('contracts/{contract}', [ContractController::class,'update'])->name('api.contract.update');
Route::delete('contracts/{contract}', [ContractController::class,'destroy'])->name('api.contract.delete');

Route::get('/departamentos', [DepartmentController::class, 'index']);

Route::get('user_by_roles', action: [UserRegisterController::class, 'getUserRegistersByRoles']);
Route::get('user_by_roles_instructor', action: [UserRegisterController::class, 'getUserRegistersByRolesInstructor']);

Route::get('user_by_roles_aprendiz', action: [UserRegisterController::class, 'getUserRegistersByAprendiz']);
Route::get('get_trainer', action: [UserRegisterController::class, 'getTrainer']);

Route::post('/apprentices-asignar', [ApprenticeController::class, 'asignarInstructorAprendiz']);



Route::get('getCompany', action: [CompanyController::class, 'getCompany']);

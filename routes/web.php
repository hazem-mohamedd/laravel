 <?php
use App\Http\Controllers\DriveController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes([
   "verify"=>true,
]);



Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware("auth","verified")->group(function(){


    Route::prefix("user")->group(function (){

        Route::get("profile" , [UserController::class , 'profile'])->name("user.profile");

        Route::post("uploadImage" , [UserController::class , 'uploadImage'])->name("user.uploadImage");
    });



Route::prefix("drive")->group(function (){
 Route::get("/MyFiles",[DriveController::class , 'MyFiles'])->name("drive.MyFiles");
 Route::get("/publicFile",[DriveController::class , 'publicFile'])->name("drive.publicFile");
 Route::get("/allFile",[DriveController::class , 'allFile'])->name("drive.allFile")->middleware('admin');
 Route::get("/create" , [DriveController::class , 'create'])->name("drive.create");
 Route::post("/store" , [DriveController::class , 'store'])->name("drive.store");
 Route::get("/notFound",[DriveController::class , 'notFound'])->name("notFound");
 // Route With ID
 Route::get("/show/{id}" , [DriveController::class , 'show'])->name("drive.show");
 Route::get("/edit{id}" , [DriveController::class , 'edit'])->name("drive.edit");
 Route::post("/update/{id}" , [DriveController::class , 'update'])->name("drive.update");
 Route::get("/destroy/{id}" , [DriveController::class , 'destroy'])->name("drive.destroy");
 Route::get("download/{id}" , [DriveController::class , 'download'])->name("drive.download");
 Route::get("changeStatus/{id}" , [DriveController::class , 'changeStatus'])->name("drive.changeStatus");
});


});

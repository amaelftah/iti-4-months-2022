<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Models\User;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', function () {
//     $testName = 'ahmedasdasdasdd';
//     $books = ['first book', 'second book'];

//     return view('test', [
//         'name' => $testName,
//          'age' => 23,
//          'books' => $books,
//     ]);
// });

Route::get('posts', [PostController::class, 'index'])
->name('posts.index')
->middleware('auth');
Route::get('posts/create',[PostController::class, 'create'])
->name('posts.create')
->middleware(['auth']);
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// class Test
// {
//     protected $hello;

//     public function greeting () 
//     {

//     }
// }

// $test = new Test;

// $test->hello;
// $test['hello'];// thorws exception

// foreach($test as $item){ //thorws exception

// }

Route::get('test',function(){
    $user = User::find(1);

    dd($user->posts);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


use Laravel\Socialite\Facades\Socialite;
 
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('auth.github');
 
Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::where('email', $githubUser->email)->first();
    if($user) {
        $user->update([
            'name' => $githubUser->name,
        ]);
    } else {
        $user = User::create([
            'email' => $githubUser->email,
            'name' => $githubUser->name,
        ]);
    }
    // $user = User::updateOrCreate([
    //     'email' => $githubUser->email,
    // ], [
    //     'name' => $githubUser->name,
    //     'email' => $githubUser->email,
    //     'github_token' => $githubUser->token,
    //     'github_refresh_token' => $githubUser->refreshToken,
    // ]);
 
    Auth::login($user);
 
    return redirect('/dashboard');
    dd($user);
    // $user->token
});

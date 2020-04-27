    <?php

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

use App\Http\Controllers\PostController;


Route::get('/', 'Auth\LoginController@home');

// Cards
Route::get('cards', 'CardController@list');
Route::get('cards/{id}', 'CardController@show');

// API
Route::put('api/cards', 'CardController@create');
Route::delete('api/cards/{card_id}', 'CardController@delete');
Route::put('api/cards/{card_id}/', 'ItemController@create');
Route::post('api/item/{id}', 'ItemController@update');
Route::delete('api/item/{id}', 'ItemController@delete');

Route::put('/api/posts' , 'PostController@create');
Route::delete('api/posts/{id}', 'PostController@delete');

Route::put('/api/posts/{id}/comment', 'CommentController@create');
Route::delete('api/comments/{id}', 'CommentController@delete');
Route::put('api/comments/{id}/edit' , 'CommentController@update');

Route::put('/api/posts/{id}/like/{val}' , 'PostController@like');

Route::put('/api/chats/{id}/message', 'MessageController@create');



// Authentication

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');


//Feed
Route::get('/feed' , 'FeedController@show');

//Profile
Route::get('/users/me', 'ProfileController@show_me');
Route::get('/users/me/edit', 'ProfileController@show_me_edit');
Route::post('/users/me', 'ProfileController@edit');
Route::get('/users/{id}', 'ProfileController@show');

//About
Route::get('/about', 'AboutController@show');

//Group
Route::get('/groups/{id}', 'GroupController@show');
Route::put('/api/groups/{id}/posts', 'PostController@createInGroup');

//Event
Route::get('/events/{event_id}' , 'EventController@show');
Route::put('/api/events/{id}/posts', 'PostController@createInEvent');

//Chat
Route::get('/chats/{chat_id}', 'ChatController@show');
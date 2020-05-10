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

Route::put('/api/reports/{id}/accept' , 'ReportController@accept');
Route::put('/api/reports/{id}/decline' , 'ReportController@decline');

Route::put('/api/sendFriendRequest/{id}' , 'RegularUserController@sendRequest');
Route::put('/api/cancelFriendRequest/{id}' , 'RegularUserController@cancelRequest');
Route::put('/api/acceptFriendRequest/{id}' , 'RegularUserController@acceptRequest');
Route::put('/api/declineFriendRequest/{id}' , 'RegularUserController@declineRequest');
Route::put('/api/removeFriendRequest/{id}' , 'RegularUserController@removeFriend');



// Authentication

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('resetPass', 'Auth\ResetController@showReset');

Route::post('api/resetPass/email', 'Auth\ResetController@addEmail');
Route::post('api/resetPass/code', 'Auth\ResetController@checkCode');
Route::post('/resetPass', 'Auth\ResetController@reset');


//Feed
Route::get('/feed' , 'FeedController@show')->name('feed');

//Profile
Route::get('/users/me', 'ProfileController@show_me');
Route::get('/users/me/edit', 'ProfileController@show_me_edit');
Route::post('/users/me', 'ProfileController@edit');
Route::get('/users/{id}', 'ProfileController@show');
Route::get('/users/me/email', 'ProfileController@email');

//About
Route::get('/about', 'AboutController@show');

//Group
Route::get('/groups/create', 'GroupController@showCreateForm');
Route::post('/groups/create', 'GroupController@create');

Route::get('/groups/{id}', 'GroupController@show')->name('groups.show');
Route::put('/api/groups/{id}/posts', 'PostController@createInGroup');

Route::get('/groups/{group_id}/edit', 'GroupController@show_edit');
Route::post('/groups/{group_id}', 'GroupController@edit');

//Event
Route::get('/events/create', 'EventController@showCreateForm');
Route::post('/events/create', 'EventController@create');

Route::get('/events/{event_id}' , 'EventController@show')->name('events.show');
Route::put('/api/events/{id}/posts', 'PostController@createInEvent');

Route::get('/events/{event_id}/edit', 'EventController@show_edit');
Route::post('/events/{event_id}', 'EventController@edit');

//Chat
Route::get('/chats/{chat_id}', 'ChatController@show')->name('chats.show');
Route::get('/chats', 'ChatController@get_chat');

//Post
Route::get('/posts/{post_id}', 'PostController@show');
Route::get('/posts/{post_id}/edit', 'PostController@show_edit');
Route::post('/posts/{post_id}', 'PostController@edit');



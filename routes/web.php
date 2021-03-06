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


Route::get('/', 'Auth\LoginController@home')->name('home');


// API

Route::post('/api/posts' , 'PostController@create');
Route::delete('api/posts/{id}', 'PostController@delete');
Route::put('api/posts/{id}/archive', 'PostController@archive');
Route::put('api/posts/{id}/unarchive', 'PostController@unarchive');
Route::put('api/posts/{id}/report', 'PostController@report');
Route::put('/api/posts/{id}/comment', 'CommentController@create');
Route::delete('api/comments/{id}', 'CommentController@delete');
Route::put('api/comments/{id}/edit' , 'CommentController@update');
Route::put('api/comments/{id}/report' , 'CommentController@report');

Route::put('/api/users/{id}/report', 'RegularUserController@report');

Route::put('/api/posts/{id}/like/{val}' , 'PostController@like');
Route::put('/api/comments/{id}/like/{val}' , 'CommentController@like');

Route::put('/api/chats/{id}/message', 'MessageController@create');
Route::put('/api/chats/{id}/clear', 'ChatController@clear');

Route::put('/api/reports/{id}/accept' , 'ReportController@accept');
Route::put('/api/reports/{id}/decline' , 'ReportController@decline');

Route::put('/api/orgApproval/{id}/accept', 'OrgApprovalController@accept');
Route::put('/api/orgApproval/{id}/decline', 'OrgApprovalController@decline');

Route::put('/api/sendFriendRequest/{id}' , 'RegularUserController@sendRequest');
Route::put('/api/cancelFriendRequest/{id}' , 'RegularUserController@cancelRequest');
Route::put('/api/acceptFriendRequest/{id}' , 'RegularUserController@acceptRequest');
Route::put('/api/declineFriendRequest/{id}' , 'RegularUserController@declineRequest');
Route::put('/api/removeFriendRequest/{id}' , 'RegularUserController@removeFriend');


Route::put('/api/users/{id}/orgVerify', 'RegularUserController@orgVerify');

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
Route::get('/admin' , 'FeedController@show_admin_feed')->name('admin');

Route::get('/search' , 'FeedController@search');
Route::get('/users/search' , 'FeedController@searchUsers');
Route::get('/groups/search' , 'FeedController@searchGroups');
Route::get('/events/search' , 'FeedController@searchEvents');

//Profile
Route::get('/users/me', 'ProfileController@show_me');
Route::get('/users/me/edit', 'ProfileController@show_me_edit');
Route::post('/users/me', 'ProfileController@edit');
Route::get('/users/{id}', 'ProfileController@show');
Route::get('/users/me/email', 'ProfileController@email');
Route::delete('/api/users/{teacher_id}/appointments/{time_id}', 'ProfileController@deleteAppointment');
Route::put('/api/users/{teacher_id}/appointments/{time_id}', 'ProfileController@addAppointment');
Route::put('/api/users/{id}/apply', 'RegularUserController@applyToOrg');
Route::put('/api/users/{id}/reject', 'RegularUserController@rejectApplication');
Route::put('/api/users/{id}/accept', 'RegularUserController@acceptApplication');
Route::delete('/api/users/{id}/remove', 'RegularUserController@removeUser');



//About
Route::get('/about', 'AboutController@show');

//Group
Route::get('/groups/create', 'GroupController@showCreateForm');
Route::post('/groups/create', 'GroupController@create');

Route::get('/groups/{id}', 'GroupController@show')->name('groups.show');
Route::post('/api/groups/{id}/posts', 'PostController@createInGroup');

Route::get('/groups/{group_id}/edit', 'GroupController@show_edit');
Route::post('/groups/{group_id}', 'GroupController@edit');
Route::put('api/groups/{id}/report', 'GroupController@report');

Route::delete('api/groups/{group_id}/members/{user_id}', 'GroupController@removeMember');

Route::get('/api/groups/{group_id}/friends','GroupController@getFriends');
Route::put('api/groups/{group_id}/{user_id}','GroupController@addToGroup');

//Event
Route::get('/events/create', 'EventController@showCreateForm');
Route::post('/events/create', 'EventController@create');

Route::get('/events/{event_id}' , 'EventController@show')->name('events.show');
Route::post('/api/events/{id}/posts', 'PostController@createInEvent');

Route::get('/events/{event_id}/statistics', 'EventController@show_statistics');
Route::get('/events/{event_id}/edit', 'EventController@show_edit');
Route::post('/api/events/{event_id}/upload_image', 'EventController@upload_image');
Route::post('/events/{event_id}', 'EventController@edit');
Route::put('api/events/{id}/report', 'EventController@report');
Route::put('api/events/{id}/interest', 'EventController@interest');
Route::put('api/events/{id}/desinterest', 'EventController@desinterest');

//Chat
Route::get('/chats/{chat_id}', 'ChatController@show')->name('chats.show');
Route::get('/chats', 'ChatController@get_chat');
Route::put('api/chats/create', 'ChatController@create');
Route::delete('/api/chats/{chat_id}/deleteUser', 'ChatController@deleteUser');


//Post
Route::get('/posts/{post_id}', 'PostController@show');
Route::get('/posts/{post_id}/edit', 'PostController@show_edit');
Route::post('/posts/{post_id}', 'PostController@edit');

Route::get('/api/posts/{last_id}', 'FeedController@getPosts');
Route::get('/api/groups/{group_id}/posts/{last_id}', 'GroupController@getPosts');
Route::get('/api/events/{event_id}/posts/{last_id}', 'EventController@getPosts');
Route::get('/api/users/me/posts/{last_id}', 'ProfileController@getMyPosts');
Route::get('/api/users/{user_id}/posts/{last_id}', 'ProfileController@getPosts');


Route::put('/api/users/notifications', 'RegularUserController@seeNotifactions');



Route::get('/settings','RegularUserController@settings')->name('settings');
Route::get('/archived','RegularUserController@archived');

Route::post('/users/delete','RegularUserController@delete');

Route::get('/api/chats/{chat_id}/friends','ChatController@getFriends');


Route::put('api/chats/{chat_id}/{user_id}','ChatController@addToChat');


Route::get('/dark_mode','ProfileController@dark');
Route::get('/light_mode','ProfileController@light');

Route::get('/api/chats/{chat_id}/messages/{id}','ChatController@getMessages');
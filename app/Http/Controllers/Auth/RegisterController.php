<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\RegularUser;
use App\Student;
use App\Teacher;
use App\Organization;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/users/me';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6|confirmed',
            'university' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();
        
        $regular_user = new RegularUser;
        $regular_user->user_id = $user->user_id;
        $regular_user->save();

        
        $user->userable_id = $regular_user->regular_user_id;
        $user->userable_type = RegularUser::class;
        $user->save();

        $occupation = null;

        if($data['occupation'] == 'Student')
            $occupation = new Student;
        else if($data['occupation'] == 'Teacher')
            $occupation = new Teacher;
        else if($data['occupation'] == 'Organization')
            $occupation = new Organization;

        $occupation->regular_user_id = $regular_user->regular_user_id;
        $occupation->save();

        if($data['occupation'] == 'Student'){
            $regular_user->regular_userable_id = $occupation->student_id;
            $regular_user->regular_userable_type = Student::class;
        }else if($data['occupation'] == 'Teacher'){
            $regular_user->regular_userable_id = $occupation->teacher_id;
            $regular_user->regular_userable_type = Teacher::class;
        }else if($data['occupation'] == 'Organization'){
            $regular_user->regular_userable_id = $occupation->organization_id;
            $regular_user->regular_userable_type = Organization::class;
        }
        $regular_user->save();

        return $user;
    }
}

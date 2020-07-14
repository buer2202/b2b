<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
    protected $redirectTo = '/home/user';

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
            'email'     => 'bail|required|string|email|max:255|unique:users',
            'password'  => 'bail|required|string|min:6|confirmed',
            'type'      => 'bail|required|in:1,2',
            'license'   => 'bail|max:100',
            'company'   => 'bail|max:50',
            'real_name' => 'bail|required|string|max:20',
            'id_number' => 'bail|required|string|max:20',
            'phone'     => 'bail|required|string|max:20',
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
        $user = User::create([
            'email'       => $data['email'],
            'password'    => bcrypt($data['password']),
            'type'        => $data['type'],
            'license'     => $data['license'] ?: null,
            'company'     => $data['company'] ?: null,
            'real_name'   => $data['real_name'],
            'id_number'   => $data['id_number'],
            'phone'       => $data['phone'],
            'status'      => 1,
            'auth_status' => 0,
            'secret_key'  => encrypt(str_random(32)),
        ]);
        $user->secret_id = uniqid($user->id);
        $user->save();

        return $user;
    }
}

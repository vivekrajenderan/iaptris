<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Input;
use App\Http\Resources\AuthResource;
use App\Rules\MatchOldPassword;
use DB;

class AuthController extends Controller {

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
                    'username' => 'required|max:55|unique:users',
                    'email' => 'email|required|unique:users',
                    'password' => 'required|confirmed',
                    'roles' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->messages()->all()
                            ], 200);
        } else {
            $userdata = array(
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'username' => $request->username,
                'address' => isset($request->address) ? $request->address : '',
                'companyname' => isset($request->companyname) ? $request->companyname : '',
                'country' => isset($request->country) ? $request->country : '',
                'state' => isset($request->state) ? $request->state : '',
                'zipcode' => isset($request->zipcode) ? $request->zipcode : '',
                'mobile' => isset($request->mobile) ? $request->mobile : '',
                'fk_roles_id' => $request->roles,
                'status' => '1'
            );
            $user = User::create($userdata);

            $accessToken = $user->createToken('authToken')->accessToken;

            return response(['status' => true, 'message' => 'Registered successfully', 'user' => $user, 'token' => $accessToken]);
        }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required',
                    'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $userdata = array(
                'email' => $request->email,
                'password' => $request->password
            );

            if (!auth()->attempt($userdata)) {
                return response(['status' => false, 'message' => 'Invalid Credentials']);
            }            
            $accessToken = auth()->user()->createToken('authToken')->accessToken;

            return response(['status' => true, 'user' => auth()->user(), 'token' => $accessToken]);
        }
    }

    public function changepassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'current_password' => ['required', new MatchOldPassword],
                    'new_password' => ['required'],
                    'new_confirm_password' => ['same:new_password'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            User::find(auth()->user()->id)->update(['password' => bcrypt($request->new_password)]);

            return response(['status' => true, 'message' => 'Password has been changed successfully']);
        }
    }

    public function userDetails(Request $request) {
        if (isset($request->id) && !empty($request->id)) {
            $userdetails = User::where('id', $request->id)->get()->toArray();
            return response()->json(['status' => true, 'user' => $userdetails]);
        }
    }

    public function logout(Request $request) {

        $request->user()->token()->revoke();
        return response()->json([
                    'message' => 'Successfully logged out'
        ]);
    }

    public function forget(Request $request) {

        $validator = Validator::make($request->all(), [
                    'email' => 'email|required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            if (User::where('email', '=', $request->email)->exists()) {
                return response()->json(["status" => true, "message" => 'Reset password link sent on your email ID.']);
            } else {
                return response()->json(["status" => false, "message" => 'Email is not exist.']);
            }
        }
    }

    public function userAll($searchVal = null) {
        $userdetails = User::where('fk_usertypes_id', '2')->where(function($query) use ($searchVal) {
                    $query->where('name', 'LIKE', '%' . $searchVal . '%')
                            ->orWhere('phone', 'LIKE', '%' . $searchVal . '%')
                            ->orWhere('status', 'LIKE', '%' . $searchVal . '%')
                            ->orWhere('dob', 'LIKE', '%' . $searchVal . '%')
                            ->orWhere('email', 'LIKE', '%' . $searchVal . '%');
                })->orderBy('name', 'desc')->get();
        return response()->json(["status" => true, "userdetails" => $userdetails]);
    }

    public function updateUser(Request $request) {
        if (isset($request->id) && !empty($request->id)) {
            $validator = Validator::make($request->all(), [
                        'name' => 'required|max:55',
                        'email' => 'unique:users,email,' . $request->id . '|email|required',
                        'dob' => 'required|date|before:-18 years',
                        'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:12',
                        'zipcode' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|max:8'
            ]);
            if ($validator->fails()) {
                return response()->json([
                            'status' => false,
                            'message' => implode(",", $validator->messages()->all())
                                ], 200);
            } else {
                $userdata = array(
                    'email' => $request->email,
                    'name' => $request->name,
                    'dob' => $request->dob,
                    'address' => isset($request->address) ? $request->address : '',
                    'companyname' => isset($request->companyname) ? $request->companyname : '',
                    'country' => isset($request->country) ? $request->country : '',
                    'state' => isset($request->state) ? $request->state : '',
                    'zipcode' => isset($request->zipcode) ? $request->zipcode : '',
                    'phone' => isset($request->phone) ? $request->phone : '',
                );
                if ($request->status != 'verified') {
                    if (in_array(null, $userdata, true) || in_array('', $userdata, true)) {
                        $userdata['status'] = 'pending';
                    } else {
                        $userdata['status'] = 'submitted';
                    }
                }
                User::find($request->id)->update($userdata);
                return response()->json(["status" => true, "message" => 'User updated Successfully.']);
            }
        }
    }

}

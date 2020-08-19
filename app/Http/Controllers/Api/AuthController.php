<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Roles;
use App\Models\University;
use Illuminate\Http\Request;
use Validator;
use Input;
use App\Http\Resources\AuthResource;
use App\Rules\MatchOldPassword;
use DB;

class AuthController extends Controller {

    public function roles(Request $request) {
        $roles = Roles::where('status', '1')->get()->toArray();
        return response()->json(['status' => true, 'roles' => $roles]);
    }

    public function university(Request $request) {
        $university = University::where('status', '1')->get()->toArray();
        return response()->json(['status' => true, 'university' => $university]);
    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
                    'username' => 'required|max:55|unique:users',
                    'email' => 'email|required|unique:users',
                    'password' => 'required|confirmed',
                    'roles' => 'required',
                    'certificate' => 'nullable|image|mimes:jpeg,jpg,png|max:10000',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $otp = mt_rand(100000, 999999);
            $userdata = array(
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'username' => $request->username,
                'gender' => isset($request->gender) ? $request->gender : '',
                'address' => isset($request->address) ? $request->address : '',
                'companyname' => isset($request->companyname) ? $request->companyname : '',
                'companyemail' => isset($request->companyemail) ? $request->companyemail : '',
                'qualification' => isset($request->qualification) ? $request->qualification : '',
                'city' => isset($request->city) ? $request->city : '',
                'state' => isset($request->state) ? $request->state : '',
                'zipcode' => isset($request->zipcode) ? $request->zipcode : '',
                'mobile' => isset($request->mobile) ? $request->mobile : '',
                'fk_university_id' => isset($request->fk_university_id) ? $request->fk_university_id : 0,
                'fk_roles_id' => $request->roles,
                'mobile_otp' => $otp
            );
            if (isset($request->certificate) && !empty($request->certificate)) {
                $file = $request->certificate;
                $destinationPath = 'upload/certificate/';
                $filename = md5(microtime() . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
                $request->certificate->move($destinationPath, $filename);
                $userdata['certificate'] = $filename;
            }
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
                'password' => $request->password,
            );
            if (!auth()->attempt($userdata)) {
                return response(['status' => false, 'message' => 'Invalid Credentials']);
            }
            $user = auth()->user();

            //Check the active status
            if (empty($user->activationstatus) || empty($user->status)) {
                return response(['status' => false, 'message' => 'Please verify and activate the user.']);
            }
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            return response(['status' => true, 'user' => auth()->user(), 'token' => $accessToken]);
        }
    }

    public function verifyOtp(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'mobile_otp' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $userdetails = User::where(['id' => $request->id, 'mobile_otp' => $request->mobile_otp])->get()->toArray();
            if (empty($userdetails)) {
                return response(['status' => false, 'message' => 'Invalid OTP.']);
            }
            User::find($request->id)->update(array('status' => '1', 'activationstatus' => '1'));
            return response()->json(['status' => true, 'message' => 'Otp verify successfully']);
        }
    }

    public function resendOtp(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $otp = mt_rand(100000, 999999);
            $userdetails = User::where(['id' => $request->id])->get()->toArray();
            if (empty($userdetails)) {
                return response(['status' => false, 'message' => 'Invalid User.']);
            }
            User::find($request->id)->update(array('mobile_otp' => $otp));
            return response()->json(['status' => true, 'message' => 'Otp send successfully', 'mobile_otp' => $otp]);
        }
    }

    public function changepassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
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
            User::find($request->id)->update(['password' => bcrypt($request->new_password)]);

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
                            ->orWhere('mobile', 'LIKE', '%' . $searchVal . '%')
                            ->orWhere('status', 'LIKE', '%' . $searchVal . '%')
                            ->orWhere('dob', 'LIKE', '%' . $searchVal . '%')
                            ->orWhere('email', 'LIKE', '%' . $searchVal . '%');
                })->orderBy('name', 'desc')->get();
        return response()->json(["status" => true, "userdetails" => $userdetails]);
    }

    public function updateUser(Request $request) {
        if (isset($request->id) && !empty($request->id)) {
            $validator = Validator::make($request->all(), [
                        'email' => 'unique:users,email,' . $request->id . '|email|required',
                        'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:12',
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
                    'address' => isset($request->address) ? $request->address : '',
                    'companyname' => isset($request->companyname) ? $request->companyname : '',
                    'companyemail' => isset($request->companyemail) ? $request->companyemail : '',
                    'city' => isset($request->city) ? $request->city : '',
                    'state' => isset($request->state) ? $request->state : '',
                    'zipcode' => isset($request->zipcode) ? $request->zipcode : '',
                    'mobile' => isset($request->mobile) ? $request->mobile : '',
                    'fk_university_id' => isset($request->fk_university_id) ? $request->fk_university_id : '',
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

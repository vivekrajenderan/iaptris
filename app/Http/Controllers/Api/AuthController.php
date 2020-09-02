<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Project;
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
        $roles = Roles::where('status', '1')->whereNotIn('id', [1])->get()->toArray();
        return response()->json(['status' => true, 'roles' => $roles]);
    }

    public function university(Request $request) {
        $university = University::where('status', '1')->get()->toArray();
        return response()->json(['status' => true, 'university' => $university]);
    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
                    'fullname' => 'required|max:55',
                    'username' => 'required|max:55|unique:users',
                    'email' => 'email|required|unique:users',
                    'password' => 'required|confirmed',
                    'roles' => 'required',
                    'devicetoken' => 'required',
                    'certificate' => 'nullable|image|mimes:jpeg,jpg,png|max:10000',
                    'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:12',
                    'zipcode' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|max:8',
                    'profileimage' => 'nullable|image|mimes:jpeg,jpg,png|max:10000',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $otp = mt_rand(100000, 999999);
            $userdata = array(
                'fullname' => $request->fullname,
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
                'devicetoken' => $request->devicetoken,
                'mobile_otp' => $otp
            );
            if (isset($request->certificate) && !empty($request->certificate)) {
                $file = $request->certificate;
                $destinationPath = 'upload/certificate/';
                $filename = md5(microtime() . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
                $request->certificate->move($destinationPath, $filename);
                $userdata['certificate'] = $filename;
            }
            if (isset($request->profileimage) && !empty($request->profileimage)) {
                $file = $request->profileimage;
                $destinationPath = 'upload/profile/';
                $filename = md5(microtime() . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
                $request->profileimage->move($destinationPath, $filename);
                $userdata['profileimage'] = $filename;
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
            $imageUrl = url('/upload/profile/');
            $userdetails = User::select('id', 'fullname', 'email', 'gender', 'address', 'companyname', 'companyemail', 'qualification', 'certificate', 'city', 'state', 'zipcode', 'mobile','fk_roles_id', 'fk_university_id', DB::raw("CONCAT('" . $imageUrl . "/', profileimage) AS profileimage"))
                            ->where('id', $request->id)->get()->toArray();
            $projects = array();
            if (count($userdetails) > 0) {
                $query = Project::where(['project.status' => '1', 'project.dels' => '0', 'project.createdBy' => $request->id])
                        ->join('category as c', 'c.id', '=', 'project.fk_category_id')
                        ->join('season as s', 's.id', '=', 'project.fk_season_id')
                        ->join('designertype as d', 'd.id', '=', 'project.fk_designertype_id')
                        ->select(['project.id', 'project.stylefor', 'project.brandname', 'project.brandimage', 'project.deliverytime',
                    'project.designbudget', 'project.projectamount', 'project.projectstatus', 'c.name as categoryname', 's.name as seasonname', 'd.name as designername']);
                $projects = $query->get()->toArray();
            }

            return response()->json(['status' => true, 'user' => $userdetails, 'projects' => $projects]);
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
            $userdetails = User::where(['id' => $request->id])->get()->toArray();
            if (count($userdetails) > 0) {
                $validator = Validator::make($request->all(), [
                            'email' => 'nullable|unique:users,email,' . $request->id . '|email',
                            'username' => 'nullable|unique:users,username,' . $request->id,
                            'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:12',
                            'zipcode' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|max:8',
                            'profileimage' => 'nullable|image|mimes:jpeg,jpg,png|max:10000',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                                'status' => false,
                                'message' => implode(",", $validator->messages()->all())
                                    ], 200);
                } else {
                    $userdata = array(
                        'fullname' => (isset($request->fullname) && !empty($request->fullname)) ? $request->fullname : $userdetails[0]['fullname'],
                        'email' => (isset($request->email) && !empty($request->email)) ? $request->email : $userdetails[0]['email'],
                        'username' => (isset($request->username) && !empty($request->username)) ? $request->username : $userdetails[0]['username'],
                        'gender' => (isset($request->gender) && !empty($request->gender)) ? $request->gender : $userdetails[0]['gender'],
                        'address' => (isset($request->address) && !empty($request->address)) ? $request->address : $userdetails[0]['address'],
                        'companyname' => (isset($request->companyname) && !empty($request->companyname)) ? $request->companyname : $userdetails[0]['companyname'],
                        'companyemail' => (isset($request->companyemail) && !empty($request->companyemail)) ? $request->companyemail : $userdetails[0]['companyemail'],
                        'qualification' => (isset($request->qualification) && !empty($request->qualification)) ? $request->qualification : $userdetails[0]['qualification'],
                        'city' => (isset($request->city) && !empty($request->city)) ? $request->city : $userdetails[0]['city'],
                        'state' => (isset($request->state) && !empty($request->state)) ? $request->state : $userdetails[0]['state'],
                        'zipcode' => (isset($request->zipcode) && !empty($request->zipcode)) ? $request->zipcode : $userdetails[0]['zipcode'],
                        'mobile' => (isset($request->mobile) && !empty($request->mobile)) ? $request->mobile : $userdetails[0]['mobile'],
                        'fk_university_id' => (isset($request->fk_university_id) && !empty($request->fk_university_id)) ? $request->fk_university_id : $userdetails[0]['fk_university_id'],
                        'devicetoken' => (isset($request->devicetoken) && !empty($request->devicetoken)) ? $request->devicetoken : $userdetails[0]['devicetoken']
                    );

                    //Upload brand image
                    if (isset($request->profileimage) && !empty($request->profileimage)) {
                        if (!empty($userdetails[0]["profileimage"])) {
                            $image_file = './upload/profile/' . $userdetails[0]["profileimage"];
                            if (file_exists($image_file)) {
                                unlink($image_file);
                            }
                        }
                        $file = $request->profileimage;
                        $destinationPath = 'upload/profile/';
                        $filename = md5(microtime() . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
                        $request->profileimage->move($destinationPath, $filename);
                        $userdata['profileimage'] = $filename;
                    }
                    User::find($request->id)->update($userdata);
                    return response()->json(["status" => true, "message" => 'User updated Successfully.']);
                }
            } else {
                return response()->json(["status" => false, "message" => 'Invalid User.']);
            }
        }
    }

    public function topRoles() {
//        echo url('/upload/profile/');die;
        $roles = Roles::where(['status' => '1'])->whereNotIn('id', [1])->get()->toArray();
        $data = array();
        $imageUrl = url('/upload/profile/');
        foreach ($roles as $key => $value) {
            $userdetails = User::select("id", "fullname", DB::raw("'4' as rating"), DB::raw("CONCAT('" . $imageUrl . "/', profileimage) AS profileimage"))->where(['fk_roles_id' => $value['id']])->get()->toArray();
            $data[$value['name']] = $userdetails;
        }
        return response()->json(["status" => true, "data" => $data]);
    }

}

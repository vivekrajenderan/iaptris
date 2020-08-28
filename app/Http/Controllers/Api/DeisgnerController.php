<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Input;
use App\Http\Resources\AuthResource;
use App\Rules\MatchOldPassword;
use DB;
use App\Models\Designerproject;
use App\User;
use App\Models\Project;

class DeisgnerController extends Controller {

    public function extractProject(Request $request) {

        $validator = Validator::make($request->all(), [
                    'fk_project_id' => 'required|numeric',
                    'fk_user_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $extractdetails = Designerproject::where(['fk_project_id' => $request->fk_project_id, 'fk_user_id' => $request->fk_user_id])->get()->toArray();
            if (empty($extractdetails)) {
                $projectdata = array(
                    'fk_project_id' => $request->fk_project_id,
                    'fk_user_id' => $request->fk_user_id,
                    'status' => '1'
                );
                $project = Designerproject::create($projectdata);

                return response(['status' => true, 'message' => 'Project extracted successfully']);
            } else {
                return response(['status' => false, 'message' => 'Already project has been extracted.']);
            }
        }
    }

}

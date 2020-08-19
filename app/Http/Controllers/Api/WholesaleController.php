<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Project;
use App\Models\University;
use Illuminate\Http\Request;
use Validator;
use Input;
use App\Http\Resources\AuthResource;
use App\Rules\MatchOldPassword;
use DB;
use App\Models\Category;
use App\Models\Season;
use App\Models\Designertype;

class WholesaleController extends Controller {

    public function getProjectDropdown(Request $request) {
        $category = Category::where('status', '1')->get()->toArray();
        $season = Season::where('status', '1')->get()->toArray();
        $designertypes = Designertype::where('status', '1')->get()->toArray();
        $stylefor = array('men' => 'Men', 'women' => 'Women', 'boy' => 'Boy', 'girl' => 'Girl');
        $designbudget = array('regular' => 'Regular', 'urgent' => 'Urgent');
        return response()->json(['status' => true, 'category' => $category,
                    'season' => $season, 'designertypes' => $designertypes,
                    'stylefor' => $stylefor, 'designbudget' => $designbudget]);
    }

    public function addProject(Request $request) {

        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:55',
                    'category_id' => 'required|numeric|max:20',
                    'season_id' => 'required|numeric|max:20',
                    'designertype_id' => 'required|numeric|max:20',
                    'stylefor' => 'required',
                    'brandname' => 'required|max:100',
                    'deliverytime' => 'required',
                    'designbudget' => 'required',
                    'createdBy' => 'required|numeric|max:20',
                    'brandimage' => 'nullable|image|mimes:jpeg,jpg,png|max:10000',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $projectdata = array(
                'name' => $request->name,
                'fk_category_id' => $request->category_id,
                'fk_season_id' => $request->season_id,
                'fk_designertype_id' => $request->designertype_id,
                'stylefor' => $request->stylefor,
                'brandname' => isset($request->brandname) ? $request->brandname : '',
                'deliverytime' => isset($request->deliverytime) ? $request->deliverytime : '',
                'designbudget' => isset($request->designbudget) ? $request->designbudget : '',
                'createdBy' => $request->createdBy,
                'status' => '1'
            );
            if (isset($request->brandimage) && !empty($request->brandimage)) {
                $file = $request->brandimage;
                $destinationPath = 'upload/brand/';
                $filename = md5(microtime() . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
                $request->brandimage->move($destinationPath, $filename);
                $projectdata['brandimage'] = $filename;
            }
            $project = Project::create($projectdata);

            return response(['status' => true, 'message' => 'Project addedd successfully']);
        }
    }

    public function getProjects() {
        $projects = Project::where(['status' => '1', 'dels' => '0'])->orderBy('name', 'desc')->get()->toArray();
        return response()->json(["status" => true, "projects" => $projects]);
    }

}

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
use App\Models\Projectpayment;

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
                    'stylenumber' => 'required|max:55|unique:project',
                    'category_id' => 'required|numeric',
                    'season_id' => 'required|numeric',
                    'designertype_id' => 'required|numeric',
                    'stylefor' => 'required',
                    'brandname' => 'required|max:100',
                    'deliverytime' => 'required',
                    'designbudget' => 'required',
                    'createdBy' => 'required|numeric|max:20',
                    'brandimage' => 'nullable|image|mimes:jpeg,jpg,png|max:10000',
                    'projectamount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $projectdata = array(
                'stylenumber' => $request->stylenumber,
                'fk_category_id' => $request->category_id,
                'fk_season_id' => $request->season_id,
                'fk_designertype_id' => $request->designertype_id,
                'stylefor' => $request->stylefor,
                'brandname' => isset($request->brandname) ? $request->brandname : '',
                'deliverytime' => isset($request->deliverytime) ? $request->deliverytime : '',
                'designbudget' => isset($request->designbudget) ? $request->designbudget : '',
                'createdBy' => $request->createdBy,
                'projectamount' => $request->projectamount,
                'status' => '1'
            );
//            Helper::pre($projectdata);
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

    public function updateProject(Request $request) {
            $validator = Validator::make($request->all(), [
                        'id' => 'required|numeric',
                        'stylenumber' => 'nullable|unique:project,stylenumber,' . $request->id,
                        'category_id' => 'required|numeric',
                        'season_id' => 'required|numeric',
                        'designertype_id' => 'required',
                        'stylefor' => 'required',
                        'brandname' => 'required|max:100',
                        'deliverytime' => 'required',
                        'designbudget' => 'required',
                        'createdBy' => 'required|numeric',
                        'brandimage' => 'nullable|image|mimes:jpeg,jpg,png|max:10000',
                        'projectamount' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                            'status' => false,
                            'message' => implode(",", $validator->messages()->all())
                                ], 200);
            } else {
                $projectdetails = Project::where(['id' => $request->id])->get()->toArray();
                if (count($projectdetails) > 0) {
                    $projectdata = array(
                        'stylenumber' => $request->stylenumber,
                        'fk_category_id' => $request->category_id,
                        'fk_season_id' => $request->season_id,
                        'fk_designertype_id' => $request->designertype_id,
                        'stylefor' => $request->stylefor,
                        'brandname' => isset($request->brandname) ? $request->brandname : '',
                        'deliverytime' => isset($request->deliverytime) ? $request->deliverytime : '',
                        'designbudget' => isset($request->designbudget) ? $request->designbudget : '',
                        'projectamount' => $request->projectamount,
                    );

                    //Upload brand image
                    if (isset($request->brandimage) && !empty($request->brandimage)) {
                        if (!empty($projectdetails[0]["brandimage"])) {
                            $image_file = './upload/brand/' . $projectdetails[0]["brandimage"];
                            if (file_exists($image_file)) {
                                unlink($image_file);
                            }
                        }
                        $file = $request->brandimage;
                        $destinationPath = 'upload/brand/';
                        $filename = md5(microtime() . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
                        $request->brandimage->move($destinationPath, $filename);
                        $projectdata['brandimage'] = $filename;
                    }

                    Project::find($request->id)->update($projectdata);
                    return response()->json(["status" => true, "message" => 'Project updated Successfully.']);
                } else {
                    return response()->json(["status" => false, "message" => 'Invalid Project.']);
                }
            }
        
    }

    public function getProjects($projectid = null) {
        //        $projects = Project::select(['project.id', 'project.stylefor', 'project.brandname', 'project.brandimage', 'project.deliverytime',
//                    'project.designbudget'])->where(['status' => '1', 'dels' => '0'])->with(array('category' => function($query) {
//                        $query->select('id','name');
//                    }))->orderBy('stylenumber', 'desc')->get()->toArray();
        $query = Project::where(['project.status' => '1', 'project.dels' => '0'])
                ->join('category as c', 'c.id', '=', 'project.fk_category_id')
                ->join('season as s', 's.id', '=', 'project.fk_season_id')
                ->join('designertype as d', 'd.id', '=', 'project.fk_designertype_id')
                ->select(['project.id', 'project.stylefor', 'project.brandname', 'project.brandimage', 'project.deliverytime',
            'project.designbudget', 'project.projectamount','project.projectstatus','c.name as categoryname', 's.name as seasonname', 'd.name as designername']);
        if (!empty($projectid)) {
            $query = $query->where('project.id', '=', $projectid);
        }
        $projects = $query->get()->toArray();
        return response()->json(["status" => true, "projects" => $projects]);
    }

    public function updatePayment(Request $request) {

        $validator = Validator::make($request->all(), [
                    'fk_project_id' => 'required|numeric|max:20',
                    'txnid' => 'required',
                    'payid' => 'required',
                    'amount' => 'required',
                    'txnstatus' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => implode(",", $validator->messages()->all())
                            ], 200);
        } else {
            $paymentdetails = Projectpayment::where(['fk_project_id' => $request->fk_project_id])->get()->toArray();
            if (empty($paymentdetails)) {
                $paymentdata = array(
                    'fk_project_id' => $request->fk_project_id,
                    'txnid' => $request->txnid,
                    'payid' => isset($request->payid) ? $request->payid : '',
                    'amount' => isset($request->amount) ? $request->amount : '',
                    'message' => isset($request->message) ? $request->message : '',
                    'txnstatus' => isset($request->txnstatus) ? $request->txnstatus : ''
                );
                $payment = Projectpayment::create($paymentdata);
                if (isset($request->txnstatus) && ($request->txnstatus == 'success')) {
                    Project::find($request->fk_project_id)->update(array('projectstatus' => 'posted'));
                }
                return response(['status' => ($request->txnstatus == 'success') ? true : false,
                    'message' => ($request->txnstatus == 'success') ? 'Payment has been updated successfully' : 'Payment has been cancelled']);
            } else {
                return response(['status' => false, 'message' => 'Already payment has been updated.']);
            }
        }
    }

    public function report($projectid = null) {
        $query = Projectpayment::where(['p.status' => '1', 'p.dels' => '0'])
                ->join('project as p', 'projectpayment.fk_project_id', '=', 'p.id')
                ->select(['p.id', 'p.stylefor', 'p.brandname', 'p.brandimage', 'p.deliverytime',
            'p.designbudget', 'p.projectstatus','projectpayment.txnid', 'projectpayment.payid', 'projectpayment.amount', 'projectpayment.message', 'projectpayment.txnstatus']);
        if (!empty($projectid)) {
            $query = $query->where('p.id', '=', $projectid);
        }
        $payment = $query->get()->toArray();
        return response()->json(["status" => true, "payment" => $payment]);
    }

}

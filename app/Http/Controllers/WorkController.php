<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $user = auth()->user();
        $city = $user->currentCity()->first();
        $building = Building::leftJoin('goods', 'goods.id', '=', 'buildings.good_id')->where('buildings.id', $id)->first([
            'goods.name as good_name',
            'goods.id as good_id',
            'buildings.*'
        ]);
        $workFlag = !!$user->job_id;
        $walkFlag = false;

        if($user->job_id == 1) {
            $walkFlag = true;
        }

        $building->short_job = $city->getReadableWalktime($building->short_job);
        $building->mid_job = $city->getReadableWalktime($building->mid_job);
        $building->long_job = $city->getReadableWalktime($building->long_job);

        return view('work.index', [
            'building' => $building,
            'city' => $city,
            'workFlag' => $workFlag,
            'walkFlag' => $walkFlag
        ]);
    }

    public function start($id, $task)
    {
        $building = Building::where('buildings.id', $id)->first();
        $user = auth()->user();
        $city = $user->currentCity()->first();
        $jobs = Job::where('building_id', $id)->get();

        if($building->city_id != $city->id) {
            return redirect('/city');
        }

        if($task == 1 || $task == 2 || $task == 3) {
            switch ($task) {
                case 1:
                    $job_time = $building->short_job;
                    $job = $jobs[0];
                    break;
                case 2:
                    $job_time = $building->mid_job;
                    $job = $jobs[1];
                    break;
                case 3:
                    $job_time = $building->long_job;
                    $job = $jobs[2];
                    break;
                default:
                    $job_time = $building->short_job;
                    $job = $jobs[0];
                    break;
            };

            // insert into users table
            $user->job_id = $job->id;
            $user->task = $job->task;
            $user->work_finished_at = Carbon::now()->addSeconds($job_time);
            $user->save();

            session()->put([
                'active_job_headline' => 'You are currently working',
                'active_job_description' => 'At: ' . $building->name . '. In: ' . $city->name . '  Finished at: ' . $user->work_finished_at,
            ]);

            return redirect()->back()->with([
                'status' => 'work started',
                'status_type' => 'success'
            ]);

        } else {
            return redirect('/city');
        }
    }
}

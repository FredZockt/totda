<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Job extends Model
{
    protected $table = 'jobs';
    protected $fillable = ['name', 'building_id'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function finish($user)
    {
        // working or walking??
        if($this->building_id) {
            // working
            $building = $this->building()->first();
            $good = $building->good()->first();

            // high value goods are produced less than cheap ones
            // determined by price
            switch ($user->task) {
                case 1:
                    // short work
                    $amount = round(rand(10, 20) / $good->price * (1 / log($building->level + 2)));
                    break;
                case 2:
                    // mid work
                    $amount = round(rand(50, 100) / $good->price * (1 / log($building->level + 2)));
                    break;
                case 3:
                    // long work
                    $amount = round(rand(100, 150) / $good->price * (1 / log($building->level + 2)));
                    break;
                default:
                    // short work
                    $amount = round(rand(10, 20) / $good->price * (1 / log($building->level + 2)));
                    break;
            };

            // determine owner
            if($building->user_id != null && $building->user_id != 0) {
                $owner = $building->user()->first();
                Inventory::addItem($user->id, $good->id, round($amount * 0.9), 'good', $good->max_stack);
                Inventory::addItem($owner->id, $good->id, round($amount * 0.1), 'good', $good->max_stack);
            } else {
                Inventory::addItem($user->id, $good->id, $amount, 'good', $good->max_stack);
            }

            // add items
            $user->job_id = null;
            $user->work_finished_at = null;
            $user->task = null;
            $user->save();

        } else {
            // walking
            // just reset the users job and finished at
            $user->job_id = null;
            $user->work_finished_at = null;
            $user->task = null;
            $user->save();
        }
    }
}

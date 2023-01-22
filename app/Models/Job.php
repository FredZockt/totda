<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
                    $amount = round(rand(10, 20) / $good->price);
                    break;
                case 2:
                    // mid work
                    $amount = round(rand(50, 100) / $good->price);
                    break;
                case 3:
                    // long work
                    $amount = round(rand(100, 150) / $good->price);
                    break;
                default:
                    // short work
                    $amount = round(rand(10, 20) / $good->price);
                    break;
            };

            // add items
            Inventory::addItem($user->id, $good->id, $amount, 'good', $good->max_stack);

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

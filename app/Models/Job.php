<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';
    protected $fillable = ['name', 'building_id'];

    public function finish($user)
    {
        // working or walking??
        if($this->building_id) {
            // working
            var_dump("working");
        } else {
            // walking
            // just reset the users job and finished at
            $user->job_id = null;
            $user->work_finished_at = null;
            $user->save();
        }
    }
}

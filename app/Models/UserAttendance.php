<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttendance extends Model
{
    use HasFactory;

    // Statuses
    const OK        = 'ok';
    const LATE_IN   = 'late in';
    const ON_TIME   = 'on time';
    const EARLY_OUT = 'early out';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}

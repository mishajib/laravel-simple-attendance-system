<?php

namespace App\Http\Controllers;

use App\Models\UserAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user           = Auth::user();
        $attendances    = $user->attendances()->latest()->get();
        $userAttendance = $user->attendances()->whereDate('timein', Carbon::today())->first() ?? '';
        return view('home', compact('attendances', 'user', 'userAttendance'));
    }

    public function timeIn()
    {
        $user             = Auth::user();
        $checkTimeInExist = UserAttendance::whereDate('timein', Carbon::today())->first();
        if ($checkTimeInExist) {
            session()->flash('error', 'You have already checked in!');
            return back();
        }
        // Take start time for check time in status
        $startTime = Carbon::today()->setTime(9, 00, 00);

        $data['user_id'] = $user->id;
        $data['timein']  = Carbon::now();
        // Check time in
        if ($startTime < Carbon::now()) {
            $data['status_timein'] = UserAttendance::LATE_IN;
        } else {
            $data['status_timein'] = UserAttendance::OK;
        }
        UserAttendance::create($data);
        session()->flash('success', 'Successfully checked in');
        return redirect()->route('home');
    }

    public function timeOut($id)
    {
        $attendance = UserAttendance::findOrFail($id);
        $startTime  = Carbon::parse($attendance->timein);
        $endTime    = Carbon::today()->setTime(18, 00, 00);

        $data['timeout']     = Carbon::now();
        $data['total_hours'] = $startTime->diffInHours($data['timeout']);
        if ($endTime < $data['timeout']) {
            $data['status_timeout'] = UserAttendance::OK;
        } elseif ($endTime == $data['timeout']) {
            $data['status_timeout'] = UserAttendance::ON_TIME;
        } else {
            $data['status_timeout'] = UserAttendance::EARLY_OUT;
        }
        $attendance->update($data);
        session()->flash('success', 'Successfully checked out');
        return redirect()->route('home');
    }
}

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __($user->name) }}{{ __(' Attendances') }}

                        @if(@$userAttendance->timein && !@$userAttendance->timeout)
                            <form action="{{ route('time.out', $userAttendance->id ?? '') }}" class="float-right"
                                  method="POST">
                                @csrf
                                <button class="btn btn-primary btn-sm">Time Out</button>
                            </form>
                        @endif
                        @if(!@$userAttendance->timein)
                            <form action="{{ route('time.in') }}" class="float-right mr-1" method="POST">
                                @csrf
                                <button class="btn btn-primary btn-sm">Time In</button>
                            </form>
                        @endif
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Oops!</strong> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @elseif(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Hurray!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>SL#</th>
                                <th>User</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Total Hours</th>
                                <th>Status Time In</th>
                                <th>Status Time Out</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($attendances as $key => $attendance)
                                <tr>
                                    <th scope="row">{{ ++$key }}</th>
                                    <td>
                                        @if($attendance->user)
                                            {{ $attendance->user->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->timein)
                                            {{ \Carbon\Carbon::parse($attendance->timein)->format('M, j Y - g:ia') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->timeout)
                                            {{ \Carbon\Carbon::parse($attendance->timeout)->format('M, j Y - g:ia') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        {{ $attendance->total_hours ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @if($attendance->status_timein)
                                            @if($attendance->status_timein == \App\Models\UserAttendance::LATE_IN)
                                                <span class="badge badge-danger">
                                                    {{ Str::upper($attendance->status_timein) }}
                                                </span>
                                            @else
                                                <span class="badge badge-success">
                                                    {{ Str::upper($attendance->status_timein) }}
                                                </span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->status_timeout)
                                            @if($attendance->status_timeout == \App\Models\UserAttendance::EARLY_OUT)
                                                <span class="badge badge-danger">
                                                    {{ Str::upper($attendance->status_timeout) }}
                                                </span>
                                            @elseif($attendance->status_timeout == \App\Models\UserAttendance::ON_TIME)
                                                <span
                                                    class="badge badge-info">{{ Str::upper($attendance->status_timeout) }}</span>
                                            @else
                                                <span class="badge badge-success">
                                                    {{ Str::upper($attendance->status_timeout) }}
                                                </span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        <h3 class="text-center text-danger">No data found!</h3>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

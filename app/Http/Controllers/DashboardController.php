<?php

namespace App\Http\Controllers;

use App\Models\AirPlane;
use App\Models\AlertTemplate;
use App\Models\FlightSchedule;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['']]);
    }
  
public function fetchStats(Request $request)
{
    $startDate = $request->input('start_date', Carbon::today()->startOfDay());
    $endDate = $request->input('end_date', Carbon::today()->endOfDay());

    $flights = FlightSchedule::whereBetween('departure_time', [$startDate, $endDate])->get();

    return response()->json(
        [
            'agents' => UserDetails::where('userType', 'agent')->count(),
            'clients' => UserDetails::where('userType', 'client')->count(),
            'airplanes' => AirPlane::count(),
            'flightSchedules' => FlightSchedule::count(),
            'alertTemplates' => AlertTemplate::count(),
            'getFlightByDate' => $flights,
        ],
        200
    );
}
    

}

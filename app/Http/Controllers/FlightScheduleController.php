<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlightSchedule;
use Illuminate\Support\Facades\Validator;

class FlightScheduleController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flight_number' => 'nullable',
            'departure_airport' => 'nullable',
            'arrival_airport' => 'nullable',
            'departure_time' => 'nullable',
            'arrival_time' => 'nullable',
            'status' => 'in:active,inactive',
            'caller_type_id' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'airplane_id' => 'nullable|exists:airplanes,id',
            'alert_template_id' => 'nullable|exists:alert_templates,id',
            'ticket_number' => 'nullable',
            'counter' => 'nullable',
            'userdetails_id' => 'nullable|exists:user_details,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $flightSchedule = FlightSchedule::create($request->all());

        return response()->json(['message' => 'Flight schedule created successfully', 'data' => $flightSchedule]);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'flight_number' => 'nullable',
            'departure_airport' => 'nullable',
            'arrival_airport' => 'nullable',
            'departure_time' => 'nullable',
            'arrival_time' => 'nullable',
            'status' => 'in:active,inactive',
            'caller_type_id' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'airplane_id' => 'nullable|exists:airplanes,id',
            'alert_template_id' => 'nullable|exists:alert_templates,id',
            'ticket_number' => 'nullable',
            'counter' => 'nullable',
            'userdetails_id' => 'nullable|exists:user_details,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $flightSchedule = FlightSchedule::find($id);

        if (!$flightSchedule) {
            return response()->json(['message' => 'Flight schedule not found'], 404);
        }

        $flightSchedule->update($request->all());

        return response()->json(['message' => 'Flight schedule updated successfully', 'data' => $flightSchedule]);
    }

    public function delete($id)
    {
        $flightSchedule = FlightSchedule::find($id);

        if (!$flightSchedule) {
            return response()->json(['message' => 'Flight schedule not found'], 404);
        }

        $flightSchedule->delete();

        return response()->json(['message' => 'Flight schedule deleted successfully']);
    }

    public function getAll()
    {
        return FlightSchedule::all();
    }
}


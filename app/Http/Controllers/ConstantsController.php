<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AirPlane;
use App\Models\CallType;
use Illuminate\Support\Facades\Validator;

class ConstantsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['']]);
    }
    public function createAirplane(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'airplaneCode' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'status' => 'in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $airplane = Airplane::create($request->all());

        return response()->json(['message' => 'Airplane created successfully', 'data' => $airplane]);
    }

    public function editAirplane(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'airplaneCode' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'status' => 'in:active,inactive',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $airplane = Airplane::find($id);
    
        if (!$airplane) {
            return response()->json(['message' => 'Airplane not found'], 404);
        }
    
        $airplane->update($request->all());
    
        return response()->json(['message' => 'Airplane updated successfully', 'data' => $airplane]);
    }
    

    public function deleteAirplane($id)
    {
        $airplane = Airplane::find($id);
    
        if (!$airplane) {
            return response()->json(['message' => 'Airplane not found'], 404);
        }
    
        $airplane->delete();
    
        return response()->json(['message' => 'Airplane deleted successfully']);
    }
    

    public function getAllAirPlane()
    {   return Airplane::all(); 
    }


    public function createCallType(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'user_id' => 'required|exists:users,id',
            'type' => 'in:reminder,notification',
            'status' => 'in:active,inactive',
        ]);

        $callType = CallType::create($data);

        return response()->json(['message' => 'Call Type created successfully', 'data' => $callType]);
    }

    public function editCallType(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user_id' => 'required|exists:users,id',
            'type' => 'in:reminder,notification',
            'status' => 'in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $callType = CallType::find($id);

        if (!$callType) {
            return response()->json(['message' => 'Call Type not found'], 404);
        }

        $callType->update($request->all());

        return response()->json(['message' => 'Call Type updated successfully', 'data' => $callType]);
    }

    public function deleteCallType($id)
    {
        $callType = CallType::find($id);

        if (!$callType) {
            return response()->json(['message' => 'Call Type not found'], 404);
        }

        $callType->delete();

        return response()->json(['message' => 'Call Type deleted successfully']);
    }

    public function getAllCallTypes()
    {

        return  CallType::all();
    }





}


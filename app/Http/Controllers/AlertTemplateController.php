<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlertTemplate;
use Illuminate\Support\Facades\Validator;

class AlertTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required|exists:users,id',
            'status' => 'in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $alertTemplate = AlertTemplate::create($request->all());

        return response()->json(['message' => 'Alert template created successfully', 'data' => $alertTemplate]);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required|exists:users,id',
            'status' => 'in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $alertTemplate = AlertTemplate::find($id);

        if (!$alertTemplate) {
            return response()->json(['message' => 'Alert template not found'], 404);
        }

        $alertTemplate->update($request->all());

        return response()->json(['message' => 'Alert template updated successfully', 'data' => $alertTemplate]);
    }

    public function delete($id)
    {
        $alertTemplate = AlertTemplate::find($id);

        if (!$alertTemplate) {
            return response()->json(['message' => 'Alert template not found'], 404);
        }

        $alertTemplate->delete();

        return response()->json(['message' => 'Alert template deleted successfully']);
    }

    public function getAll()
    {
        $alertTemplates = AlertTemplate::all();

        return response()->json(['data' => $alertTemplates]);
    }
}

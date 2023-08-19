<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\TransferSms;


class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['']]);
    }

    // Store a new notification
    public function push(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required',
            'doneBy' =>  'required',
            'message' => 'required',
        ]);

        Message::create($validatedData);

        $sms = new TransferSms();
        $sms->sendSMS($request['phone_number'], $request['message']);

        return response()->json(['message' => 'Notification is sent successfully', 'data' => $validatedData]);
       
    }

    // Show a list of notifications
    public function index()
    {
        $notifications = Message::all();
        return response()->json(['message' => 'success', 'data' => $notifications]);
    
     }

    // Show a specific notification
    public function show($id)
    {
        return response()->json(['message' => 'success', 'data' => Message::find($id)]);
    }

    

    // Update a specific notification
    public function update(Request $request, Message $notification)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required',
            'doneBy' => 'required',
            'message' => 'required',
        ]);

        $sms = new TransferSms();
        $sms->sendSMS($request['phone_number'], $request['message']);


        $notification->update($validatedData);

        return response()->json(['message' => 'Notification updated successfully', 'data' => $notification]);
     
    }

    // Delete a specific notification
    public function destroy(Message $notification)
    {
        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully', 'data' => $notification]);
     
    }
}

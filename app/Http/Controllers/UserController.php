<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\TransferSms;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['']]);
    }


    /**
     * create Agent
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAgent(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'firstName' => 'required',
            'lastName' => 'required',
            'telephone' => 'required'
        ]);

        // Create a new user
        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $role = Role::find('1717');
        $user->attachRole($role);

        // Create a new user details
        $userDetails = UserDetails::create([
            'user_id' => $user->id,
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'address' => $request->input('address'),
            'telephone' => $request->input('telephone'),
            'dob' => $request->input('dob') ?? null, // Use null if 'dob' is not provided
            'gender' => $request->input('gender') ?? null, // Use null if 'gender' is not provided
            'location' => $request->input('location') ?? null, // Use null if 'location' is not provided
            'status' => 'pending'
        ]);

       

        $message = "Hello " . $request['firstName'] . " Your Account has been created successfully, here is your login credentials email: " . $request->input('email') . "  Password: " . $request->input('password');

        // $sms = new TransferSms();
        // $sms->sendSMS($request['telephone'], $message);

        // Return a response
        return response()->json([
            'message' => 'Agent created successfully',
            'user' => $user,
            'userDetails' => $userDetails
        ], 201);
    }



    public function editAgent(Request $request, $id)
    {

        // Find the Agent user
        $user = User::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'firstName' => 'required',
            'lastName' => 'required',
            'telephone' => 'required'
        ]);


        // Update the user
        $user->update([
            'username' => $request->input('username'),
            'email' => $request->input('email')
        ]);

        // Update the user details
        $userDetails = UserDetails::find($id);

        if ($userDetails) {
            $userDetails->update([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'address' => $request->input('address'),
                'telephone' => $request->input('telephone'),
                'dob' => $request->input('dob') ?? null,
                'gender' => $request->input('gender') ?? null,
                'location' => $request->input('location') ?? null
            ]);
        }

        // Return a response
        return response()->json([
            'message' => 'Agent updated successfully',
            'user' => $user,
            'userDetails' => $userDetails
        ], 200);
    }

    public function deleteAgent($id)
    {
        // Find the Agent user
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        // Return a response
        return response()->json([
            'message' => 'Agent deleted successfully'
        ], 200);
    }


    public function getAllAgents()
    {
        // Retrieve all Agents with their associated user details
        $Agents = UserDetails::with('user')
        ->get();

        return response()->json([
            'Agents' => $Agents
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OTP;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\TransferSms;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class JWTController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','validateEmail','check_otp']]);
    }


    /**
     * login user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validationRules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($credentials, $validationRules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid email.'], 401);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Invalid password.'], 401);
        }

        try {
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json(['error' => 'Invalid credentials.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }


        return response()->json(
            [
                'message' => 'success',
                'token' => $token,
                'user' => Auth::user(),
                'userRole' => Auth::user()->roles->first()->display_name,
            ],
            200
        );
    }


    /**
     * Logout user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully logged out.']);
    }



    /**
     * Get user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {

        return response()->json(
            [
                'token' => $request->bearerToken(),
                'user' => Auth::user(),
                'userRole' => Auth::user()->roles->first()->display_name,
            ],
            200
        );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function allUserDetails()
    {
        return UserDetails::all();
    }
   public function showUserDetailsById($id)
{
    $userDetails = UserDetails::find($id);

    if (!$userDetails) {
        return response()->json(['message' => 'User not found'], 404);
    }

    return $userDetails;
}


    public function destroyUserDetailsById($id)
    {
        $UserDetails = UserDetails::find($id);

        if (!$UserDetails) {
            return response()->json([
                'message' => 'UserDetails is not found',
            ], 404);
        }

        $UserDetails->delete();

        return response()->json([
            'message' => 'UserDetails deleted successfully',
        ], 200);
    }
   public function addUserDetails(Request $request){
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email|unique:users',
        'role_id' => 'required|exists:roles,id',
        'dob' => 'nullable|date_format:Y-m-d'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }
    
    $user = User::create([
        'username' => $request-> input('username'),
        'email' => $request-> input('email'),
        'password' => $request-> input('password'),
    ]);

     $role = Role::find($request->input('role_id'));
     $user->attachRole($role);

    $UserDetails = UserDetails::create([
        'user_id' => $user->id,
        'firstName' =>  $request->input('firstName'),
        'lastName' =>  $request->input('lastName'),
        'address' =>  $request->input('address')?? null,
        'mobileTelephone' =>  $request->input('mobileTelephone')?? null,
        'workTelephone' =>   $request->input('workTelephone') ?? null,
        'dob' =>   $request->input('dob') ?? null,
        'gender' =>   $request->input('gender') ?? null,
        'nationality' =>   $request->input('nationality') ?? null,
        'nid' =>   $request->input('nid') ?? null,
        'passport' =>   $request->input('passport') ?? null,
        'language' =>   $request->input('language') ?? null,
        'placeOfIssue' =>   $request->input('placeOfIssue') ?? null,
        'workEmail' =>   $request->input('workEmail') ?? null,
        'userType' =>   $role->name ?? "agent",
        'email' =>   $request->input('email'),
     ]);

     if($role->name == "client"){
     $message = "Hello " . $request['firstName'] . " Your  Travel-Quest FAS".$role->name." Account has been created successfully, here is your login credentials email: " . $request->input('email') . "  One Time Password: " . $request->input('password');

     $sms = new TransferSms();
     $sms->sendSMS($request['mobileTelephone'], $message);
     }


    return response()->json([
        'data' => $UserDetails,
    ], 201);
   }


   public function validateEmail(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'email' => 'required|email|exists:users,email',
       ]);
   
       if ($validator->fails()) {
           // Email does not exist or validation failed
           return response()->json($validator->errors(), 400);
       }
   
       $email = $request->input('email');
       $user = User::where('email', $email)->first();
       $otp = random_int(100000, 999999);

       $otpModel = OTP::where('user_id', $user->id)->first();

       if ($otpModel) {
           $otpModel->otp = $otp;
           $otpModel->save();
       } else {
           $otpModel = new OTP();
           $otpModel->user_id = $user->id;
           $otpModel->otp = $otp;
           $otpModel->save();
       }
       

    
       $userDetails= UserDetails::where('user_id',$user->id)->first();
       
       $message = "Hello ".$userDetails->firstName.' '.$userDetails->lastName.' Your OTP is '.$otp;
     
       $sms = new TransferSms();
       $sms->sendSMS($userDetails->mobileTelephone, $message);

       return response()->json(['message' => 'You OTP '.$otp.' has been sent to your phone number: '.$userDetails->mobileTelephone ]);
   }


   public function check_otp(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'email' => 'required|email|exists:users,email',
           'otp' => 'required',
           'password' => 'required',
       ]);
   
       if ($validator->fails()) {
           return response()->json($validator->errors(), 400);
       }
   
       $email = $request->input('email');
       $otp = $request->input('otp');
       $password = $request->input('password');
   
       $user = User::where('email', $email)->first();
   
       if (!$user) {
           return response()->json(['message' => 'User not found'], 404);
       }
   
       $otpRecord = OTP::where('user_id', $user->id)
                       ->where('otp', $otp)
                       ->first();
   
       if (!$otpRecord) {
           return response()->json(['message' => 'Invalid OTP or expired'], 400);
       }
   
       $user->update(['password' => bcrypt($password)]);
   
       return response()->json(['message' => 'Password updated successfully']);
   }


}

<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Role;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use DB;

class UserController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();

        $roleuser = User::find($user->id);
        $member = Role::where('slug', 'member')->first();
        $roleuser->roles()->attach($member);

        $content_mail = "Create member ".$user->id ." : ".$user->email;
        $this->sm($content_mail);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
    public function adminSignup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();

        $roleuser = User::find($user->id);
        $admin = Role::where('slug', 'admin')->first();
        $roleuser->roles()->attach($admin);

        $content_mail = "Create admin ".$user->id ." : ".$user->email;
        $this->sm($content_mail);

        return response()->json([
            'message' => 'Successfully created admin!'
        ], 201);
    }
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }


    public function update(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $user = $request->user();
        if ($request->name) {
            $user->name = $request->name;
        }
        if ($files = $request->file('avatar')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $user->avatar = 'images/'.$profileImage;
        }
        if ($request->birthday) {
            $user->birthday = $request->birthday;
        }
        $user->save();

        $content_mail = "Update ".$user->id ." : ".$user->email;
        $this->sm($content_mail);

        return response()->json($request->user());
    }

    static public function sm($mess = '')
    {
        $users = DB::table('role_users')->where('role_id', Role::where('name','=','Admin')->first()->id)->get();
        foreach ($users as $user) {
            $user_receive = User::find($user->user_id);
            Mail::to($user_receive)->send(new SendMail($mess));
            sleep(5);
        }
    }

    public function users(Request $request)
    {
        if($request->user()->hasAccess(['user.users']) == true){
            $user = User::select('users.*','roles.*')->join('role_users', 'role_users.user_id', '=', 'users.id')->join('roles', 'role_users.role_id', '=', 'roles.id')->get();
            return response()->json($user);
        }else{
            return response()->json([
                'message' => 'Error'
            ]);
        }
    }
}
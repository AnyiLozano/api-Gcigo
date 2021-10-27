<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function newUser(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $user = new User();
            $user->uid = Hash::make($request->names);
            $user->fullname = $request->names;
            $user->email = $request->email;
            $user->contraseña = Hash::make($request->email);
            $user->password = Hash::make($request->password);
            $user->speciality = $request->speciality;
            $user->work_location = $request->work_location;
            $user->status_id = 2;
            $user->save();
            $user->assignRole('Regular');

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }
        if ($status) {
            return [
                'transaction' => ['status' => $status],
                'code' => 200,
                'data' => $user
            ];
        } else {
            return [
                'transaction' => ['status' => $status],
                'code' => 500,
                'data' => $result
            ];
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $activeUser = User::where('email', $request->email)->whereStatusId(1)->find($user->id);
            if (isset($activeUser->id)) {
                $token = $user->createToken('g-cigo')->accessToken;
                return ['transaction' => ['status' => true], 'code' => 200, 'data' => ['token' => $token, 'roles' => $user->getRoleNames()]];
            } else {
                return ['transaction' => ['status' => false], 'code' => 500, 'data' => 'No estas autorizado para el ingreso'];
            }
        } else {
            return ['transaction' => ['status' => false], 'code' => 500, 'data' => 'Las credenciales no son correctas.'];
        }
    }

    public function getUsers()
    {
        $users = User::whereStatusId(2)->get();
        return $users;
    }

    public function getUser()
    {
        $user = User::find(Auth::id());
        return $user;
    }

    public function deleteUser(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $beforeUser = User::find($request->id);
            $user = User::find($request->id);
            $user->removeRole('Regular');
            $user->delete();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }
        if ($status) {
            return [
                'transaction' => ['status' => true],
                'code' => 200,
                'data' => "Se elimino exitosamente el usuario con correo electronico <b>$beforeUser->email</b>"
            ];
        } else {
            return [
                'transaction' => ['status' => false],
                'code' => 500,
                'data' => $result
            ];
        }
    }

    public function activeUser(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $beforeUser = User::find($request->id);
            $user = User::find($request->id);
            $user->status_id = 1;
            $user->save();


            Mail::to($user->email)->send(new ConfirmationEmail());
            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }
        if ($status) {
            return [
                'transaction' => ['status' => true],
                'code' => 200,
                'data' => "Se elimino exitosamente el usuario con correo electronico <b>$beforeUser->email</b>"
            ];
        } else {
            return [
                'transaction' => ['status' => false],
                'code' => 500,
                'data' => $result
            ];
        }
    }

    public function updateImage(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $user = User::find($request->id);
            if ($user->avatar == null) {
                $path = Storage::disk('local')->put("/profile/$user->email", $file, 'public');
                $user->avatar = $path;
                $user->save();
            } else {
                Storage::disk('local')->deleteDirectory("/profile/$user->email");
                $user->avatar = null;
                $user->save();
                $path = Storage::disk('local')->put("/profile/$user->email", $file, 'public');
                $user->avatar = $path;
                $user->save();
            }

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }
        if ($status) {
            return [
                'transaction' => ['status' => true],
                'code' => 200,
                'data' => $user
            ];
        } else {
            return [
                'transaction' => ['status' => false],
                'code' => 500,
                'data' => $result
            ];
        }
    }

    public function UpdateData(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            $user->fullname = $request->names;
            $user->speciality = $request->speciality;
            $user->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }
        if ($status) {
            return [
                'transaction' => ['status' => true],
                'code' => 200,
                'data' => $user
            ];
        } else {
            return [
                'transaction' => ['status' => false],
                'code' => 500,
                'data' => $result
            ];
        }
    }

    public function CreateAdmin(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $user = new User();
            $user->uid = Hash::make($request->fullname);
            $user->fullname = $request->fullname;
            $user->email = $request->email;
            $user->contraseña = Hash::make($request->email);
            $user->password = Hash::make($request->password);
            $user->speciality = $request->speciality;
            $user->work_location = $request->work_location;
            $user->status_id = 1;
            $user->save();

            $user->assignRole('Admin');

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }
        if ($status) {
            return [
                'transaction' => ['status' => true],
                'code' => 200,
                'data' => $user
            ];
        } else {
            return [
                'transaction' => ['status' => false],
                'code' => 500,
                'data' => $result
            ];
        }
    }

    public function recoverPassword(Request $request)
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            return [
                'transaction' => ['status' => true],
                'data' => 'se actualizo el usuario'
            ];
        }else{
            return [
                'transaction' => ['status' => false],
                'data' => $result
            ];
        }
    }
}

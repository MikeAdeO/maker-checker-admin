<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserDraft;
use App\Models\UserEdit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{
    public function submitUser(Request $request)
    {
        // validation
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            "email" => 'required|unique:user_drafts,email|email',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        $user_id = $request->user_id ?? null;
        $request_type = 'create';
        $response = $this->createUserDraft($request, $request_type, $user_id);
        return $response;
    }

    private function createUserDraft($request, $request_type, $user_id)
    {
        try {
            DB::beginTransaction();

            $userDraft = UserDraft::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'  => $request->email,
            ]);

            $userDraft->userEdits()->create([
                'user_id' => $user_id,
                'maker_id' => 1,
                'editable_type' => UserDraft::class,
                'request_type' => $request_type
            ]);

             // send Email
            $userDraft->sendAdminNotification();

            DB::commit();

            $data = $userDraft->fresh('userEdits.maker', 'userEdits.editable');


            return response()->json($data, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }


    public function showRequests()
    {
        $data = UserEdit::whereHasMorph(
            'editable',
            [User::class, UserDraft::class],
            function (Builder $query, $type) {

                if ($type == UserDraft::class) {
                    $query->where('status', 'pending');
                }

                if ($type == User::class) {
                    $query->where('status', 'pending');
                }
            }
        )->get();

        $data = $data->fresh('editable', 'maker');


        return response()->json($data, 200);
    }

    public function submitUserUpdate(Request $request, $userId)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            "email" => 'required|unique:user_drafts,email|email',
        ],);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        $user = User::find($userId);
        if (is_null($user)) {
            return response()->json(["message" => "User does not exist"], 404);
        }
        $user_id = $user->id;
        $request_type = 'update';
        $response = $this->createUserDraft($request, $request_type, $user_id);
        return $response;
    }

    public function approveRequest($id)
    {
        $data = UserEdit::where('id', $id)->where('status', 'pending')->with('editable', 'maker')->first();




        if (!$data) {
            return response()->json(['message' => "User Draft does not exist"], 404);
        }



        if ($data->request_type == 'create') {
            $user = $this->addUser($data);
        }

        if ($data->request_type == 'update') {
            $user = $this->updateUser($data);
        }
        if ($data->request_type == 'destroy') {
            $user = $this->destroyUser($data);
        }


        return $user;
    }

    private function addUser($data)
    {

        try {
            DB::beginTransaction();
            $user = User::create([
                "first_name" => $data->editable->first_name,
                "last_name" => $data->editable->last_name,
                "email" => $data->editable->email,
                "password" => Hash::make($data->editable->first_name)
            ]);

            $this->destroyUserDraft($data->editable->id);

            $user->realUser()->create([
                'maker_id' =>  $data->maker->id,
                'editable_type' => User::class,
                'status' => 'success',
                'checker_id' => 2  #Auth Admin
            ]);

            DB::commit();

            return response()->json([
                'message' => "user added",
                "data" => $user,
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->json($user, 201);
    }

    private function updateUser($data)
    {
        $user = User::find($data->user->id);



        if (!$user) {
            return response()->json(["message" => "User does not exist"], 400);
        }
        try {
            DB::beginTransaction();
            $user->update([
                'first_name' => $data->editable->first_name,
                "last_name" => $data->editable->last_name,
                "email" => $data->editable->email
            ]);


            $this->destroyUserDraft($data->editable->id);

            $user->realUser()->create([
                'maker_id' => $data->maker->id,
                'editable_type' => User::class,
                'status' => 'success',
                'checker_id' => 2  #Auth Admin
            ]);

            DB::commit();

            return response()->json([
                'message' => "user updated",
                "data" => $user,
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    private function destroyUser($data)
    {
        $user = User::find($data->user->id);
        if (!$user) {
            return response()->json(["message" => "User does not exist"], 400);
        }

        User::destroy($user->id);

        return response()->json(['message' => "user Deleted"], 200);
    }

    private function destroyUserDraft($id)
    {
        UserDraft::destroy($id);
    }

    public function declineRequest($id)
    {
        $data = UserEdit::where('id', $id)->where('status', 'pending')->with('editable', 'maker')->first();


        $data->delete();

        return response()->json([
            "message" => "deleted successfully"

        ], 200);
    }
}

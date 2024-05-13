<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $fields = $request->query('fields');
        $search = $request->query('search');
        $sort = $request->query('sort');
        $limit = $request->query('limit');

        // Explode the fields parameter to get an array of selected fields
        $selectedFields = explode(',', $fields);

        $users = User::
        when($search, function ($query) use ($search) {
            $query->where('firstname', 'LIKE', "%$search%")
                ->orWhere('lastname', 'LIKE', "%$search%")
                ->orWhere('age', 'LIKE', "%$search%")
                ->orWhere('nickname', 'LIKE', "%$search%");
        })
        ->limit($limit)
        ->orderBy('id', $sort)
        ->get($selectedFields); // Passing the selected fields to get only those columns

        return response()->json(['message' => 'Users Display Successfully', 'result' => $users], 200);
    }


    public function store(Request $request){

        $create_user = User::create([
            "firstname" => $request["firstname"],
            "lastname" => $request["lastname"],
            "age" => $request["age"],
            "nickname" => $request["nickname"],
        ]);

        return response()->json(['message' => 'User Created Successfully', 'result' => $create_user], 200);
        
    }
    

    public function update(Request $request, $id) {

        $userID = User::find($id);

        if (!$userID) {
            return response()->json([
                'status_code' => "404",
                'message' => "User not found"
                ], 404);
        }

        $userID->update([
            "firstname" => $request["firstname"],
            "lastname" => $request["lastname"],
            "age" => $request["age"],
            "nickname" => $request["nickname"],
        ]);
       
        return response()->json(['message' => 'User Updated Successfully', 'result' => $userID], 200);
    }

    public function destroy(Request $request, $id) {

        $userID = User::find($id);

        if (!$userID) {
            return response()->json([
                'status_code' => "404",
                'message' => "User not found"
                ], 404);
        }

        $userID->delete();
       
        return response()->json(['message' => 'User Deleted Successfully', 'result' => $userID], 200);
    }

}

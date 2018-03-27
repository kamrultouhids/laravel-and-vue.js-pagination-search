<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = User::query();
            if ($request->filter != '') {
                $user = User::where('name', 'like', '%' . $request->filter . '%');
            }
        $user = $user->paginate(10);
        return response()->json($user);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'password'          => 'required|confirmed|min:6',
            'email'             => 'required|unique:users,email',
        ]);

        $request->request->add(['password'   =>  Hash::make($request->password)]);

        try{
            User::create($request->all());
            return response()->json(['status'=>'success','message'=>'User successfully saved !']);
        }
        catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>'Something Error Found !, Please try again']);
        }
    }


    public function edit($id)
    {
        try {
            $data = User::findOrFail($id);
             return response()->json(['status'=>'success','data'=>$data]);
        }catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>'No data found !']);
        }
    }


    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'name'              => 'required',
            'email'             =>'required|unique:users,email,'.$id.',id',
        ]);

        try{
            $data = User::findOrFail($id);
            $data->update($request->all());
            return response()->json(['status'=>'success','message'=>'User successfully updated !']);
        }
        catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>'Something Error Found !, Please try again']);
        }
    }


    public function destroy($id)
    {
        try{
            $data = User::findOrFail($id);
            $data->delete();
            return response()->json(['status'=>'success','message'=>'User successfully deleted !']);
        }
        catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>'Something Error Found !, Please try again']);
        }
    }

}

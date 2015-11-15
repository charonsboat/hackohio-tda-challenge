<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Return a listing of the resources.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::lists('username');
        return response()->json($users);
    }

    /**
     * Return the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Create a new resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $payload = $request->json()->all();
    }

    /**
     * Update the specified resource.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        dd($id);
    }

    /**
     * Patch the specified resource.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function patch(Request $request, $id)
    {
        //
    }

    /**
     * Delete the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        //
    }
}

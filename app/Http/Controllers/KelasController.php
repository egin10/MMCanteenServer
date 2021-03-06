<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Kelas;

class KelasController extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt.auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelas = Kelas::all();
        foreach($kelas as $data) {
            $data->detail_kelas = [
                'link' => 'api/v1/kelas/' . $data->id,
                'method' => 'GET'
            ];
        }

        $response = [
            'status' => 1,
            'msg' => 'List of Kelas',
            'kelas' => $kelas
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $name = $request->input('name');
        
        $kelas = new Kelas([
            'name' => $name
        ]);

        // Kelas check
        if(Kelas::where('name', $name)->first()) {
            $response = [
                'status' => 0,
                'msg' => 'Kelas is already exist',
            ];

            return response()->json($response);
        }

        if($kelas->save()) {
            $response = [
                'status' => 1,
                'msg' => 'Kelas created',
                'kelas' => $kelas,
                'link' => 'api/v1/kelas',
                'method' => 'GET'
            ];

            return response()->json($response, 201);
        }

        $response = [
            'status' => 0,
            'msg' => 'An Error occured'
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kelas = Kelas::find($id);
        
        if($kelas == '') {
            return response()->json(['status' => 0,'msg' => 'Kelas not found'], 200);
        } else {
            $kelas->update = [
                'link' => 'api/v1/kelas/' . $kelas->id,
                'method' => 'PATCH'
            ];
        }

        $response = [
            'status' => 1,
            'msg' => 'Detail kelas',
            'kelas' => $kelas
        ];

        return response()->json($kelas, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        $name = $request->input('name');
        $kelas = Kelas::find($id);

        if($kelas == '') {
            return response()->json(['status' => 0,'msg' => 'Kelas not found'], 200);
        } else {
            $kelas->name = $name;
        }

        if(!$kelas->update()) {
            return response()->json([
                'status' => 2,
                'msg' => 'Error during update'
            ]);
        }

        $response = [
            'status' => 1,
            'msg' => 'Kelas updated',
            'kelas' => $kelas
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if($kelas == '') {
            return response()->json(['status' => 0,'msg' => 'Kelas not found'], 200);
        }

        if(!$kelas->delete()) {
            return response()->json([
                'status' => 0,
                'msg' => 'Delete failed'
            ]);
        }

        $response = [
            'status' => 1,
            'msg' => 'Kelas deleted',
            'create' => [
                'link' => 'api/v1/kelas',
                'method' => 'POST',
                'params' => 'name'
            ]             
        ];

        return response()->json($response, 200);
    }
}

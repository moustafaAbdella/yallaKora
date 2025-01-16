<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ServerRequest;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    // create a new server in the database
    public function store(ServerRequest $request)
    {
        $server = new Server();
        $server->fill($request->all());
        $server->save();

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $server
        ];

        return response()->json($data, $data['status']);
    }
     /**
     * Search Servers by name or id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $categories = Server::where('name', 'like', '%'. $request->input('q') . '%')
        ->limit(6)->get();

        return response()->json($categories, 200);
    }
        // returns all genres for the api
    public function index()
    {
        return response()->json(Server::All(), 200);
    }
    

    // returns all server for admin panel
    public function data()
    {
        return response()->json(Server::paginate(13), 200);
    }

    // update a server from database
    public function update(ServerRequest $request, Server $server)
    {
        $server->fill($request->all());
        $server->save();
        $data = [
            'status' => 200,
            'message' => 'successfully updated',
            'body' => $server
        ];

        return response()->json($data, $data['status']);
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Livetv  $livetv
     * @return \Illuminate\Http\Response
     */
    public function changeEmbed(Request $request, $server)
    {
        
        $server = Server::find($server);

        if($server != null) {
            $server->embed = $request->embed === 'true'? 1: 0;
            $server->save();
    
            $data = [
                'status' => 200,
                'message' => 'تم تغير بنجاح',
                'body' => $server
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد قناة',
                'body' => $server
            ];
        }
        // $this->onUpdateCache();
        return response()->json($data, $data['status']);
    }

    // delete a server from database
    public function destroy(Server $server)
    {
        if ($server != null) {
            $server->delete();
            $data = [
                'status' => 200,
                'message' => 'successfully deleted',
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be deleted',
            ];
        }

        return response()->json($data, $data['status']);
    }
}

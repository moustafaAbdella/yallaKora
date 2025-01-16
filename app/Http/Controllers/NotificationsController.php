<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Notification\NotificationFb;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\NotificationRequest;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->q;

        if(isset($query)) {
           $notifications = Notifications::where('key', 'LIKE', "%$query%")->orWhere('value', 'LIKE', "%$query%")->limit(6)->get();
        }else{
            return [];
        }
        return response()->json($notifications, 200);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        return response()->json(Notifications::orderByDesc('created_at')
        ->paginate(6), 200);
    }

    // create a new genre in the database
    public function store(NotificationRequest $request)
    {
        $notification = new Notifications();

        $notification->fill($request->all());

        $notification->save();
        $notification = Notifications::find($notification->id);

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $notification
        ];

        return response()->json($data, $data['status']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(NotificationRequest $request, $notification)
    {
        $notification = Notifications::find($notification);
        if($notification != null) {
            $notification->fill($request->all());
            $notification->save();
            $data = [
                'status' => 200,
                'message' => 'تم تحديث بطولة بنجاح',
                'body' => $notification
            ];
        }else {
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد بطولة',
                'body' => $notification
            ];
        }

        return response()->json($data, $data['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy($notification)
    {
        $notification = Notifications::find($notification);

        if ($notification != null) {

            $notification->delete();

            $data = [
                'status' => 200,
                'message' => 'successfully removed',
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be deleted',
            ];
        }

        return response()->json($data, $data['status']);

    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'type' => 'required|in:main,player',
            'image'=> 'nullable'
        ]);
        try {
            $notification = new NotificationFb(type:$request->type);
            $body = $notification->sendMsgToAllUser(title: $request->title, body: $request->body, image: $request->image,type:$request->type);
            $data = [
                'status' => 200,
                'message' => 'تم ارسال اشعار بنجاح',
                'body' => $body
            ];
        }catch (\Exception $exception){
            $data = [
                'status' => 400,
                'message' => $exception->getMessage(),
                'body' => []
            ];
        }
        return response()->json($data, $data['status']);
    }
}

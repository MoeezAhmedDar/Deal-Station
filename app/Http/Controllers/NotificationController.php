<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private $page_heading;

    public function __construct()
    {
        $this->page_heading = 'Notification';
    }

    public function index()
    {
        $page_title = __($this->page_heading);
        return view('notification.index', compact('page_title'));
    }

    public function savePushNotificationToken(Request $request)
    {
        Auth::user()->update(['device_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

    public function sendPushNotification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $FIREBASE_API_KEY = env('FIREBASE_API_KEY');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . env('FIREBASE_API_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $res = json_decode($response);

        return redirect()->route('notification.index')
            ->with('success', 'Notification has been created successully');
    }
}

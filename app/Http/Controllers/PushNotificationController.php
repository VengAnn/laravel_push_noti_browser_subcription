<?php

namespace App\Http\Controllers;

use App\Models\PushNotification;
use Illuminate\Http\Request;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushNotificationController extends Controller
{

    public function sendNotification(Request $request)
    {

        // Public Key:
        // BBAUMFmXYjwgnzwKv3vfl799l-Ze9XHn6hsTEpvvMegcKq8JhtLwSDoeu_unSrbSApMphZk6l4xWKlFSw_ITCnw

        // Private Key:
        // swyeZxFX6fTvzjy01FnCxc3G56Wa042EMWBVDOniqlc 

        $auth = [
            'VAPID' => [
                'subject' => 'https://honeydew-herring-622934.hostingersite.com/', // can be a mailto: or your website address
                'publicKey' => 'BBAUMFmXYjwgnzwKv3vfl799l-Ze9XHn6hsTEpvvMegcKq8JhtLwSDoeu_unSrbSApMphZk6l4xWKlFSw_ITCnw',
                'privateKey' => 'swyeZxFX6fTvzjy01FnCxc3G56Wa042EMWBVDOniqlc',
                //'privateKey' => env('PUSH_NOTIFICATION_PRIVATE_KEY'), 
            ],
        ];
        $webPush = new WebPush($auth);

        $payload = json_encode([
            'title' => $request->title,
            'body' => $request->body,
            'url' => './?id=' . $request->idOfProduct,
        ]);

        // Fetch all stored notifications
        $notifications = PushNotification::all();

        foreach ($notifications as $notification) {
            // Decode the stored subscription JSON string
            $subscriptionData = json_decode($notification->subscriptions, true);

            if (is_array($subscriptionData)) {
                $webPush->sendOneNotification(
                    Subscription::create($subscriptionData),
                    $payload,
                    ['TTL' => 5000]
                );
            } else {
                return response()->json(['error' => 'Invalid subscription format'], 400);
            }
        }

        return response()->json(['message' => 'Notification sent successfully'], 200);
    }


    public function saveSubscription(Request $request)
    {
        $items = new PushNotification();
        $items->subscriptions = $request->sub; // Store raw JSON
        $items->save();

        return response()->json(['message' => 'added successfully'], 200);
    }
}

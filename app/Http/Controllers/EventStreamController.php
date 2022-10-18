<?php

namespace App\Http\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventStreamController extends Controller
{
    /**
     * @return void
     */
    public function stream(): void
    {
        // Server-Sent Events

        $countOfUsers = User::count();

        $chunk = 5000;

        $response = new StreamedResponse();

        $response->setCallback(function () use ($chunk, $countOfUsers) {

            User::chunk($chunk, function($users) use ($countOfUsers) {
                $data = [
                    'data' => $users,
                    'dataCount' => $countOfUsers,
                ];

                echo 'data: ' . json_encode($data). "\n\n";

                if (connection_aborted()) {
                    return;
                }

                sleep(3);
            });
        });

        // Indicates that server is aware of server sent events
        $response->headers->set('Content-Type', 'text/event-stream');

        // Disable caching of response
        $response->headers->set('Cache-Control', 'no-cache');

        $response->headers->set('X-Accel-Buffering', 'no');

        $response->send();
    }
}

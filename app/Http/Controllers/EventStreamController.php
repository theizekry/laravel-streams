<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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

        $chunk = 100;

        // this many times the loop should go on
        $iterations = ceil($countOfUsers / $chunk);

        $response = new StreamedResponse();

        $response->setCallback(function () use ($chunk, $iterations) {

            User::chunk($chunk, function($users) use ($iterations) {

                $data = [
                    'data' => $users,
                    'is_last' => (Cache::get('counter', 1) === $iterations),
                ];

                if (! empty($users))  {
                    echo 'data: ' . json_encode($data). "\n\n";

                    ob_flush();
                    flush();

                    Cache::increment('counter');
                }

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

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

        $response = new StreamedResponse();

        $response->setCallback(function () {

            User::chunk(3000, function($users) {
                if (! empty($users))
                {
                    echo 'data: ' . json_encode($users). "\n\n";

                    ob_flush();
                    flush();
                }

                if (connection_aborted()) { return; }

                sleep(3);
            });
        });

        echo 'data: {"isLastChunk": "last"}';

        // Indicates that server is aware of server sent events
        $response->headers->set('Content-Type', 'text/event-stream');

        // Disable caching of response
        $response->headers->set('Cache-Control', 'no-cache');

        $response->headers->set('X-Accel-Buffering', 'no');

        $response->send();
    }
}

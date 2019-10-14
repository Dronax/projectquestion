<?php

namespace App\Http\Controllers;

use App\Theard;

class TheardSubscriptionController extends Controller
{
    public function store($channelId, Theard $theard)
    {
        $theard->subscribe();
    }

    public function destroy($channelId, Theard $theard)
    {
        $theard->unsubscribe();
    }
}

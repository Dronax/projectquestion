<?php

namespace App\Http\Controllers;

use App\Theard;

class TheardSubscriptionController extends Controller
{
    public function store(Theard $theard)
    {
        $theard->subscribe();
    }

    public function destroy(Theard $theard)
    {
        $theard->unsubscribe();
    }
}

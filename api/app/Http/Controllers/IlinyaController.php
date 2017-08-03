<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ilinya\Webhook\Entry;
use App\Jobs\BotHandler;
use Illuminate\Support\Facades\Log;


class IlinyaController extends Controller
{
    public function receive(Request $request){
        $entries = Entry::getEntries($request);
        Log::info(print_r($entries, true));
        foreach ($entries as $entry) {
            $messagings = $entry->getMessagings();
            foreach ($messagings as $messaging) {
                dispatch(new BotHandler($messaging));
            }
        }
        //print_r($entries);
        return response("", 200);
    }
}

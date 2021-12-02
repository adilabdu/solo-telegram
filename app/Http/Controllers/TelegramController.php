<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    
    public function start(BotMan $bot) {
        $user = $bot->getUser();
        $bot->reply('Congrats '.$user->getId().', you have triggered the command successfully!');
    }

    public function fallback(BotMan $bot) {
        $bot->reply('Sorry, I did not get that, try running command "\help"');
    }

}

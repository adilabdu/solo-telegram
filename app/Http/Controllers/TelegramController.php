<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    
    public function start(BotMan $bot) {
        $bot->reply('Start command triggered successfully!');
    }

}
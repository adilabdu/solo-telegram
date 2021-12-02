<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\User;

class TelegramController extends Controller
{
    
    public function start(BotMan $bot) {
        
        $userID = $bot->getUser()->getId();
        
        if(User::where('telegram_id', $userID)->first()) {
            $bot->reply('You have already started the bot!');
        } else {
            User::create([
                "telegram_id" => $userID
            ]);
            $bot->reply('Congrats '.$userID.', you have triggered the command successfully!');

        }
    }

    public function fallback(BotMan $bot) {
        $bot->reply('Sorry, I did not get that, try running command "\help"');
    }

}

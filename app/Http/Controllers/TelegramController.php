<?php

namespace App\Http\Controllers;

use App\Conversations\UserConversation;
use BotMan\BotMan\BotMan;
use App\User;
use Exception;

class TelegramController extends Controller
{
    
    public function start(BotMan $bot) {
        
        $userID = $bot->getUser()->getId();

        try {

            if(User::where("telegram_id", "=", $userID)->exists()) {
                $bot->reply("You have already registered. Thank you for participating!");
            } else {
                $bot->startConversation(new UserConversation($userID));
            }           

        } catch(Exception $e) {
            $bot->reply('Exception: ' + $e);
        }

    }

    public function fallback(BotMan $bot) {
        $bot->reply('Sorry, I did not get that, try running command "\help"');
    }

}

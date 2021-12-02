<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\User;
use Error;

class TelegramController extends Controller
{
    
    public function start(BotMan $bot) {
        
        $userID = $bot->getUser()->getId();

        try {
            User::create([
                "telegram_id" => $userID,
                "name" => $bot->getUser()->getFirstName()
            ]);            

            $bot->reply('User created, with name ' . $bot->getUser()->getFirstName());

        } catch(Error $e) {
            $bot->reply('Error: ' + $e);
        }

        // if(User::where('telegram_id', $userID)->exists()) {
        //     $bot->reply('You have already started the bot!');
        // } else {
        //     User::create([
        //         "telegram_id" => $userID
        //     ]);
        //     $bot->reply('Congrats '.$userID.', you have triggered the command successfully!');

        // }
    }

    public function fallback(BotMan $bot) {
        $bot->reply('Sorry, I did not get that, try running command "\help"');
    }

}

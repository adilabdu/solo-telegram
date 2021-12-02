<?php

namespace App\Http\Controllers;

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
                User::create([
                    "telegram_id" => $userID,
                    "name" => $bot->getUser()->getFirstName(),
                    // "contact" => "Right here, where else 90210",
                    // "profession" => "Arts Curator",
                    // "organization" => "Independent Arts Foundation",
                    // "reason" => "Networking, Connections"
                ]); 

                $bot->reply('Successfully registered, with name ' . $bot->getUser()->getFirstName());
            }           

        } catch(Exception $e) {
            $bot->reply('Exception: ' + $e);
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

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

    public function export() {
        $fileName = 'registered_users.csv';
        $users = User::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Name', 'Contact', 'Profession', 'Organization', 'Reason');

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $row['Name']  = $user->name;
                $row['Contact']    = $user->contact;
                $row['Profession']    = $user->profession;
                $row['Organization']  = $user->organization;
                $row['Reason']  = $user->reason;

                fputcsv($file, array($row['Name'], $row['Contact'], $row['Profession'], $row['Organization'], $row['Reason']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}

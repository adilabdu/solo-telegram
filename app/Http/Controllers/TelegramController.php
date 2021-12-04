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
                $bot->reply("You have already registered. Thank you for joining!");
            } else {
                $bot->startConversation(new UserConversation($userID));
            }           

        } catch(Exception $e) {
            $bot->reply('Exception: ' . $e);
        }

    }

    public function help(BotMan $bot) {

        $htmlText = "This is <b>SOLO SOLO SOLO a performing arts network </b> – linking artists, researchers, and programmers whose work center the East African performing arts practice, narrative, and archive. 
 
Are you a performing artist living and working in East Africa, join our network and activate the SOLO SOLO SOLO annual program physically and/or online.";

        $bot->reply($htmlText, ["parse_mode" => "HTML"]);
    }

    public function fallback(BotMan $bot) {
        $bot->reply('Sorry, I did not get that, try running command /help');
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

        $columns = array('No.', 'Name', 'Contact', 'Profession', 'Organization', 'Reason for Participation', 'Registered Date');

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $roll = 1;
            foreach ($users as $user) {
                $row['No.'] = $roll;
                $row['Name']  = $user->name;
                $row['Contact']    = $user->contact;
                $row['Profession']    = $user->profession;
                $row['Organization']  = $user->organization;
                $row['Reason for Participation']  = $user->reason;
                $row['Registered Date'] = $user->created_at;

                fputcsv($file, array($row['No.'], $row['Name'], 
                                $row['Contact'], $row['Profession'], 
                                $row['Organization'], $row['Reason for Participation'], 
                                $row['Registered Date'])
                            );
            
                $roll++;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}

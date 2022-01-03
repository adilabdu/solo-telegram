<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class NewUserConversation extends UserConversation
{

    public function __construct($userID) {
        $this->telegram_id = $userID;
    }
 
    public function askFullName() {
        $this->say('Thank you for your interest to join our network.' . chr(10) . chr(10) . 'SOLO SOLO SOLO aims to serve as a fortitude and a life-line for moments of shared crisis. & advocate for the vitality of performing arts to influence policy and action. Share with us your name + contact to get registered on the network.');
        $this->say('ሶሎ ሶሎ ሶሎ የኪነት ጥበብ ጋር በተያያዘ የሚሰሩ የተለያዩ ባለሙያዎችን የሚያገናኝ መድረክ ሲሆን በውስጡም የጥበብ ባለሙያዎችን፣ ተመራማሪዎችን እና ፕሮግራመሮችን ያገናኛል። ትኩረቱም በምስራቅ አፍሪካ የኪነት ጥበብ፣ ታሪክ እና ጭብጥ ላይ ነው።');

        $this->ask('Full Name / ሙሉ ስም', function(Answer $answer) {

            $this->fullName = $answer->getText();

            $this->say('Nice to meet you '.$this->fullName);
            $this->askPhone();
        });
    }

    public function askPhone() {
        $this->ask('Phone Number / ስልክ ቁጥር', function(Answer $answer) {

            $this->phone = $answer->getText();

            $this->say('GREAT!');
            $this->askEmail();
        });
    }

    public function askEmail() {
        $question = Question::create("EMAIL / ኢሜል")
            ->addButtons([
                Button::create("Skip / ዝለል ⏩")->value('skip'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->updateInfo($answer->getValue());
            } else {
                $this->email = $answer->getText();
                $this->storeUser();
            }
        }, ["parse_mode" => "HTML"]);
    }

    public function askProfession() {
        $this->ask('So, what do you do, ' . $this->fullName . '?', function(Answer $answer) {

            $this->profession = $answer->getText();

            $this->say('That is awesome!');
            $this->askOrganization();
        });
    }

    public function askOrganization() {
        $this->ask('And, where do you do it?', function(Answer $answer) {

            $this->organization = $answer->getText();

            $this->say('Ooh fancy... ');
            $this->askReason();
        });
    }

    public function askReason() {
        $this->ask('One last thing, and I promise I will let you go. Please describe why you want to join.', function(Answer $answer) {

            $this->reason = $answer->getText();

            $this->say('Great! So, just to confirm... ');
            $this->confirm();
        });        
    }

    public function editAnswers() {

        $question = Question::create("Which info do you want to edit?")
            ->addButtons([
                Button::create('Name')->value('name'),
                Button::create('Phone')->value('phone'),
                Button::create('Email')->value('email'),
//                Button::create('Profession')->value('profession'),
//                Button::create('Organization')->value('organization'),
//                Button::create('Reason for joining')->value('reason'),
                Button::create('Never mind -- take me back 🔙')->value('return'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->updateInfo($answer->getValue());
            }
        }, ["parse_mode" => "HTML"]);
    }

    private function updateInfo(String $value) {

        switch ($value) {
            case 'name':
                $this->ask("Full Name / ሙሉ ስም", function(Answer $answer) {
                    $this->fullName = $answer->getText();
                    $this->reconfirm();
                });
                break;
            case 'phone':
                $this->ask("Phone Number / ስልክ ቁጥር", function(Answer $answer) {
                    $this->phone = $answer->getText();
                    $this->reconfirm();
                });
                break;
            case 'email':
                $this->ask("EMAIL / ኢሜል", function(Answer $answer) {
                    $this->email = $answer->getText();
                    $this->reconfirm();
                });
                break;
            case 'profession':
                $this->ask("What's your profession?", function(Answer $answer) {
                    $this->profession = $answer->getText();
                    $this->reconfirm();
                });
                break;
            case 'organization':
                $this->ask("Where do you work (organization)?", function(Answer $answer) {
                    $this->organization = $answer->getText();
                    $this->reconfirm();
                });
                break;
            case 'reason':
                $this->ask("Why do you want to join SOLO SOLO SOLO?", function(Answer $answer) {
                    $this->reason = $answer->getText();
                    $this->reconfirm();
                });
                break;
            case 'return':
            case 'skip':
                $this->confirm();
                break;
        }
    }

    private function reconfirm() {
        $this->say('Great! So, <i>again</i> just to confirm... ', ["parse_mode" => "HTML"]);
        $this->confirm();
    }


    public function run() {
        $this->askFullName();
    }

}

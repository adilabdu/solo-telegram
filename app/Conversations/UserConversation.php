<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\User;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class UserConversation extends Conversation
{

    protected $fullName;
    protected $phone;
    protected $profession;
    protected $organization;
    protected $reason;
    protected $email;
    protected $telegram_id;

    protected function storeUser() {

        User::create([
            "telegram_id" => $this->telegram_id,
            "name" => $this->fullName,
            "phone" => $this->phone,
            "email" => $this->email ?: '❌',
            "profession" => $this->profession,
            "organization" => $this->organization,
            "reason" => $this->reason
        ]);
        $this->say("THANK YOU!" . chr(10) . chr(10) . "WELCOME TO SOLO SOLO SOLO DANCE NETWORK. WE WILL SEND CONFIRMATION OF MEMBERSHIP ON PHONE AND/OR EMAIL.");
        $this->say("እንኳን ወደ ሶሎ ሶሎ ሶሎ የኪነት ጥበብ ባለሙያዎችን መድረክ መጡ" . chr(10) . chr(10) . "የአባልት ማረጋገገጫ በስልክ ወይም ኢሜል ይጠብቁ");
    }

    public function confirm() {

//        $markdownText = '
//<b>Name</b>   '.$this->fullName. '
//<b>Profession</b>   '.$this->profession. '
//<b>Organization</b>   '.$this->organization. '
//
//<b>Reason for joining SOLO</b>   '.$this->reason;

        $markdownText = '
<b>Name</b>   '.$this->fullName. '
<b>Phone</b>   '.$this->phone. '
<b>Email</b>   '.$this->email;

        $question = Question::create($markdownText)
            ->addButtons([
                Button::create('Edit 📝')->value('edit'),
                Button::create('Looks good 👌')->value('perfect'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'edit') {
                    $this->editAnswers();
                } else {
                    $this->storeUser();
                }
            }
        }, ["parse_mode" => "HTML"]);
    }

//    public function editAnswers() {
//
//        $question = Question::create("Which info do you want to edit?")
//            ->addButtons([
//                Button::create('Name')->value('name'),
//                Button::create('Contact')->value('contact'),
//                Button::create('Profession')->value('profession'),
//                Button::create('Organization')->value('organization'),
//                Button::create('Reason for joining')->value('reason'),
//                Button::create('Never mind -- take me back 🔙')->value('return'),
//            ]);
//
//        $this->ask($question, function (Answer $answer) {
//            if ($answer->isInteractiveMessageReply()) {
//                $this->updateInfo($answer->getValue());
//            }
//        }, ["parse_mode" => "HTML"]);
//    }
//
//    private function updateInfo(String $value) {
//
//        switch ($value) {
//            case 'name':
//                $this->ask("What's your full name?", function(Answer $answer) {
//                    $this->fullName = $answer->getText();
//                    $this->reconfirm();
//                });
//                break;
//            case 'contact':
//                $this->ask("How shall we reach you?", function(Answer $answer) {
//                    $this->contactInfo = $answer->getText();
//                    $this->reconfirm();
//                });
//                break;
//            case 'profession':
//                $this->ask("What's your profession?", function(Answer $answer) {
//                    $this->profession = $answer->getText();
//                    $this->reconfirm();
//                });
//                break;
//            case 'organization':
//                $this->ask("Where do you work (organization)?", function(Answer $answer) {
//                    $this->organization = $answer->getText();
//                    $this->reconfirm();
//                });
//                break;
//            case 'reason':
//                $this->ask("Why do you want to join SOLO SOLO SOLO?", function(Answer $answer) {
//                    $this->reason = $answer->getText();
//                    $this->reconfirm();
//                });
//                break;
//            case 'return':
//                $this->confirm();
//                break;
//        }
//    }
//
//    private function reconfirm() {
//        $this->say('Great! So, <i>again</i> just to confirm... ', ["parse_mode" => "HTML"]);
//        $this->confirm();
//    }


    public function run() { }

}

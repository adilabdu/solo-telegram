<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\User;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Foundation\Inspiring;

class UserConversation extends Conversation
{

    protected $fullName;
    protected $contactInfo;
    protected $profession;
    protected $organization;
    protected $reason;
    protected $telegram_id;

    public function __construct($userID) {
        $this->telegram_id = $userID;
    }
 
    public function askFullName() {
        $this->ask('What is your full name?', function(Answer $answer) {

            $this->fullName = $answer->getText();

            $this->say('Nice to meet you '.$this->fullName);
            $this->askContact();
        });
    }

    public function askContact() {
        $this->ask('Send us your contact so we can reach you (Email / Phone / Both)', function(Answer $answer) {

            $this->contactInfo = $answer->getText();

            $this->say('Great! Now it is almost like we are family!');
            $this->askProfession();
        });
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

    protected function storeUser() {

        // User inputs
        // $this->say($this->fullName);
        // $this->say($this->telegram_id);
        // $this->say($this->contactInfo);
        // $this->say($this->profession);
        // $this->say($this->organization);
        // $this->say($this->reason);
        
        User::create([
            "telegram_id" => $this->telegram_id,
            "name" => $this->fullName,
            "contact" => $this->contactInfo,
            "profession" => $this->profession,
            "organization" => $this->organization,
            "reason" => $this->reason
        ]);
        $this->say('Perfect! That is it. Welcome to the club. And remember, first rule of SOLO, is never talk about SOL-- of course we kid! Tell everyone you know!');
    }

    public function confirm() {

        $markdownText =
'<b>Name</b>   '.$this->fullName. '
<b>Contact</b>   '.$this->contactInfo. '
<b>Profession</b>   '.$this->profession. '
<b>Organization</b>   '.$this->organization. '
<b>Reason for joining SOLO</b>   '.$this->reason;

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

    public function editAnswers() {

        $question = Question::create("Which answer do you want to edit?")
            ->addButtons([
                Button::create('Name')->value('name'),
                Button::create('Contact')->value('contact'),
                Button::create('Profession')->value('profession'),
                Button::create('Organization')->value('organization'),
                Button::create('Reason for joining')->value('reason'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->updateAnswer($answer->getValue());
            }
        }, ["parse_mode" => "HTML"]);
    }

    private function updateAnswer(String $value) {

        switch ($value) {
            case 'name':
                $this->ask("What's your full name?", function(Answer $answer) {
                    $this->fullName = $answer->getText();
                    $this->reconfirm();
                });
                break;
            case 'contact':
                $this->ask("How shall we reach you?", function(Answer $answer) {
                    $this->contactInfo = $answer->getText();
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
        }
    }
    
    private function reconfirm() {
        $this->say('Great! So, <i>again</i> just to confirm... ', ["parse_mode" => "HTML"]);
        $this->confirm();
    }

    // Start conversation
    public function run()
    {
        $this->askFullName();
    }
}

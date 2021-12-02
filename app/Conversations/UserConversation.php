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

            $this->say('Perfect! That is it. Welcome to the club. And remember, first rule of SOLO, is never talk about SOL-- of course we kid! Tell everyone you know!');
            $this->storeUser();
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

        $this->confirm();
    }

    public function confirm() {
        $question = Question::create("Thanks! That's all we need. Just to confirm: ", ["parse_mode" => "Markdown"])
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Edit')->value('edit'),
                Button::create('Perfect')->value('perfect'),
            ]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'joke') {
                    $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                    $this->say($joke->value->joke);
                } else {
                    $this->say(Inspiring::quote());
                }
            }
        });
    }

    // Start conversation
    public function run()
    {
        $this->askFullName();
    }
}

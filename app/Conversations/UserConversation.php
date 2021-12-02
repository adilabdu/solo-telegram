<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class UserConversation extends Conversation
{

    protected $fullName;
    protected $contactInfo;
    protected $profession;
    protected $organization;
    protected $reason;
 
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

            $this->say('Perfect! That is it. Welcome to the club. And remember, first rule of SOLO, is never talk about -- of course we kid. Tell everyone you know!');
        });
    }

    // Start conversation
    public function run()
    {
        $this->askFullName();
    }
}

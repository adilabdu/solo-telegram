<?php
use App\Http\Controllers\BotManController;
use App\Http\Controllers\TelegramController;

$botman = resolve('botman');

$botman->hears('/start', TelegramController::class.'@start');

//$botman->hears('/view', TelegramController::class.'@view');

$botman->hears('/help', TelegramController::class.'@help')->stopsConversation();

$botman->hears('/exit', function() {
    $this->reply("No worries! Just type /start whenever you want to register.");
})->stopsConversation();

$botman->fallback(TelegramController::class.'@fallback');
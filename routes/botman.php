<?php
use App\Http\Controllers\BotManController;
use App\Http\Controllers\TelegramController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->hears('/start', TelegramController::class.'@start');

$botman->fallback(TelegramController::class.'@fallback');
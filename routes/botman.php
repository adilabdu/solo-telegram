<?php
use App\Http\Controllers\BotManController;
use App\Http\Controllers\TelegramController;

$botman = resolve('botman');

$botman->hears('/start', TelegramController::class.'@start');

//$botman->hears('/view', TelegramController::class.'@view');

$botman->hears('/help', TelegramController::class.'@help')->stopsConversation();

$botman->fallback(TelegramController::class.'@fallback');
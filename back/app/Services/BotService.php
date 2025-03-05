<?php

namespace app\Services;

use App\Classes\BotEventHandler;
use App\Models\TgBot;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
use Illuminate\Support\Facades\Log;

class BotService
{
    public function getMadeline($botId)
    {
        [$settings, $bot, $sessionPath] = $this->makeMadelineConfig($botId);

        if (file_exists($sessionPath)) {
            Log::info("Using existing session (bot_id={$botId})");
            $madeline = new API($sessionPath);
            $madeline->start();
        } else {
            Log::info("Creating new session (bot_id={$botId})");
            $madeline = new API($sessionPath, $settings);
            $madeline->botLogin($bot->token);
        }

        return $madeline;
    }

    public function startForeground($botId)
    {
        [$settings, $bot, $sessionPath] = $this->makeMadelineConfig($botId);

        BotEventHandler::startAndLoopBot(
            $sessionPath,
            $bot->token,
            $settings,
        );
    }

    public function startBackground($botId)
    {
        $bot = TgBot::findOrFail($botId);
        $logFile = storage_path("logs/bot_{$bot->username}.log");
        $cmd = "php artisan bot:start-fg --bot_id={$bot->id} >> {$logFile} 2>&1 &";
        exec($cmd);
    }

    public function startAllBackground()
    {
        $notRunningIds = collect($this->getStatuses())
            ->filter(fn($item) => $item['status'] === 'stopped')
            ->pluck('id');

        $bots = TgBot::findMany($notRunningIds);
        foreach ($bots as $bot) {
            $this->startBackground($bot->id);
        }
    }

    public function stop($botId)
    {
        $botToStop = collect($this->getBotProcsInfo())
            ->where('botId', $botId)
            ->first();

        if (!$botToStop) {
            return;
        }

        $cmd = "kill {$botToStop['pid']}";
        exec($cmd);
    }

    public function stopAll()
    {
        $idsToStop = collect($this->getBotProcsInfo())
            ->pluck('botId');
        foreach ($idsToStop as $id) {
            $this->stop($id);
        }
    }

    public function getStatuses()
    {
        $runningBotIds = collect($this->getBotProcsInfo())
            ->pluck('botId');

        $result = TgBot::all()->map(fn($bot) => [
            'id' => $bot->id,
            'username' => $bot->username,
            'status' => $runningBotIds->contains($bot->id)
                ? 'running' : 'stopped',
        ])
            ->toArray();

        return $result;
    }

    protected function getBotProcsInfo()
    {
        $cmd = "ps -eo pid,command | grep 'php artisan bot:start' | grep -v grep";
        exec($cmd, $output);

        $result = [];
        foreach ($output as $line) {

            preg_match('/--bot_id=(\d+)/', $line, $matches);
            if (!count($matches)) {
                continue;
            }
            $botId = $matches[1];

            preg_match('/^\s*(\d+)/', $line, $matches);
            if (!count($matches)) {
                continue;
            }
            $pid = $matches[1];

            $result[] = compact('botId', 'pid');
        }

        return $result;
    }

    protected function makeMadelineConfig($botId)
    {
        $bot = TgBot::findOrFail($botId);

        $settings = new Settings();
        $appInfo = new Settings\AppInfo();
        $appInfo->setApiId(config('services.telegram.api_id'));
        $appInfo->setApiHash(config('services.telegram.api_hash'));
        $settings->setAppInfo($appInfo);

        $sessionPath = storage_path("telegram.sessions/{$bot->username}");

        return [$settings, $bot, $sessionPath];
    }
}

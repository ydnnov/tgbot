<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTgBotRequest;
use App\Http\Resources\TgBotResource;
use App\Models\TgBot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TgBotController extends Controller
{
    public function getAll(): AnonymousResourceCollection
    {
        return TgBotResource::collection(TgBot::all());
    }

    public function getOne(TgBot $tgBot): TgBotResource
    {
        return new TgBotResource($tgBot);
    }

    public function store(StoreTgBotRequest $request): TgBotResource
    {
        $bot = TgBot::create($request->only(['username', 'token']));

        return new TgBotResource($bot);
    }

    public function destroy(TgBot $tgBot): JsonResponse
    {
        $tgBot->delete();
        return response()->json(['message' => 'Bot deleted']);
    }
}

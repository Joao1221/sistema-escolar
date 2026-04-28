<?php

namespace App\Observers;

use App\Services\AuditoriaService;
use Illuminate\Database\Eloquent\Model;

class RegistroAuditoriaObserver
{
    private static array $originais = [];

    public function updating(Model $model): void
    {
        self::$originais[spl_object_id($model)] = $model->getRawOriginal();
    }

    public function deleting(Model $model): void
    {
        self::$originais[spl_object_id($model)] = $model->getRawOriginal();
    }

    public function created(Model $model): void
    {
        app(AuditoriaService::class)->registrarModelo('criacao', $model, [], $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $key = spl_object_id($model);
        $antes = self::$originais[$key] ?? [];
        unset(self::$originais[$key]);

        app(AuditoriaService::class)->registrarModelo('alteracao', $model, $antes, $model->getAttributes());
    }

    public function deleted(Model $model): void
    {
        $key = spl_object_id($model);
        $antes = self::$originais[$key] ?? $model->getRawOriginal();
        unset(self::$originais[$key]);

        app(AuditoriaService::class)->registrarModelo('exclusao', $model, $antes, []);
    }
}

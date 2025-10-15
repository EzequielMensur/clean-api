<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/routes',
        __DIR__.'/database',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
        SetList::CODE_QUALITY,
    ]);

    // Opcional: cache para acelerar
    $rectorConfig->cacheDirectory(__DIR__.'/storage/framework/rector');
};

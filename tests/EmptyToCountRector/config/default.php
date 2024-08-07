<?php

declare(strict_types=1);

use tamagoage\Rector\EmptyToCountRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(EmptyToCountRector::class);
};

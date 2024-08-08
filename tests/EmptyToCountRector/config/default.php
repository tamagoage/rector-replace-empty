<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use tamagoage\Rector\EmptyToCountRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(EmptyToCountRector::class);
};

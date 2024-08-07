<?php

declare(strict_types=1);

namespace tamagoage\Rector\EmptyToCountRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class EmptyToCountTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest
     */
    public function test(string $file): void
    {
        var_dump($file);
        $this->doTestFile($file);
    }

    /**
     * @return Iterator<array{string}>
     */
    public function provideDataForTest(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return IsNullToIssetRector::class;
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/default.php';
    }
}

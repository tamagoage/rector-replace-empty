<?php

declare(strict_types=1);

namespace tamagoage\Rector\EmptyToCountRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use tamagoage\Rector\EmptyToCountRector;

/**
 * @covers EmptyToCountRector
 */
final class EmptyToCountTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest
     */
    public function test(string $file): void
    {
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
        return EmptyToCountRector::class;
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/default.php';
    }
}

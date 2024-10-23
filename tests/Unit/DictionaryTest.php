<?php

namespace Tests\Unit;

use Ninja\BanThis\Dictionary;
use Ninja\BanThis\Exceptions\DictionaryFileNotFound;
use PHPUnit\Framework\TestCase;

class DictionaryTest extends TestCase
{
    public function testWithLanguage()
    {
        $dictionary = Dictionary::withLanguage('en-us');
        $this->assertNotEmpty($dictionary->words());
    }

    public function testFromFile()
    {
        $file = __DIR__ . '/../../resources/dict/en-us.php';
        $dictionary = Dictionary::fromFile($file);
        $this->assertNotEmpty($dictionary->words());
    }

    public function testDictionaryFileNotFound()
    {
        $this->expectException(DictionaryFileNotFound::class);
        Dictionary::fromFile('nonexistent-file.php');
    }
}

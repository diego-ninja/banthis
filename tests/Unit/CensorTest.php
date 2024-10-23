<?php

namespace Tests\Unit;

use Ninja\BanThis\Censor;
use Ninja\BanThis\Dictionary;
use PHPUnit\Framework\TestCase;

class CensorTest extends TestCase
{
    public function testSetDictionary()
    {
        $dictionary = Dictionary::withLanguage('en-us');
        $censor = new Censor($dictionary);
        $this->assertNotEmpty($censor->clean('test')['orig']);
    }

    public function testAddDictionary()
    {
        $dictionary = Dictionary::withLanguage('en-us');
        $censor = new Censor($dictionary);
        $censor->addDictionary(Dictionary::withLanguage('fr'));

        $this->assertNotEmpty($censor->clean('test')['orig']);
    }

    public function testWhitelist()
    {
        $dictionary = Dictionary::withLanguage('en-us');
        $censor = new Censor($dictionary);
        $censor->whitelist(['test']);

        $result = $censor->clean('This is a test string');
        $this->assertEquals('This is a test string', $result['clean']);
    }

    public function testSetReplaceChar()
    {
        $dictionary = Dictionary::withLanguage('en-us');
        $censor = new Censor($dictionary);
        $censor->setReplaceChar('X');

        $result = $censor->clean('This is a dick string');
        $this->assertStringContainsString('X', $result['clean']);
    }

    public function testClean()
    {
        $dictionary = Dictionary::withLanguage('en-us');
        $censor = new Censor($dictionary);

        $result = $censor->clean('This is a test string');
        $this->assertNotEmpty($result['clean']);
    }
}

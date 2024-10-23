<?php

namespace Tests\Unit;

use Ninja\BanThis\Whitelist;
use PHPUnit\Framework\TestCase;

class WhitelistTest extends TestCase
{
    public function testAdd()
    {
        $whitelist = new Whitelist();
        $whitelist->add(['test', 'example']);

        $this->assertNotEmpty($whitelist->replace('This is a test string'));
    }

    public function testReplace()
    {
        $whitelist = new Whitelist();
        $whitelist->add(['test']);

        $result = $whitelist->replace('This is a test string');
        $this->assertStringContainsString('{whiteList0}', $result);
    }

    public function testReplaceReverse()
    {
        $whitelist = new Whitelist();
        $whitelist->add(['test']);

        $result = $whitelist->replace('This is a {whiteList0} string', true);
        $this->assertStringContainsString('test', $result);
    }
}

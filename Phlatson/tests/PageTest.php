<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Phlatson\Page;

class PageTest extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress() : void
    {
        $this->assertInstanceOf(
            Page::class,
            new Page('/')
        );
    }

}

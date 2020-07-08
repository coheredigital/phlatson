<?php

declare(strict_types=1);

namespace Phlatson;

define('ROOT_PATH', str_replace(DIRECTORY_SEPARATOR, '/', realpath(__DIR__ . '/../../') . "/"));

use \PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{

    protected $data_path = ROOT_PATH . 'site/';
    protected $finder;


    protected function setUp(): void
    {
        $this->finder = new Finder($this->data_path);
        $this->finder->addPathMapping("Page", "/pages/");
        $this->finder->addPathMapping("Field", "/pages/");
    }

    public function testFinderInstance()
    {
        $this->assertInstanceOf(
            Finder::class,
            $this->finder
        );
    }

    public function testFinderPage()
    {

        $page = $this->finder->getType("Page", "/");
        $this->assertIsObject($page);
        $this->assertInstanceOf(Page::class, $page);
    }
    public function testFinderPageValues()
    {

        $this->assertInstanceOf(
            Page::class,
            $this->finder->getType("Page", "/")
        );
    }
}

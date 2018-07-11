<?php 

declare(strict_types=1);

define('ROOT_PATH', "../../" . str_replace(DIRECTORY_SEPARATOR, '/', __DIR__ . '/'));
define('DATA_PATH', ROOT_PATH . 'site/');

use PHPUnit\Framework\TestCase;
use Phlatson\Page;
use Phlatson\PageCollection;

class PageTest extends TestCase
{
    public function testPageInstance() : Page
    {
        $page = new Page('/');
        $this->assertInstanceOf(
            Page::class,
            $page
        );
        return $page;
    }

    /**
     * @depends testPageInstance
     *
     * @return void
     */
    public function testPageChildrenCollection(Page $page) : void
    {
        $this->assertInstanceOf(
            PageCollection::class,
            $page->children()
        );
    }



}

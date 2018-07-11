<?php 

declare(strict_types=1);

namespace Phlatson;

define('ROOT_PATH', str_replace(DIRECTORY_SEPARATOR, '/', __DIR__ . '/../../'));
define('DATA_PATH', ROOT_PATH . 'site/');

use \PHPUnit\Framework\TestCase;
 
class FinderTest extends TestCase
{
    public function testFinderInstance() : Finder
    {
        $finder = new Finder(DATA_PATH);
        $this->assertInstanceOf(
            Finder::class,
            $finder
        );
        return $finder;
    }

    /**
     * @dataProvider    folderProvider
     */
    public function testValidFolders($folder)
    {
        $finder = new Finder(DATA_PATH);
        $this->assertSame(
            true,
            file_exists($finder->getPath($folder))
        );
    }
  
    /**
     * @dataProvider    folderProvider
     */
    public function testFinderObjects($folder)
    {
        $finder = new Finder(DATA_PATH);
        $this->assertInstanceOf(
            JsonObject::class,
            $finder->get($folder)
        );
    }

    public function folderProvider()
    {
        return [
            'page' => ['/pages/about/'],
            'model' => ['/models/page/'],
            'user' => ['/users/adam/']
        ];
    }
}

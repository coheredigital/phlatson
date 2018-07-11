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
     * @dataProvider    pathsProvider
     */
    public function testFinder($path)
    {
        $finder = new Finder(DATA_PATH);

        $this->assertSame(
            true,
            file_exists($finder->exists($path))
        );

        $this->assertInstanceOf(
            JsonObject::class,
            $finder->get($path)
        );
    }

    public function pathsProvider()
    {
        return [
            'page' => ['/pages/about/'],
            'model' => ['/models/article/'],
            'user' => ['/users/adam/']
        ];
    }
}

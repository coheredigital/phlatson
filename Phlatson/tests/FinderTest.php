<?php 

declare(strict_types=1);

namespace Phlatson;

define('ROOT_PATH', str_replace(DIRECTORY_SEPARATOR, '/', __DIR__ . '/../../'));

use \PHPUnit\Framework\TestCase;




class FinderTest extends TestCase
{

    protected $data_path = ROOT_PATH . 'site/';

    public function testFinderInstance() : Finder
    {
        $finder = new Finder($this->data_path);
        $this->assertInstanceOf(
            Finder::class,
            $finder
        );
        return $finder;
    }
    
    public function testFinderPage() : Finder
    {
        $finder = new Finder($this->data_path);
        $this->assertInstanceOf(
            Finder::class,
            $finder
        );
        return $finder;
    }

}
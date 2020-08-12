<?php

declare(strict_types=1);

namespace Phlatson;

define('ROOT_PATH', str_replace(DIRECTORY_SEPARATOR, '/', realpath(__DIR__ . '/../../') . '/'));

use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
	protected $data_path = ROOT_PATH . 'site/';
	protected $finder;

	protected function setUp(): void
	{
		$this->finder = new Finder($this->data_path);
		$this->finder->map('Page', '/pages/');
		$this->finder->map('Field', '/pages/');
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
		$page = $this->finder->get('Page', '/');
		$this->assertIsObject($page);
		$this->assertInstanceOf(Page::class, $page);
	}

	public function testFinderPageValues()
	{
		$this->assertInstanceOf(
			Page::class,
			$this->finder->get('Page', '/')
		);
	}
}

<?php

namespace Phlatson;

class FileCollection implements \Iterator, \Countable
{
	public $iterator;
	protected Finder $finder;
	protected int $currentPage = 1;
	protected int $limit = 0;
	protected int $position = 0;
	protected array $files = [];
	protected array $collection = [];

	public function __construct(Finder $finder)
	{
		$this->finder = $finder;
	}

	public function append(File $item): self
	{
		$this->files[$item->file] = true;
		$this->collection[] = $item;

		return $this;
	}

	public function reverse(): self
	{
		$this->collection = array_reverse($this->collection);

		return $this;
	}

	public function import(array $collection): self
	{
		// TODO: this need work
		$files = array_fill_keys($collection, true);
		$this->files += $files;
		$this->collection += $collection;

		return $this;
	}

	public function limit(int $limit): self
	{
		$this->limit = $limit;

		return $this;
	}

	public function paginate(int $currentPage): self
	{
		if ($currentPage < 1) {
			throw new \Exception('Request page number cannot be less than 1');
		}
		$this->currentPage = $currentPage;

		return $this;
	}

	public function count(): int
	{
		return count($this->collection);
	}

	public function index(): int
	{
		if ($this->limit > 0) {
			return ($this->currentPage * $this->limit) - $this->limit + $this->position;
		} else {
			return $this->position;
		}
	}

	/**
	 * Iterator Interface methods.
	 *
	 * @return void
	 */
	public function rewind(): void
	{
		$this->position = 0;
	}

	public function current(): File
	{
		$item = $this->collection[$this->index()];
		if (is_string($item)) {
			$item = $this->finder->get('File', $item);
			// replace the existing pointer
			$this->collection[$this->index()] = $item;
		}

		return $item;
	}

	public function key()
	{
		return $this->index();
	}

	public function next()
	{
		++$this->position;
	}

	public function valid()
	{
		if ($this->limit && $this->position == $this->limit) {
			return false;
		}

		return isset($this->collection[$this->index()]);
	}
}

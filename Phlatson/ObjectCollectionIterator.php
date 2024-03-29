<?php

namespace Phlatson;

class ObjectCollectionIterator implements \Iterator, \ArrayAccess, \Countable
{
	protected $currentIndex = 0;
	protected $startIndex = 0;
	protected $endIndex;
	protected $limit = 0;
	protected $pageCount;
	protected $currentPage;
	protected $isPaginated = false;
	protected $collection = [];

	public function append(object $item)
	{
		$this->collection += [$item->name => $item];

		return $this;
	}

	public function prepend(object $item)
	{
		$this->collection = [$item->name => $item] + $this->collection;

		return $this;
	}

	public function import(ObjectCollection $items)
	{
		foreach ($items as $item) {
			if (!$this->isValidItem($item)) {
				continue;
			}
			$this->append($item);
		}

		return $this;
	}

	/**
	 * @param $fieldname
	 * @param string $direction
	 *
	 * @return $this
	 */
	public function sort($fieldname, $direction = 'ASC')
	{
		$object = $this->first();

		if (!$value = $object->getUnformatted($fieldname)) {
			throw new \Exception("Cannot sort by '$fieldname' no data by that name can be found in {$this}.");
		}

		$type = get($value);

		usort(
			$this->collection,
			function ($a, $b) use ($fieldname, $type) {
				$v1 = $a->get($fieldname);
				$v2 = $b->get($fieldname);

				switch ($type) {
					case 'integer':
						if ($v1 == $v2) {
							return 0;
						}

						return ($v1 < $v2) ? -1 : 1;
					default:
						return strcmp($v1, $v2);
				}
			}
		);

		if ('DESC' == $direction) {
			$this->reverse();
		}

		return $this;
	}

	/**
	 * returns new collection with index range items.
	 *
	 * @return $this
	 */
	public function slice(int $start, $end)
	{
		// TODO : implement non destructive slice
		return $this;
	}

	/**
	 * reverses array orders.
	 *
	 * @return $this
	 */
	public function reverse()
	{
		$this->collection = array_reverse($this->collection);

		return $this;
	}

	public function has($name)
	{
		return isset($this->collection[$name]);
	}

	/**
	 * return first item in data array.
	 *
	 * @return Object
	 */
	public function first()
	{
		return $this->index(0);
	}

	/**
	 * return last item in data array.
	 *
	 * @return Object
	 */
	public function last()
	{
		return $this->index(-1);
	}

	/**
	 * return item at given index.
	 */
	public function index($x)
	{
		if (!count($this->collection)) {
			throw new \Exception("$this->className is empty, cannot retrieve index($x)");
		}

		return array_values($this->collection)[$x];
	}

	public function getArray($key, $value)
	{
		$array = [];
		foreach ($this as $object) {
			$key = $object->get($key);
			$value = $object->get($value);

			$array[$key] = $value;
		}

		return $array;
	}

	/* Interface requirements */
	public function rewind()
	{
		if ($this->currentPage > 1) {
			$this->currentIndex = ($this->currentPage - 1) * $this->limit;
		} else {
			$this->currentIndex = 0;
		}
	}

	public function current()
	{
		return $this->index($this->currentIndex);
	}

	public function key()
	{
		return array_keys($this->collection)[$this->currentIndex];
	}

	public function next()
	{
		++$this->currentIndex;
	}

	public function valid()
	{
		if ($this->limit > 0 && $this->currentIndex === ($this->endIndex + 1)) {
			return false;
		}

		return array_key_exists($this->key(), $this->collection);
	}

	/* Interface requirements */

	/**
	 * simply return the count of elments in the data container.
	 *
	 * @return int
	 */
	public function count(): int
	{
		return (int) count($this->collection);
	}

	public function offsetSet($key, $value)
	{
		$this->set($key, $value);
	}

	public function offsetGet($key)
	{
		return $this->get($key);
	}

	public function offsetUnset($key)
	{
		return $this->remove($key);
	}

	public function offsetExists($key)
	{
		return $this->has($key);
	}

	public function __get($name)
	{
		return $this->get($name);
	}
}

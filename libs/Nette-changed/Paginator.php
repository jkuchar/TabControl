<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 * @version    $Id: Paginator.php 329 2009-05-28 20:18:49Z david@grudl.com $
 */

/*namespace Nette;*/



/**
 * Paginating math.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 *
 * @property   int $page
 * @property-read int $firstPage
 * @property-read int $lastPage
 * @property   int $base
 * @property-read int $pageCount
 * @property   int $itemsPerPage
 * @property   int $itemCount
 * @property-read int $offset
 * @property-read int $countdownOffset
 * @property-read int $length
 * @property-read bool $first
 * @property-read bool $last
 */
class Paginator extends Object
{
	/** @var int */
	private $base = 1;

	/** @var int */
	private $itemsPerPage;

	/** @var int */
	private $page;

	/** @var int */
	private $itemCount = 0;



	/**
	 * Sets current page number.
	 * @param  int
	 * @return void
	 */
	public function setPage($page)
	{
		$this->page = (int) $page;
	}



	/**
	 * Returns current page number.
	 * @return int
	 */
	public function getPage()
	{
		return $this->base + $this->getPageIndex();
	}



	/**
	 * Returns first page number.
	 * @return int
	 */
	public function getFirstPage()
	{
		return $this->base;
	}



	/**
	 * Returns last page number.
	 * @return int
	 */
	public function getLastPage()
	{
		return $this->base + max(0, $this->getPageCount() - 1);
	}



	/**
	 * Sets first page (base) number.
	 * @param  int
	 * @return void
	 */
	public function setBase($base)
	{
		$this->base = (int) $base;
	}



	/**
	 * Returns first page (base) number.
	 * @return int
	 */
	public function getBase()
	{
		return $this->base;
	}



	/**
	 * Returns zero-based page number.
	 * @return int
	 */
	protected function getPageIndex()
	{
		return min(max(0, $this->page - $this->base), max(0, $this->getPageCount() - 1));
	}



	/**
	 * Is the current page the first one?
	 * @return bool
	 */
	public function isFirst()
	{
		return $this->getPageIndex() === 0;
	}



	/**
	 * Is the current page the last one?
	 * @return bool
	 */
	public function isLast()
	{
		return $this->getPageIndex() === $this->getPageCount() - 1;
	}



	/**
	 * Returns the total number of pages.
	 * @return int
	 */
	public function getPageCount()
	{
		return (int) ceil($this->itemCount / $this->itemsPerPage);
	}



	/**
	 * Sets the number of items to display on a single page.
	 * @param  int
	 * @return void
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = max(1, (int) $itemsPerPage);
	}



	/**
	 * Returns the number of items to display on a single page.
	 * @return int
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}



	/**
	 * Sets the total number of items.
	 * @param  int (or FALSE as infinity)
	 * @return void
	 */
	public function setItemCount($itemCount)
	{
		$this->itemCount = $itemCount === FALSE ? PHP_INT_MAX : max(0, (int) $itemCount);
	}



	/**
	 * Returns the total number of items.
	 * @return int
	 */
	public function getItemCount()
	{
		return $this->itemCount;
	}



	/**
	 * Returns the absolute index of the first item on current page.
	 * @return int
	 */
	public function getOffset()
	{
		return $this->getPageIndex() * $this->itemsPerPage;
	}



	/**
	 * Returns the absolute index of the first item on current page in countdown paging.
	 * @return int
	 */
	public function getCountdownOffset()
	{
		return max(0, $this->itemCount - ($this->getPageIndex() + 1) * $this->itemsPerPage);
	}



	/**
	 * Returns the number of items on current page.
	 * @return int
	 */
	public function getLength()
	{
		return min($this->itemsPerPage, $this->itemCount - $this->getPageIndex() * $this->itemsPerPage);
	}

}

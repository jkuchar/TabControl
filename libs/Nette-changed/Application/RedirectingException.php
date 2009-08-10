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
 * @package    Nette\Application
 * @version    $Id: RedirectingException.php 182 2008-12-31 00:28:33Z david@grudl.com $
 */

/*namespace Nette\Application;*/



require_once dirname(__FILE__) . '/../Application/AbortException.php';



/**
 * Abort presenter and redirects to new request.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette\Application
 */
class RedirectingException extends AbortException
{


	public function __construct($uri, $code)
	{
		parent::__construct((string) $uri, (int) $code);
	}



	/**
	 * @return string
	 */
	final public function getUri()
	{
		return $this->getMessage();
	}

}
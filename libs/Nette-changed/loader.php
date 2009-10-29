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
 */

/**
 * Check PHP configuration.
 */
if (version_compare(PHP_VERSION, '5.2.0', '<')) {
	throw new Exception('Nette Framework requires PHP 5.2.0 or newer.');
}

require_once dirname(__FILE__) . '/compatibility/PHP.php';

require_once dirname(__FILE__) . '/Loaders/NetteLoader.php';


NetteLoader::getInstance()->base = dirname(__FILE__);
NetteLoader::getInstance()->register();

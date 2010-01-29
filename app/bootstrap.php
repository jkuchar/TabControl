<?php

//header("content-style-type: text/css");
//header("content-script-type: text/javascript");
//header("content-type: text/html; charset=UTF-8");

require_once LIBS_DIR . '/Nette-changed/loader.php';

setlocale(LC_ALL, 'czech', 'cs_CZ@euro', 'cs_CZ', 'cs', 'cz');	// Nastavení lokality: Česká Republika
date_default_timezone_set('Europe/Prague');

/**
 * Automatické načítání tříd
 */

$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

/**
 * Produkční nebo vývojový mód
 */


Environment::setName(Environment::DEVELOPMENT);

$config = Environment::loadConfig();
//Debug::dump($config);

Debug::$strictMode = true;
Debug::enable(Debug::DEVELOPMENT);
// parametr logování chyb je NULL, takže se rozhodne podle autodetekce z Environment podle řežimu production, tzn: Environment::isProduction() ? 'logovat' : 'zobrazovat'

if (Environment::getName() == Environment::DEVELOPMENT)
	Debug::enableProfiler();

$application = Environment::getApplication();

$application->onStartup[] = 'BaseModel::initialize';
$application->onShutdown[] = 'BaseModel::disconnect';

/**
 * Nastavení routování
 */

$router = $application->getRouter();
/*
    $application->errorPresenter = "Error";
    $application->catchExceptions = Environment::isProduction() ? TRUE : FALSE;
*/
$router[] = new Route('index.php', array(
    'presenter' => 'Default',
    'action' => 'default',
    ), Route::ONE_WAY);

$router[] = new Route('<presenter>/<action>/<id>', array(
    'presenter' => 'Default',
    'action' => 'default',
    'id' => null,
));

/**
 * Spustí aplikaci
 */
$application->run();

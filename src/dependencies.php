<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
//Database
$container['db'] = function ($c) {
	$connectionString = $c->get('settings')['connectionString'];

	$pdo = new PDO($connectionString['dns'],$connectionString['user'],$connectionString['pass'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

	return new FluentPDO($pdo);
};
// Models

$container['model']	= function($c){

	return (object)[
		'User'	=>	new App\Model\UserModel($c->db),
		'Auth'	=>	new App\Model\AuthModel($c->db),
		'Student' => new App\Model\StudentModel($c->db),
		'Professional' => new App\Model\ProfessionalModel($c->db)
	];
};




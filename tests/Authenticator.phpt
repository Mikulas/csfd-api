<?php

use Symfony\Component\Yaml\Yaml;
use Csfd\Authentication\Authenticator;
use Csfd\Authentication\Exception;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Authentication\Authenticator');

$config = Yaml::parse(file_get_contents(__DIR__ . '/config.yml'));

$localFile = __DIR__ . '/config.local.yml';
if (is_file($localFile))
{
	$local = Yaml::parse(file_get_contents($localFile));
	$config = array_replace_recursive($config, $local);
}

if (!$config['account']['password'])
{
	Tester\Environment::skip('Password not set.');
}

$urlBuilder = UrlBuilder::factory($urlsPath);

$rf = new RequestFactory;
$rf->setRequestClass('Csfd\Networking\Request');

$auth = Access(new Authenticator($urlBuilder, new Parsers\User, new Parsers\Authentication, $rf));

Assert::null($auth->cookie);
Assert::null($auth->userId);

Assert::exception(function() use ($auth) {
	$auth->getUserId();
}, 'Csfd\Authentication\Exception', Exception::NOT_AUTHENTICATED);

Assert::exception(function() use ($auth) {
	$auth->getCookie();
}, 'Csfd\Authentication\Exception', Exception::CREDENTIALS_NOT_SET);

$auth->setCredentials($config['account']['username'], 'wrong password');
Assert::exception(function() use ($auth) {
	$auth->getCookie();
}, 'Csfd\Authentication\Exception');

$auth->setCredentials($config['account']['username'], $config['account']['password']);

Assert::match('%d%', $auth->getUserId());

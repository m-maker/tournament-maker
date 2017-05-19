<?php
require '../mangopay2-php-sdk/MangoPay/Psr/Log/LoggerInterface.php';
require '../mangopay2-php-sdk/MangoPay/Psr/Log/AbstractLogger.php';
require '../mangopay2-php-sdk/MangoPay/Psr/Log/NullLogger.php';
require '../mangopay2-php-sdk/MangoPay/Psr/Log/InvalidArgumentException.php';
require '../mangopay2-php-sdk/MangoPay/Psr/Log/LoggerAwareInterface.php';
require '../mangopay2-php-sdk/MangoPay/Psr/Log/LoggerAwareTrait.php';
require '../mangopay2-php-sdk/MangoPay/Psr/Log/LoggerTrait.php';
require '../mangopay2-php-sdk/MangoPay/Psr/Log/LogLevel.php';
require '../mangopay2-php-sdk/MangoPay/Autoloader.php';

$mangoPayApi = new \MangoPay\MangoPayApi();
$mangoPayApi->Config->ClientId = "mate-maker33";
$mangoPayApi->Config->ClientPassword = "6Dc8CbqhmCAsbT552f94MwTDsjjwXLJM3WSjOkdTqfqGMGbSEh";
$path = "../mango";
//file_put_contents('mango/lol.txt', 'salut');
$mangoPayApi->Config->TemporaryFolder = $path;

?>

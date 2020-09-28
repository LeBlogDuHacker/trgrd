<?php
//to be ran as a process (eg: php DNS_server.php &)
require "src/AbstractStorageProvider.php";
require "src/RecordTypeEnum.php";
require "src/JsonStorageProvider.php";
require "src/RecursiveProvider.php";
require "src/StackableResolver.php";
require "src/Server.php";
error_reporting(E_ALL); ini_set('display_errors', 1);
// JSON formatted DNS records file
$record_file = './dns_record.json';
$jsonStorageProvider = new yswery\DNS\JsonStorageProvider($record_file);

// Recursive provider acting as a fallback to the JsonStorageProvider
//$recursiveProvider = new yswery\DNS\RecursiveProvider;

/*$stackableResolver = new yswery\DNS\StackableResolver(array($jsonStorageProvider, $recursiveProvider));
if($stackableResolver->allows_recursion()) {
	echo "EHHOUUI\n";
}*/
// Creating a new instance of our class
$dns = new yswery\DNS\Server($jsonStorageProvider/*$stackableResolver*/);

// Starting our DNS server
$dns->start();

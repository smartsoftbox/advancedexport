<?php
/**
 * 2019 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

interface FtpInterface
{
    public function __construct($host, $username, $password, $port = false);

    public function testConnection();

    public function getErrors();

    public function changeDir($directory);

    public function put($target, $local);

    public function listDir($directory);

    public function deleteFile($file);

    public function makeDir($directory);

    public function pwd();
}

<?php
/**
 * 2019 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

require_once 'FtpInterface.php';

class SFTP implements FtpInterface
{
    private $host;
    private $username;
    private $password;
    private $port;
    private $errors;
    private $connection = false;

    public function __construct($host, $username, $password, $port = false)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;

        if ($port === false) {
            $port = 22;
        }
        $this->port = $port;
        $this->connection = $this->connect();
    }

    public function connect()
    {
        set_include_path(dirname(__FILE__) . '/../vendor/phpseclib');

        if (!include('Net/SFTP.php')) {
            $this->errors[] = 'Can not load SFTP class';
            return false;
        }

        $connection = new Net_SFTP($this->host, $this->port);

        if (!$connection) {
            $this->errors[] = 'Can not connect to host (SFTP)';
            return false;
        }

        $login = $connection->login($this->username, $this->password);

        if (!$login) {
            $this->errors[] = 'Login Faild';
            return false;
        }

        return $connection;
    }

    public function testConnection()
    {
        if ($this->connection === false) {
            $this->errors[] = 'There is a problem with connection';
            return false;
        }
    }

    public function changeDir($directory)
    {
        if (!$this->connection->chdir($directory)) {
            $this->errors[] = 'Can not change directory.';
            return false;
        }
    }

    public function put($target, $local)
    {
        $this->connection->put($target, $local, NET_SFTP_LOCAL_FILE);
    }

    public function get($target, $local)
    {
        $this->connection->get($target, $local);
    }

    public function listDir($directory)
    {
        return $this->connection->nlist($directory);
    }

    public function deleteFile($file)
    {
        return $this->connection->delete($file);
    }

    public function makeDir($directory)
    {
        return $this->connection->mkdir($directory);
    }

    public function pwd()
    {
        return $this->connection->pwd();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isFileExists($filename)
    {
        $path = $this->pwd();
        $files = $this->listDir($path);

        if (!in_array($path . $filename, $files)) {
            $this->errors[] = "Can't find file.";
        }
    }
}

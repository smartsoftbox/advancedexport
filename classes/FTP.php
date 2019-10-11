<?php
/**
 * 2019 Smart Soft.
 *
 *  @author    Marcin Kubiak
 *  @copyright Smart Soft
 *  @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

include_once('FtpInterface.php');

class FTP implements FtpInterface
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
            $port = 21;
        }
        $this->port = $port;
        $this->connection = $this->connect();
    }

    public function connect()
    {
        $connection = ftp_connect($this->host);

        if (!$connection) {
            $this->errors[] = 'Can not connect to host (FTP)';
            return false;
        }
        $login = ftp_login($connection, $this->username, $this->password);

        if (!$login) {
            $this->errors[] = 'Login Faild';
            return false;
        }

        ftp_pasv($connection, true);
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
        if (!ftp_chdir($this->connection, $directory)) {
            $this->errors[] = 'Can not change directory.';
            return false;
        }
    }

    public function put($target, $local)
    {
        ftp_put($this->connection, $target, $local, FTP_BINARY);
    }

    public function listDir($directory)
    {
        return ftp_nlist($this->connection, $directory);
    }

    public function deleteFile($file)
    {
        ftp_delete($this->connection, $file);
    }

    public function makeDir($directory)
    {
        return ftp_mkdir($this->connection, $directory);
    }

    public function pwd()
    {
        return ftp_pwd($this->connection);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

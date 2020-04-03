<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class SaveType
{
    private static $save_types = array(
        array('id' => 0, 'name' => 'Save to disc', 'short_name' => 'disc'),
        array('id' => 1, 'name' => 'Ftp', 'short_name' => 'ftp'),
        array('id' => 2, 'name' => 'Sent to email', 'short_name' => 'email'),
        array('id' => 3, 'name' => 'SFtp', 'short_name' => 'sftp')
    );

    /**
     * @return array
     */
    public static function getSaveTypes()
    {
        return self::$save_types;
    }
}

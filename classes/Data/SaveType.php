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

    public static function getSaveTypeNameById($id)
    {
        if (is_null($id)) {
            throw new PrestaShopException('Invalid save type id');
        }

        foreach (self::$save_types as $save_type) {
            if ($save_type['id'] === (int)$id) {
                return $save_type['name'];
            }
        }
    }

}

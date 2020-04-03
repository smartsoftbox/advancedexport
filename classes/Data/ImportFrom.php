<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class ImportFrom
{
    private static $import_from = array(
        array('id' => 0, 'name' => 'model', 'public_name' => 'Export Model'),
        array('id' => 1, 'name' => 'upload', 'public_name' => 'Upload'),
        array('id' => 2, 'name' => 'url', 'public_name' => 'Url'),
        array('id' => 3, 'name' => 'ftp', 'public_name' => 'Ftp'),
        array('id' => 4, 'name' => 'sftp', 'public_name' => 'SFtp'),
    );

    /**
     * @return array
     */
    public static function getImportFrom()
    {
        return self::$import_from;
    }

    /**
     * @param $id
     * @return array
     * @throws PrestaShopException
     */
    public static function getImportFromPublicName($id)
    {
        if (is_null($id) or $id >= count(self::$import_from)) {
            throw new PrestaShopException('Invalid import from id');
        }

        foreach (self::$import_from as $import) {
            if ($import['id'] === (int)$id) {
                return $import['public_name'];
            }
        }
    }

    /**
     * @param $id
     * @return string
     * @throws PrestaShopException
     */
    public static function getImportFromName($id)
    {
        if (is_null($id) or $id >= count(self::$import_from)) {
            throw new PrestaShopException('Invalid import from id');
        }

        foreach (self::$import_from as $import) {
            if ($import['id'] === (int)$id) {
                return $import['name'];
            }
        }
    }

    /**
     * @param $id
     * @return string
     * @throws PrestaShopException
     */
    public static function getImportFromIdByName($name)
    {
        if (is_null($name)) {
            throw new PrestaShopException('Invalid import from name');
        }

        foreach (self::$import_from as $import) {
            if ($import['name'] === (string)$name) {
                return $import['id'];
            }
        }
    }
}

<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class Charset
{
    private static $charsets = array(
        array('id' => 1, 'name' => 'UTF-8'),
        array('id' => 2, 'name' => 'ISO-8859-1'),
        array('id' => 3, 'name' => 'GB2312'),
        array('id' => 4, 'name' => 'Windows-1251'),
        array('id' => 5, 'name' => 'Windows-1252'),
        array('id' => 6, 'name' => 'Shift JIS'),
        array('id' => 7, 'name' => 'GBK'),
        array('id' => 8, 'name' => 'Windows-1256'),
        array('id' => 9, 'name' => 'ISO-8859-2'),
        array('id' => 10, 'name' => 'EUC-JP'),
        array('id' => 11, 'name' => 'ISO-8859-15'),
        array('id' => 12, 'name' => 'ISO-8859-9'),
        array('id' => 13, 'name' => 'Windows-1250'),
        array('id' => 14, 'name' => 'Windows-1254'),
        array('id' => 15, 'name' => 'EUC-KR'),
        array('id' => 16, 'name' => 'Big5'),
        array('id' => 17, 'name' => 'Windows-874'),
        array('id' => 18, 'name' => 'US-ASCII'),
        array('id' => 19, 'name' => 'TIS-620'),
        array('id' => 20, 'name' => 'ISO-8859-7'),
        array('id' => 21, 'name' => 'Windows-1255')
    );

    /**
     * @return array
     */
    public static function getCharsets()
    {
        return self::$charsets;
    }
}

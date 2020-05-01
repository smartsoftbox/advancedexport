<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once dirname(__FILE__) . '/../../controllers/admin/AdminAdvancedExportBaseController.php';

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE); // todo check if you really need this line

class AdminAdvancedExportPrestaImportController extends AdminImportControllerCore
{
    protected function excelToCsvFile($filename)
    {
        if (preg_match('#(.*?)\.(csv)#is', $filename)) {
            $dest_file = self::getPath((string)(preg_replace('/\.{2,}/', '.', $filename)));
        } else {
            $csv_folder = self::getPath();
            $excel_folder = $csv_folder . 'csvfromexcel/';
            $info = pathinfo($filename);
            $csv_name = basename($filename, '.' . $info['extension']) . '.csv';
            $dest_file = $excel_folder . $csv_name;

            if (!is_dir($excel_folder)) {
                mkdir($excel_folder);
            }

            if (!is_file($dest_file)) {
                // validator error function exists
                $reader_excel = IOFactory::createReaderForFile($csv_folder . $filename);
                $reader_excel->setReadDataOnly(true);
                $excel_file = $reader_excel->load($csv_folder . $filename);
                // validator error function exists
                $csv_writer = IOFactory::createWriter($excel_file, 'Csv');

                $csv_writer->setSheetIndex(0);
                $csv_writer->setDelimiter(';');
                $csv_writer->save($dest_file);
            }
        }

        return $dest_file;
    }

    public static function getPath($file = '')
    {
        $id = Tools::getValue('id');
        // when there is no file specify it only needs path without filename
        $path = _AE_IMPORT_PATH_ . $id . '/' . $file;
        return $path;
    }

    protected function receiveTab()
    {
        $type_value = Tools::getValue('type_value') ? Tools::getValue('type_value') : array();
        foreach ($type_value as $nb => $type) {
            if ($type != 'no') {
                self::$column_mask[$type] = $nb;
            }
        }
    }
}

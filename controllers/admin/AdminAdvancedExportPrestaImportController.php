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
                $reader_excel = IOFactory::createReaderForFile($csv_folder . $filename);
                $reader_excel->setReadDataOnly(true);
                $excel_file = $reader_excel->load($csv_folder . $filename);

                $csv_writer = IOFactory::createWriter($excel_file, 'Csv');

                $csv_writer->setSheetIndex(0);
                $csv_writer->setDelimiter(';');
                $csv_writer->save($dest_file);
            }
        }

        return $dest_file;
    }

    public static function getPath($file = '') //todo check if you need this parameter
    {
        $id = Tools::getValue('id');
        $aeImport = new AdvancedExportImportClass($id);
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

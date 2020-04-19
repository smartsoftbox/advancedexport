<?php

namespace LegacyTests\TestCase;
use Export;

require_once dirname(__FILE__) . '/../../classes/Export/Export.php';


class HelperImport
{
    public static function getExportFileAsCsv($advancedExport, $entity)
    {
        // create export models for import
        if ($entity == 'combination') {
            $advancedExportClass = $advancedExport->generateCombination('products');
        } else {
            $advancedExportClass = $advancedExport->generateDefaultCsvByType($entity);
        }
        // run export
        $export = new Export();
        $export->createExportFile($advancedExportClass);

        // read files
        $url = _PS_ROOT_DIR_ . '/modules/advancedexport/csv/' .
            ($entity == 'combination' ? 'products' : $entity) . '/' . $entity . '_import.csv';

        $rows = array_map('str_getcsv', file($url));
        $sorted_rows = array();
        foreach ($rows[0] as $key => $fieldName) {
            $sorted_rows[$fieldName] = $rows['1'][$key];
        }

        return $sorted_rows;
    }
}

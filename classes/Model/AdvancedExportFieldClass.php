<?php
/**
 * 2019 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class AdvancedExportFieldClass extends ObjectModel
{
    public $id;
    public $tab;
    public $name;
    public $field;
    public $table;
    public $alias;
    public $as;
    public $concat;
    public $attribute;
    public $return;
    public $import;
    public $import_name;
    public $import_combination;
    public $import_combination_name;
    public $isCustom;
    public $group15;
    public $group17;
    public $version;

    public static $definition = array(
        'table' => 'advancedexportfield',
        'primary' => 'id_advancedexportfield',
        'fields' => array(
            'tab' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 255),
//            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 255),
//            'field' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'size' => 255),
//            'table' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 255),
            'isCustom' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        )
    );

    public static function getDefaultCombinationImportFields($tab)
    {
        $query = 'SELECT * FROM `' . _DB_PREFIX_ . "advancedexportfield`
                  WHERE tab = '" . $tab . "'
                  AND import_combination > 0
                  ORDER BY import_combination";

        $fields = Db::getInstance()->ExecuteS($query);
        $result = array();

        foreach ($fields as $field) {
            $result[$field['field']] = array($field['import_combination_name']);
        }

        return $result;
    }

    public static function getDefaultImportFields($tab)
    {
        $query = 'SELECT * FROM `' . _DB_PREFIX_ . "advancedexportfield`
                  WHERE tab = '" . $tab . "'
                  AND import > 0
                  ORDER BY import";

        $fields = Db::getInstance()->ExecuteS($query);
        $result = array();

        foreach ($fields as $field) {
            $result[$field['field']] = array($field['import_name']);
        }

        return $result;
    }

    public static function getDefaultCombinationFields($tab)
    {
        $query = 'SELECT * FROM `' . _DB_PREFIX_ . "advancedexportfield`
                  WHERE tab = '" . $tab . "'
                  AND import_combination > 0";

        $fields = Db::getInstance()->ExecuteS($query);
        $result = array();

        foreach ($fields as $field) {
            $result[$field['field']] = array($field['field']);
        }

        return $result;
    }

    public function copyFromPost()
    {
        /* Classical fields */
        foreach ($_POST as $key => $value) {
            if (property_exists($this, $key) and $key != 'id_' . $this->table) {
                $this->{$key} = $value;
            }
        }
    }

    public static function getAllFields($tab)
    {
        $query = 'SELECT * FROM `' . _DB_PREFIX_ . "advancedexportfield`
                  WHERE tab = '" . $tab . "'";

        $fields = Db::getInstance()->ExecuteS($query);
        $result = array();

        foreach ($fields as $field) {
            $result[$field['field']] = $field;
        }

        return $result;
    }

    public static function getNumberOfRows($tab)
    {
        $query = 'SELECT COUNT(*) FROM `' . _DB_PREFIX_ . "advancedexportfield`
                  WHERE tab = '" . $tab . "'";

        $result = Db::getInstance()->ExecuteS($query);

        return $result[0]['COUNT(*)'];
    }

    public static function getAllFieldsWithPagination($tab, $limit, $start)
    {
        $query = 'SELECT * FROM `' . _DB_PREFIX_ . "advancedexportfield`
                  WHERE tab = '" . $tab . "'";

        $query .= ' LIMIT ' . $limit;
        if ($limit != false && $limit > 0) {
            $query .= ' OFFSET ' . $start * $limit;
        }

        $fields = Db::getInstance()->ExecuteS($query);

        return $fields;
    }

    public function getFields()
    {
        parent::validateFields();
        $fields = null;
        $fields['id_advancedexportfield'] = (int)($this->id);
        $fields['tab'] = (string)($this->tab);
        $fields['name'] = (string)($this->name);
        $fields['field'] = (string)($this->field);
        $fields['table'] = (string)($this->table);
        $fields['alias'] = (string)($this->alias);
        $fields['return'] = (string)($this->return);
        $fields['as'] = (string)($this->as);
        $fields['concat'] = (string)($this->concat);
        $fields['attribute'] = (bool)($this->attribute);
        $fields['import'] = (int)($this->import);
        $fields['import_name'] = (string)($this->import_name);
        $fields['import_combination'] = (int)($this->import_combination);
        $fields['import_combination_name'] = (string)($this->import_combination_name);
        $fields['isCustom'] = (bool)($this->isCustom);
        $fields['group15'] = (string)($this->group15);
        $fields['group17'] = (string)($this->group17);
        $fields['version'] = (string)($this->version);

        return $fields;
    }
}

<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class AdvancedExportCronClass extends ObjectModel
{
    public $id;
    public $is_import;
    public $id_model;
    public $type;
    public $name;
    public $cron_hour;
    public $cron_day;
    public $cron_week;
    public $cron_month;
    public $last_export;
    public $active;

    public static $definition = array(
        'table' => 'advancedexportcron',
        'primary' => 'id_advancedexportcron',
        'fields' => array(
            'id_model' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isNullOrUnsignedId'),
            'type' => array('type' => self::TYPE_STRING, 'validate' => 'isName', 'size' => 255),
            'name' => array('type' => self::TYPE_BOOL, 'validate' => 'isName', 'required' => true, 'size' => 255),
            'cron_hour' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'cron_week' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'cron_month' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'last_export' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'is_import' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        )
    );

    public function copyFromPost()
    {
        /* Classical fields */
        foreach ($_POST as $key => $value) {
            if (key_exists($key, $this) and $key != 'id_' . $this->table) {
                $this->{$key} = $value;
            }
        }
    }

    public static function getAll($type)
    {
        return Db::getInstance()->ExecuteS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'advancedexportcron` WHERE type = "' . $type . '"'
        );
    }

    public function getFields()
    {
        parent::validateFields();
        $fields = null;
//        $fields['id_advancedexportcron'] = (int)($this->id);
        $fields['id_model'] = (int)($this->id_model);
        $fields['type'] = (string)($this->type);
        $fields['name'] = (string)($this->name);
        $fields['cron_hour'] = (string)($this->cron_hour);
        $fields['cron_day'] = (string)($this->cron_day);
        $fields['cron_week'] = (string)($this->cron_week);
        $fields['cron_month'] = (string)($this->cron_month);
        $fields['last_export'] = (string)($this->last_export);
        $fields['is_import'] = (bool)($this->is_import);
        $fields['active'] = (bool)($this->active);

        return $fields;
    }
}

<?php
/**
 * 2016 Smart Soft.
 *
 *  @author    Marcin Kubiak
 *  @copyright Smart Soft
 *  @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class AdvancedExportCronClass extends ObjectModel
{
    public $id;
    public $id_advancedexport;
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
    );

    public function copyFromPost()
    {
        /* Classical fields */
        foreach ($_POST as $key => $value) {
            if (key_exists($key, $this) and $key != 'id_'.$this->table) {
                $this->{$key} = $value;
            }
        }
    }

    public function getFields()
    {
        parent::validateFields();
        $fields = null;
        $fields['id_advancedexportcron'] = (int) ($this->id);
        $fields['id_advancedexport'] = (int) ($this->id_advancedexport);
        $fields['type'] = (string) ($this->type);
        $fields['name'] = (string) ($this->name);
        $fields['cron_hour'] = (string) ($this->cron_hour);
        $fields['cron_day'] = (string) ($this->cron_day);
        $fields['cron_week'] = (string) ($this->cron_week);
        $fields['cron_month'] = (string) ($this->cron_month);
        $fields['last_export'] = (string) ($this->last_export);
        $fields['active'] = (bool) ($this->active);

        return $fields;
    }
}

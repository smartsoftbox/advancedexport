<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class MyHelperForm
{
    const MODULE_NAME = 'advancedexport';

    /**
     * @var string
     */
    protected $switch;
    /**
     * @var HelperForm
     */
    protected $helperForm;
    protected $module;
    protected $token;
    /**
     * @var string
     */
    protected $base_url;

    public function __construct($module)
    {
        $this->module = $module;
        $this->helperForm = new HelperForm();
        $this->token = Tools::getAdminTokenLite('AdminModules');
        $this->base_url = AdminController::$currentIndex . '&configure=' . self::MODULE_NAME . '&token=' . $this->token;
        $this->switch = (_PS_VERSION_ >= 1.6 ? 'switch' : 'radio');
    }

    /**
     * @param $fields
     * @param $ae
     * @return mixed
     */
    public function getFieldsValue($fields, $ae)
    {
        $fields_value = array();
        foreach ($fields as $field) {
            if (Tools::getValue($field)) {
                $fields_value[$field] = Tools::getValue($field);
            } elseif (isset($ae) && isset($ae->$field)) {
                $fields_value[$field] = $ae->$field;
            } else {
                if ($field === 'separator') {
                    $fields_value[$field] = ',';
                } elseif ($field === 'multi_value_separator') {
                    $fields_value[$field] = ';';
                } else {
                    $fields_value[$field] = '';
                }
            }
        }
        return $fields_value;
    }

    protected function getLabelsAsFieldsArray($labels, $mapping)
    {
        $fields_value = array();
        foreach ($labels as $label) {
            $field = 'fields[' . $label . ']';
            if (Tools::getValue($field)) {
                $fields_value[$field] = Tools::getValue($field);
            } elseif (isset($mapping) && isset($mapping->$label)) {
                $fields_value[$field] = $mapping->$label;
            } else {
                $fields_value[$field] = '';
            }
        }
        return $fields_value;
    }

    protected function createFromToField($from_name, $from_value, $to_name, $to_value, $class = '')
    {
        $this->module->getSmarty()->assign(array(
            'from_name' => $from_name,
            'from_value' => $from_value,
            'to_name' => $to_name,
            'to_value' => $to_value,
            'class' => $class
        ));

        return $this->module->displayTemplate('admin/fromto.tpl');
    }

    protected function l($name)
    {
        return $name;
    }

    protected function getValue($name)
    {
        return Tools::getValue($name);
    }

    public function groupFields($input_arr)
    {
        $level_arr = array();

        foreach ($input_arr as $key => $entry) {
            $level_arr[$entry['group15']] = array(
                'name' => $entry['group15'],
                'groups' => array()
            );
        }
        foreach ($input_arr as $key => $entry) {
            $level_arr[$entry['group15']]['groups'][$key] = $entry;
        }

        return $level_arr;
    }

    protected function getConfiguration($name)
    {
        return Configuration::getGlobalValue($name);
    }
}

<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class CustomersExport extends ExportInterface
{
    public function getEntityData()
    {
        $sql = 'SELECT c.`id_customer` ' . (empty($this->sorted_fields['sqlfields']) ? '' : ', ' .
                implode(', ', $this->sorted_fields['sqlfields'])) . '
				FROM ' . _DB_PREFIX_ . 'customer c
                LEFT JOIN `' . _DB_PREFIX_ . 'address` a ON ( a.`id_customer` = c.`id_customer` )
                LEFT JOIN `' . _DB_PREFIX_ . 'state` s ON ( a.`id_state` = s.`id_state` )
                LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` co 
                ON ( co.`id_country` = a.`id_country` AND co.`id_lang` = ' . $this->ae->id_lang . ')
                ' . CustomFields::customersQuery() . '
				WHERE 1' . Shop::addSqlRestriction(Shop::SHARE_CUSTOMER) .
            (isset($this->sorted_fields['active']) && $this->sorted_fields['active'] ?
                ' AND c.`active` = 1' : '') .
            (isset($this->ae->only_new) && $this->ae->only_new ?
                ' AND c.`id_customer` > ' . $this->ae->last_exported_id : '') .
            ($this->ae->only_new == false && $this->ae->start_id ?
                ' AND c.`id_customer` >= ' . $this->ae->start_id : '') .
            ($this->ae->only_new == false && $this->ae->end_id ? ' AND c.`id_customer` <= ' . $this->ae->end_id : '') .
            (isset($this->sorted_fields['active']) && $this->sorted_fields['active'] ? ' AND c.`active` = 1' : '') .
            (isset($this->ae->date_from) && $this->ae->date_from && !$this->ae->only_new ?
                ' AND c.`date_add` >= "' . ($this->ae->date_from) . '"' : '') .
            (isset($this->ae->date_to) && $this->ae->date_to && !$this->ae->only_new ?
                ' AND c.`date_add` <= "' . ($this->ae->date_to) . '"' : '');

        $result = $this->getModuleTools()->query($sql);
        $this->rowsNumber = $this->getModuleTools()->query('SELECT FOUND_ROWS()')->fetchColumn();

        return $result;
    }

    public function customersGroups($obj, $ae)
    {
        $groupsIds = Customer::getGroupsStatic((int)$obj->id);
        $groupsNames = array();
        if (is_array($groupsIds)) {
            foreach ($groupsIds as $id) {
                $group = new Group($id);
                $groupsNames[] = $group->name[$ae->id_lang];
            }
            return implode(',', $groupsNames);
        } else {
            return '';
        }
    }
}

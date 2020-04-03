<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class AddressesExport extends ExportInterface
{
    public function getEntityData()
    {
        $sql = 'SELECT a.`id_address` ' . (empty($this->sorted_fields['sqlfields']) ? '' : ', ' .
                implode(', ', $this->sorted_fields['sqlfields'])) . '
				FROM ' . _DB_PREFIX_ . 'address as a
				LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON ( a.`id_manufacturer` = m.`id_manufacturer` )
				LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON ( a.`id_supplier` = s.`id_supplier` )
				LEFT JOIN `' . _DB_PREFIX_ . 'state` st ON ( a.`id_state` = st.`id_state`)
				LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` cl 
				ON ( a.`id_country` = cl.`id_country` AND cl.`id_lang` = ' . $this->ae->id_lang . ')
				LEFT JOIN `' . _DB_PREFIX_ . 'customer` cu ON ( a.`id_customer` = cu.`id_customer`)
				' . CustomFields::addressesQuery() . '
				WHERE 1' . (isset($this->sorted_fields['active']) && $this->sorted_fields['active'] ? ' AND a.`active` = 1' : '') .
            (isset($this->ae->only_new) && $this->ae->only_new ? ' AND a.`id` > ' . $this->ae->last_exported_id : '') .
            ($this->ae->only_new == false && $this->ae->start_id ? ' AND a.`id` >= ' . $this->ae->start_id : '') .
            ($this->ae->only_new == false && $this->ae->end_id ? ' AND a.`id` <= ' . $this->ae->end_id : '') .
            (isset($this->ae->date_from) && $this->ae->date_from && !$this->ae->only_new ?
                ' AND a.`date_add` >= "' . ($this->ae->date_from) . '"' : '') .
            (isset($this->ae->date_to) && $this->ae->date_to && !$this->ae->only_new ?
                ' AND a.`date_add` <= "' . ($this->ae->date_to) . '"' : '');

        $result = $this->getModuleTools()->query($sql);
        $this->rowsNumber = $this->getModuleTools()->query('SELECT FOUND_ROWS()')->fetchColumn();

        return $result;
    }
}

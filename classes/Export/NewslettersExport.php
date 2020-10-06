<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class NewslettersExport extends ExportInterface
{
    public  $rowsNumber;

    public function getEntityData()
    {
        $sql = 'SELECT n.`id` ' . (empty($this->sorted_fields['sqlfields']) ? '' : ', ' .
                implode(', ', $this->sorted_fields['sqlfields'])) . '
				FROM ' . _DB_PREFIX_ . 'emailsubscription as n
				' . ($this->isCustomFieldsExists ? CustomFields::newslettersQuery() : '') . '
				WHERE 1' . (isset($this->sorted_fields['active']) && $this->sorted_fields['active'] ?
                ' AND n.`active` = 1' : '') .
            (isset($this->ae->only_new) && $this->ae->only_new ?
                ' AND n.`id` > ' . $this->ae->last_exported_id : '') .
            ($this->ae->only_new == false && $this->ae->start_id ? ' AND n.`id` >= ' . $this->ae->start_id : '') .
            ($this->ae->only_new == false && $this->ae->end_id ? ' AND n.`id` <= ' . $this->ae->end_id : '') .
            (isset($this->ae->date_from) && $this->ae->date_from && !$this->ae->only_new ?
                ' AND n.`newsletter_date_add` >= "' . ($this->ae->date_from) . '"' : '') .
            (isset($this->ae->date_to) && $this->ae->date_to && !$this->ae->only_new ?
                ' AND n.`newsletter_date_add` <= "' . ($this->ae->date_to) . '"' : '') . '
				AND n.`id_shop` = ' . $this->context->shop->id;

        $result = $this->getModuleTools()->query($sql);
        $this->rowsNumber = $this->getModuleTools()->query('SELECT FOUND_ROWS()')->fetchColumn();

        return $result;
    }
}

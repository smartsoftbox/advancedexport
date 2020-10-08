<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class ManufacturersExport extends ExportInterface
{
    public $rowsNumber;

    public function getEntityData()
    {
        $sql = 'SELECT m.`id_manufacturer` ' . (empty($this->sorted_fields['sqlfields']) ? '' : ', ' .
                implode(', ', $this->sorted_fields['sqlfields'])) . '
            FROM `' . _DB_PREFIX_ . 'manufacturer` m
            ' . Shop::addSqlAssociation('manufacturer', 'm') . '
            INNER JOIN `' . _DB_PREFIX_ . 'manufacturer_lang` ml 
            ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = ' . (int)$this->ae->id_lang . ')' . '
				' . ($this->isCustomFieldsExists ? CustomFields::manufacturersQuery() : '') . '
            WHERE 1' . (isset($this->sorted_fields['active']) && $this->sorted_fields['active'] ?
                ' AND m.`active` = 1' : '') .
            (isset($this->ae->only_new) && $this->ae->only_new ?
                ' AND m.`id_manufacturer` > ' . $this->ae->last_exported_id : '') .
            ($this->ae->only_new == false && $this->ae->start_id ?
                ' AND m.`id_manufacturer` >= ' . $this->ae->start_id : '') .
            ($this->ae->only_new == false && $this->ae->end_id ?
                ' AND m.`id_manufacturer` <= ' . $this->ae->end_id : '') .
            (isset($this->ae->date_from) && $this->ae->date_from && !$this->ae->only_new ?
                ' AND m.`date_add` >= "' . ($this->ae->date_from) . '"' : '') .
            (isset($this->ae->date_to) && $this->ae->date_to && !$this->ae->only_new ? ' 
            AND m.`date_add` <= "' . ($this->ae->date_to) . '"' : '');
        $result = $this->getModuleTools()->query($sql);
        $this->rowsNumber = $this->getModuleTools()->query('SELECT FOUND_ROWS()')->fetchColumn();

        return $result;
    }

    public function manufacturersImage($obj, $ae)
    {
        if (_PS_VERSION_ >= 1.7) {
            return Context::getContext()->shop->getBaseURL(true) . _PS_MANU_IMG_DIR_ . $obj->id . '.jpg';
        } else {
            return 'http://' . $this->link->getImageLink(
                $obj->link_rewrite[$ae->id_lang],
                $obj->id . '-' . $obj->id_image,
                $this->ae->image_type
            );
        }
    }
}

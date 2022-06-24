<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class CategoriesExport extends ExportInterface
{
    public $rowsNumber;

    public function getEntityData()
    {
        $sql = 'SELECT c.`id_category` ' . (empty($this->sorted_fields['sqlfields']) ? '' : ', ' .
                implode(', ', $this->sorted_fields['sqlfields'])) . '
            FROM `' . _DB_PREFIX_ . 'category` c
			' . Shop::addSqlAssociation('category', 'c') . '
			LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl 
			ON c.`id_category` = cl.`id_category`' . Shop::addSqlRestrictionOnLang('cl') . '
				' . ($this->isCustomFieldsExists ? CustomFields::categoriesQuery() : '') . '
			WHERE 1' . ($this->ae->id_lang ? ' AND `id_lang` = ' . (int)$this->ae->id_lang : '') .
            (isset($this->ae->only_new) && $this->ae->only_new ?
                ' AND c.`id_category` > ' . $this->ae->last_exported_id : '') .
            ($this->ae->only_new == false && $this->ae->start_id ?
                ' AND c.`id_category` >= ' . $this->ae->start_id : '') .
            ($this->ae->only_new == false && $this->ae->end_id ?
                ' AND c.`id_category` <= ' . $this->ae->end_id : '') .
            (isset($this->sorted_fields['active']) && $this->sorted_fields['active'] ? ' AND c.`active` = 1' : '') .
            (isset($this->ae->date_from) && $this->ae->date_from && !$this->ae->only_new ?
                ' AND c.`date_add` >= "' . ($this->ae->date_from) . '"' : '') .
            (isset($this->ae->date_to) && $this->ae->date_to && !$this->ae->only_new ?
                ' AND c.`date_add` <= "' . ($this->ae->date_to) . '"' : '') . '
			GROUP BY c.id_category
			ORDER BY c.`level_depth` ASC, category_shop.`position` ASC';
        $result = $this->getModuleTools()->query($sql);
        $this->rowsNumber = $this->getModuleTools()->query('SELECT FOUND_ROWS()')->fetchColumn();

        return $result;
    }

    public function categoriesIdGroup($obj)
    {
        $result = $this->getModuleTools()->executeS('
			SELECT cg.`id_group`
			FROM ' . _DB_PREFIX_ . 'category_group cg
			WHERE cg.`id_category` = ' . (int)$obj->id);
        $groups = null;
        foreach ($result as $group) {
            $groups = $group['id_group'];
        }

        return $groups;
    }

    public function categoriesImage($obj, $ae)
    {
        $imageLink = 'http://' . $this->link->getImageLink(
            $obj->link_rewrite[$ae->id_lang],
            $obj->id . '-' . $obj->id_image,
            $this->ae->image_type
        );

        return $imageLink;
    }

    public function categoriesNameParent($obj, $ae)
    {
        $parent = new Category($obj->id_parent, $ae->id_lang);
        return $parent->name;
    }
}

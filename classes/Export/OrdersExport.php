<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

class OrdersExport extends ExportInterface
{
    public function getEntityData()
    {
        $sql = 'SELECT o.`id_order` ' . (empty($this->sorted_fields['sqlfields']) ? '' : ', ' .
                implode(', ', $this->sorted_fields['sqlfields'])) . '
                FROM ' . _DB_PREFIX_ . 'orders o
                LEFT JOIN `' . _DB_PREFIX_ . 'order_detail` od ON ( od.`id_order` = o.`id_order` )
                LEFT JOIN `' . _DB_PREFIX_ . 'shop` sh ON ( o.`id_shop` = sh.`id_shop` )
                LEFT JOIN `' . _DB_PREFIX_ . 'customer` cu ON ( o.`id_customer` = cu.`id_customer` )
                LEFT JOIN `' . _DB_PREFIX_ . 'gender_lang` gl 
                ON ( cu.`id_gender` = gl.`id_gender` AND gl.`id_lang` = ' . $this->ae->id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'gender_lang` inv_gl 
                ON ( cu.`id_gender` = inv_gl.`id_gender` AND inv_gl.`id_lang` = ' . $this->ae->id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'address` a ON ( a.`id_address` = o.`id_address_delivery` )
                LEFT JOIN `' . _DB_PREFIX_ . 'address` inv_a ON ( inv_a.`id_address` = o.`id_address_invoice` )
                LEFT JOIN `' . _DB_PREFIX_ . 'state` s ON ( s.`id_state` = a.`id_state` )
                LEFT JOIN `' . _DB_PREFIX_ . 'state` inv_s ON ( inv_s.`id_state` = inv_a.`id_state` )
                LEFT JOIN `' . _DB_PREFIX_ . 'country` co ON ( co.`id_country` = a.`id_country` )
                LEFT JOIN `' . _DB_PREFIX_ . 'country` inv_co ON ( inv_co.`id_country` = inv_a.`id_country` )
                LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` cl 
                ON ( cl.`id_country` = co.`id_country` AND cl.`id_lang`= ' . $this->ae->id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` inv_cl 
                ON ( inv_cl.`id_country` = inv_co.`id_country` AND inv_cl.`id_lang`= ' . $this->ae->id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'carrier` ca ON ( ca.`id_carrier` = o.`id_carrier` )
                LEFT JOIN `' . _DB_PREFIX_ . 'order_payment` op ON ( op.`order_reference` = o.`reference` )
                LEFT JOIN `' . _DB_PREFIX_ . 'message` m ON ( m.`id_order` = o.`id_order` )
                LEFT JOIN `' . _DB_PREFIX_ . 'currency` cur ON ( o.`id_currency` = cur.`id_currency` )
                LEFT JOIN `' . _DB_PREFIX_ . 'order_detail_tax` odt ON ( od.`id_order_detail` = odt.`id_order_detail` )
                LEFT JOIN `' . _DB_PREFIX_ . 'tax` t ON ( odt.`id_tax` = t.`id_tax` )
                LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` osl 
                ON ( o.`current_state` = osl.`id_order_state` AND osl.`id_lang` = ' . $this->ae->id_lang . ')
				' . ($this->isCustomFieldsExists ? CustomFields::ordersQuery() : '') . '
                WHERE 1' .
            (isset($this->ae->only_new) && $this->ae->only_new ?
                ' AND o.`id_order` > ' . $this->ae->last_exported_id : '') .
            ($this->ae->only_new == false && $this->ae->start_id ? ' AND o.`id_order` >= ' . $this->ae->start_id : '') .
            ($this->ae->only_new == false && $this->ae->end_id ? ' AND o.`id_order` <= ' . $this->ae->end_id : '') .
            (isset($this->sorted_fields['groups[]']) && $this->sorted_fields['groups[]'] ?
                ' AND cu.`id_default_group` IN (' . implode(', ', $this->sorted_fields['groups[]']) . ')' : '') .
            (isset($this->sorted_fields['payments[]']) && $this->sorted_fields['payments[]'] ?
                ' AND o.`module` IN ("' . implode('", "', $this->sorted_fields['payments[]']) . '")' : '') .
            (isset($this->sorted_fields['carriers[]']) && $this->sorted_fields['carriers[]'] ?
                ' AND o.`id_carrier` IN (' . implode(', ', $this->sorted_fields['carriers[]']) . ')' : '') .
            (isset($this->sorted_fields['state[]']) && $this->sorted_fields['state[]'] ?
                ' AND o.`current_state` IN (' . implode(', ', $this->sorted_fields['state[]']) . ')' : '') .
            (isset($this->ae->date_from) && $this->ae->date_from && !$this->ae->only_new ?
                ' AND o.`date_add` >= "' . ($this->ae->date_from) . '"' : '') .
            (isset($this->ae->date_to) && $this->ae->date_to && !$this->ae->only_new ?
                ' AND o.`date_add` <= "' . ($this->ae->date_to) . '"' : '') .
            Shop::addSqlRestriction(false, 'o') .
            ' GROUP BY ' . (isset($this->sorted_fields['order_detail']) && $this->sorted_fields['order_detail'] ?
                'od.`id_order_detail`' : 'o.`id_order`');

        $result = $this->getModuleTools()->query($sql);
        $this->rowsNumber = $this->getModuleTools()->query('SELECT FOUND_ROWS()')->fetchColumn();

        return $result;
    }

    public function ordersCode($obj)
    {
        $result = $obj->getCartRules();
        $codes = array();
        foreach ($result as $res) {
            $codes[] = $res['name'];
        }

        return implode(',', $codes);
    }

    public function ordersEmployeeName($obj)
    {
        $employee = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT `firstname`, `lastname`
		FROM `' . _DB_PREFIX_ . 'employee` e
		LEFT JOIN `' . _DB_PREFIX_ . 'order_history` oh ON ( oh.`id_employee` = e.`id_employee` )
		WHERE `id_order` = ' . (int)$obj->id . '
		ORDER BY `date_add` DESC, `id_order_history` DESC LIMIT 1');

        return (isset($employee[0]) ? $employee[0]['firstname'] . ' ' . $employee[0]['lastname'] : '');
    }

    public function ordersCustomization($obj, $ae, $element)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
				SELECT cud.`value`, cu.`quantity`
				FROM `' . _DB_PREFIX_ . 'customization` cu
				INNER JOIN `' . _DB_PREFIX_ . 'customized_data` cud ON (cud.`id_customization` = cu.`id_customization`)
				WHERE cu.`id_product` = ' . (int)($element['product_id']) . ' 
				AND cu.`id_product_attribute` = ' . (int)($element['product_attribute_id']) . '  
				AND cu.`id_cart` = ' . (int)($element['id_cart']));

        $cud = array();
        foreach ($result as $res) {
            $cud[] = 'value:' . $res['value'] . ' ' . 'quantity:' . $res['quantity'];
        }

        return implode(',', $cud);
    }

    public function ordersTotalProductWeight($obj)
    {
        return $obj->getTotalWeight();
    }
}

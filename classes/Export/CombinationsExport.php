<?php
/**
 * 2016 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

include_once 'ExportInterface.php';

class CombinationsExport extends ExportInterfacet
{
    public function combinationSupplierNameAll($obj, $product_attribute)
    {
        $sups = $this->getModuleTools()->executeS('
            SELECT DISTINCT(s.`name`)
            FROM `' . _DB_PREFIX_ . 'product_supplier` ps
            LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON (ps.`id_supplier`= s.`id_supplier`)
            LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (ps.`id_product`= p.`id_product`)
            WHERE ps.`id_product` = ' . $obj->id . ' 
            AND ps.id_product_attribute = ' . (int)$product_attribute['id_product_attribute']);
        $suppliers = array();
        foreach ($sups as $sup) {
            $suppliers[] = $sup['name'];
        }

        return implode(',', $suppliers);
    }

    public function combinationIdSupplierAll($obj, $product_attribute)
    {
        $sups = $this->getModuleTools()->executeS('
			SELECT DISTINCT(ps.`id_supplier`)
			FROM `' . _DB_PREFIX_ . 'product_supplier` ps
			JOIN `' . _DB_PREFIX_ . 'product` p ON (ps.`id_product`= p.`id_product`)
			WHERE ps.`id_product` = ' . $obj->id . ' 
			AND ps.id_product_attribute = ' . (int)$product_attribute['id_product_attribute']);

        $suppliers = array();
        if (is_array($suppliers)) {
            foreach ($sups as $sup) {
                $suppliers[] = $sup['id_supplier'];
            }
        }
        return (is_array($suppliers) ? implode(',', $suppliers) : '');
    }

    public function combinationWarehosue($obj, $products_attribute)
    {
        $warehouse = $this->getModuleTools()->executeS('
		SELECT `id_warehouse`
		FROM `' . _DB_PREFIX_ . 'warehouse_product_location`
		WHERE `id_product` = ' . $obj->id . ' 
		AND `id_product_attribute` = ' . $products_attribute['id_product_attribute']);

        return $warehouse[0]['id_warehouse'];
    }

    public function combinationAttributes($obj, $products_attribute)
    {
        $name = null;
        foreach ($products_attribute['attributes'] as $attribute) {
            $name .= addslashes(htmlspecialchars($attribute[0])) . ': ' .
                addslashes(htmlspecialchars($attribute[1])) . ';';
        }
        $name = rtrim($name, ';');
        return Tools::stripslashes($name);
    }

    public function combinationAttributesName($obj, $products_attribute)
    {
        $name = array();
        foreach ($products_attribute['attributes_name'] as $attribute) {
            $attributeGroup = new AttributeGroup($attribute[1]);
            $name[] = addslashes(htmlspecialchars($attribute[0])) . ':' .
                addslashes(htmlspecialchars($attributeGroup->group_type)) . ':' .
                addslashes(htmlspecialchars($attributeGroup->position));
        }

        return implode(',', $name);
    }

    public function combinationAttributesValue($obj, $products_attribute)
    {
        $value = array();
        foreach ($products_attribute['attributes_value'] as $attribute) {
            $attr = new Attribute($attribute[1]);
            $value[] = addslashes(htmlspecialchars($attribute[0])) . ':' .
                addslashes(htmlspecialchars($attr->position));
        }

        return implode(',', $value);
    }

    public function combinationDefaultCombination($obj, $product_attribute)
    {
        return $product_attribute['default_on'] ? 1 : 0;
    }

    public function combinationWholesalePrice($obj, $product_attribute)
    {
        return $product_attribute['wholesale_price'];
    }

    public function combinationAvailableDate($obj, $product_attribute)
    {
        return $product_attribute['available_date'];
    }

    public function combinationImpactPrice($obj, $product_attribute)
    {
        return $product_attribute['price'];
    }

    public function combinationPrice($obj, $product_attribute)
    {
        return Product::getPriceStatic((int)$obj->id, false, (int)$product_attribute['id_product_attribute']);
    }

    public function combinationWeight($obj, $product_attribute)
    {
        return $product_attribute['weight'];
    }

    public function combinationPriceTax($obj, $product_attribute)
    {
        return $obj->getPrice(true, (int)$product_attribute['id_product_attribute']);
    }

    public function combinationPriceTaxNodiscount($obj, $product_attribute)
    {
        return $obj->getPrice(true, (int)$product_attribute['id_product_attribute'], 2, null, false, false);
    }

    public function combinationUnitImpact($obj, $product_attribute)
    {
        return $product_attribute['unit_impact'];
    }

    public function combinationReference($obj, $product_attribute)
    {
        return $product_attribute['reference'];
    }

    public function combinationSupplierReference($obj, $product_attribute)
    {
        // build query
        $query = new DbQuery();
        $query->select('ps.product_supplier_reference');
        $query->from('product_supplier', 'ps');
        $query->where(
            'ps.id_product = ' . (int)$obj->id .
            ' AND ps.id_product_attribute = ' . (int)$product_attribute['id_product_attribute']
        );
        $suppliers = null;
        $result = null;
        $suppliers = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

        foreach ($suppliers as $supplier) {
            if ($supplier['product_supplier_reference']) {
                $result[] = $supplier['product_supplier_reference'];
            }
        }

        return (is_array($result) ? implode(',', $result) : '');
    }

    public function combinationMpn($obj, $product_attribute)
    {
        return $product_attribute['mpn'];
    }

    public function combinationEan13($obj, $product_attribute)
    {
        return $product_attribute['ean13'];
    }

    public function combinationUpc($obj, $product_attribute)
    {
        return $product_attribute['upc'];
    }

    public function combinationMinimalQuantity($obj, $product_attribute)
    {
        return $product_attribute['minimal_quantity'];
    }

    public function combinationLocation($obj, $product_attribute)
    {
        return $product_attribute['location'];
    }

    public function combinationQuantity($obj, $product_attribute)
    {
        return $product_attribute['quantity'];
    }

    public function combinationEcotax($obj, $product_attribute)
    {
        return $product_attribute['ecotax'];
    }

    public function combinationImages($obj, $product_attribute, $ae)
    {
        $images = array();
        if (isset($product_attribute['images']) and is_array($product_attribute['images'])) {
            foreach ($product_attribute['images'] as $image) {
                $attrImage = ($image['id_image'] ? new Image($image['id_image']) : null);
                $images[] = 'http://' . $this->link->getImageLink(
                    $obj->link_rewrite[$this->ae->id_lang],
                    $obj->id . '-' . $attrImage->id,
                    $this->ae->image_type
                );
            }
        }

        return (is_array($images) ? implode(',', $images) : '');
    }

    public function combinationImagePosition($obj, $product_attribute)
    {
        $images = array();
        if (isset($product_attribute['images']) and is_array($product_attribute['images'])) {
            foreach ($product_attribute['images'] as $image) {
                $attrImage = ($image['id_image'] ? new Image($image['id_image']) : null);
                if (is_object($attrImage)) {
                    $images[] = $attrImage->position;
                }
            }
        }

        return (is_array($images) ? implode(',', $images) : '');
    }

    public function combinationImageAlt($obj, $product_attribute, $ae)
    {
        $images = array();
        if (isset($product_attribute['images']) and is_array($product_attribute['images'])) {
            $ids = implode(', ', array_map(function ($entry) {
                return $entry['id_image'];
            }, $product_attribute['images']));
            $images = $this->getModuleTools()->executeS('SELECT legend
			FROM ' . _DB_PREFIX_ . 'image_lang
			WHERE id_image IN (' . $ids . ')
			AND id_lang = ' . $this->ae->id_lang);
        }

        return (is_array($images) ? implode(', ', array_map(function ($entry) {
            return $entry['legend'];
        }, $images)) : '');
    }

    public function combinationImage($obj, $product_attribute, $ae)
    {
        $attrImage = ($product_attribute['id_image'] ? new Image($product_attribute['id_image']) : null);
        if ($attrImage) {
            return 'http://' . $this->link->getImageLink(
                $obj->link_rewrite[$this->ae->id_lang],
                $obj->id . '-' . $attrImage->id,
                $this->ae->image_type
            );
        } else {
            return '';
        }
    }

    public function combinationWarehouse($obj, $product_attribute, $ae)
    {
        return '';
    }

    public function combinationLowStockThreshold($obj, $product_attribute, $ae)
    {
        return $product_attribute['low_stock_threshold'];
    }

    public function combinationLowStockAlert($obj, $product_attribute, $ae)
    {
        return $product_attribute['low_stock_alert'];
    }
}

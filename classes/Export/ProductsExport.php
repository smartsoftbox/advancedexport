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

class ProductsExport extends ExportInterface
{
    public $rowsNumber;

    public function getEntityData()
    {
        $sql = 'SELECT p.`id_product` ' . (empty($this->sorted_fields['sqlfields']) ? '' :
                ', ' . implode(', ', $this->sorted_fields['sqlfields'])) .
            ' FROM ' . _DB_PREFIX_ . 'product as p' .
            Shop::addSqlAssociation('product', 'p') . '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl 
            ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON (p.`id_supplier` = s.`id_supplier`)
            LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
            LEFT JOIN `' . _DB_PREFIX_ . 'product_download` pd ON (p.`id_product` = pd.`id_product`)
            LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sa ON (sa.`id_product` = p.`id_product` AND sa
            .`id_product_attribute` = 0)
				' . ($this->isCustomFieldsExists ? CustomFields::productsQuery() : '') . '
			LEFT JOIN ( SELECT s1.`id_product`, s1.`from`, s1.`to`, s1.`id_cart`,
				IF(s1.`reduction_type` = "percentage", s1.`reduction`, "") as discount_percent,
				IF(s1.`reduction_type` = "amount", s1.`reduction`, "") as discount_amount
				FROM `' . _DB_PREFIX_ . 'specific_price` as s1
				LEFT JOIN `' . _DB_PREFIX_ . 'specific_price` AS s2
					 ON s1.id_product = s2.id_product AND s1.id_specific_price < s2.id_specific_price
				WHERE s2.id_specific_price IS NULL ) as sp_tmp
			ON (p.`id_product` = sp_tmp.`id_product`  && sp_tmp.`id_cart` = 0)' .
            (isset($this->sorted_fields['categories']) && $this->sorted_fields['categories'] ?
                ' LEFT JOIN `' . _DB_PREFIX_ . 'category_product` c ON (c.`id_product` = p.`id_product`)' : '') . '
			WHERE pl.`id_lang` = ' . (int)$this->ae->id_lang .
            (isset($this->ae->only_new) && $this->ae->only_new && $this->ae->last_exported_id ? ' 
            AND p.`id_product` > ' . $this->ae->last_exported_id : '') .
            ($this->ae->only_new == false && $this->ae->start_id ?
                ' AND p.`id_product` >= ' . $this->ae->start_id : '') .
            ($this->ae->only_new == false && $this->ae->end_id ? ' AND p.`id_product` <= ' . $this->ae->end_id : '') .
            (isset($this->sorted_fields['categories']) && $this->sorted_fields['categories'] ?
                ' AND c.`id_category` IN (' . implode(',', $this->sorted_fields['categories']) . ')' : '') .
            (isset($this->sorted_fields['suppliers[]']) && $this->sorted_fields['suppliers[]'] ?
                ' AND p.`id_supplier` IN (' . implode(',', $this->sorted_fields['suppliers[]']) . ')' : '') .
            (isset($this->sorted_fields['manufacturers[]']) && $this->sorted_fields['manufacturers[]'] ?
                ' AND p.`id_manufacturer` IN (' . implode(',', $this->sorted_fields['manufacturers[]']) . ')' : '')
            . (isset($this->sorted_fields['active']) && $this->sorted_fields['active'] ?
                ' AND product_shop.`active` = 1' : '') .
            (isset($this->sorted_fields['out_of_stock']) && $this->sorted_fields['out_of_stock'] ?
                ' AND sa.`quantity` <= 0' : '') .
            (isset($this->sorted_fields['ean']) && $this->sorted_fields['ean'] ? ' AND p.`ean13` != ""' : '') .
            (isset($this->ae->date_from) && $this->ae->date_from && !$this->ae->only_new ?
                ' AND p.`date_add` >= "' . ($this->ae->date_from) . '"' : '') .
            (isset($this->ae->date_to) && $this->ae->date_to && !$this->ae->only_new ?
                ' AND p.`date_add` <= "' . ($this->ae->date_to) . '"' : '') .
            ' GROUP BY p.`id_product`';

        $result = $this->getModuleTools()->query($sql);
        $this->rowsNumber = $this->getModuleTools()->query('SELECT FOUND_ROWS()')->fetchColumn();

        return $result;
    }

    public function productsAttributes()
    {
        return null;
    }

    public function productsAttributesName()
    {
        return null;
    }

    public function productsAttributesValue()
    {
        return null;
    }

    public function productsDependsOnStock($obj)
    {
        return StockAvailable::dependsOnStock($obj->id);
    }

    public function productsSupplierNameAll($obj)
    {
        $sups = $this->getModuleTools()->executeS('
		SELECT DISTINCT(s.`name`)
		FROM `' . _DB_PREFIX_ . 'product_supplier` ps
		LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON (ps.`id_supplier`= s.`id_supplier`)
		LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (ps.`id_product`= p.`id_product`)
		WHERE ps.`id_product` = ' . $obj->id);
        $suppliers = array();
        foreach ($sups as $sup) {
            $suppliers[] = $sup['name'];
        }

        return implode(',', $suppliers);
    }

    public function productsIdSupplierAll($obj)
    {
        $sups = $this->getModuleTools()->executeS('
		SELECT DISTINCT(ps.`id_supplier`)
		FROM `' . _DB_PREFIX_ . 'product_supplier` ps
		JOIN `' . _DB_PREFIX_ . 'product` p ON (ps.`id_product`= p.`id_product`)
		WHERE ps.`id_product` = ' . $obj->id);
        $suppliers = array();
        foreach ($sups as $sup) {
            $suppliers[] = $sup['id_supplier'];
        }

        return implode(',', $suppliers);
    }

    public function productsFeatures($obj, $ae)
    {
        $features = $obj->getFrontFeaturesStatic($this->ae->id_lang, $obj->id);
        $feats = array();
        foreach ($features as $feature) {
            $feats[] = $feature['name'] . '-' . $feature['value'];
        }

        return implode(',', $feats);
    }

    public function productsAttachments($obj, $ae)
    {
        $attachments_url = array();
        $attachments = $obj->getAttachments($this->ae->id_lang);

        foreach ($attachments as $attachment) {
            $attachments_url[] = 'http://' . $_SERVER['HTTP_HOST'] .
                __PS_BASE_URI__ . 'download/' . $attachment['file'];
        }

        return implode(",", $attachments_url);
    }

    public function productsWarehouse($obj)
    {
        $warehouse = $this->getModuleTools()->executeS('
		SELECT `id_warehouse`
		FROM `' . _DB_PREFIX_ . 'warehouse_product_location`
		WHERE `id_product` = ' . (int)$obj->id . ' AND `id_product_attribute` = 0');

        if (isset($warehouse[0])) {
            return $warehouse[0]['id_warehouse'];
        } else {
            return '';
        }
    }

    public function productsPriceTex($obj)
    {
        return $obj->getPrice(false);
    }

    public function productsPriceTax($obj)
    {
        return $obj->getPrice(true);
    }

    public function productsFileUrl($obj)
    {
        $link = '';
        $filename = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            '
			SELECT `filename`
			FROM `' . _DB_PREFIX_ . 'product_download`
			WHERE `id_product` = ' . (int)$obj->id
        );

        if ($filename) {
            $link .= _PS_BASE_URL_ . __PS_BASE_URI__ . 'index.php?controller=get-file&';
            $link .= 'key=' . $filename . '-orderdetail';
        }

        return $link;
    }

    public function productsTaxRate($obj)
    {
        return $obj->getTaxesRate();
    }

    public function productsQuantity($obj)
    {
        return Product::getQuantity((int)$obj->id);
    }

    public function productsPriceTaxNodiscount($obj)
    {
        return $obj->getPrice(true, null, 6, null, false, false);
    }

    public function productsUrlProduct($obj, $ae)
    {
        $category = Category::getLinkRewrite((int)$obj->id_category_default, (int)$this->ae->id_lang);

        return $this->context->link->getProductLink((int)$obj->id, $obj->link_rewrite[$this->ae->id_lang], $category);
    }

    //manufacturer name
    public function productsManufacturerName($obj)
    {
        return $obj->getWsManufacturerName();
    }

    public function productsCategoriesNames($obj, $ae)
    {
        $categories = $obj->getCategories();
        $cats = array();
        foreach ($categories as $cat) {
            $category = new Category($cat, $this->ae->id_lang);
            $cats[] = $category->name;
        }

        return implode(',', $cats);
    }

    public function productsCategoriesPath($obj, $ae)
    {
        $categories = $obj->getCategories();
        $paths = array();
        foreach ($categories as $cat) {
            $category = new Category($cat, $this->ae->id_lang);
            $parents = $category->getParentsCategories($this->ae->id_lang);
            $parentWithoutIds = null;

            foreach ($parents as $parent) {
                if ($parent['id_category'] != 1 and $parent['id_category'] != 2) {
                    $parentWithoutIds[] = $parent['name'];
                }
            }

            if ($parentWithoutIds != null) {
                $paths[] = implode(' > ', array_reverse($parentWithoutIds));
            }
        }

        return implode(',', $paths);
    }

    public function productsSupplierReference($obj)
    {
        // build query
        $query = new DbQuery();
        $query->select('ps.product_supplier_reference');
        $query->from('product_supplier', 'ps');
        $query->where(
            'ps.id_product = ' . (int)$obj->id . ' AND ps.id_product_attribute = 0'
        );
        $suppliers = null;
        $result = null;
        $suppliers = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

        foreach ($suppliers as $supplier) {
            if ($supplier['product_supplier_reference']) {
                $result[] = $supplier['product_supplier_reference'];
            }
        }

        return (is_array($result) ? implode(',', array_unique($result)) : '');
    }

    public function productsCategoriesIds($obj, $ae)
    {
        $categories = $obj->getCategories();
        $cats = array();
        foreach ($categories as $cat) {
            $category = new Category($cat, $this->ae->id_lang);
            $cats[] = $category->id;
        }

        return implode(',', $cats);
    }

    public function productsNameCategoryDefault($obj, $ae)
    {
        $category = new Category($obj->id_category_default, $this->ae->id_lang);

        return $category->name;
    }

    public function productsImages($obj, $ae)
    {
        $imagelinks = array();
        $images = $obj->getImages($obj->id);
        foreach ($images as $image) {
            $imagelinks[] = 'http://' . $this->link->getImageLink(
                $obj->link_rewrite[$this->ae->id_lang],
                $obj->id . '-' . $image['id_image'],
                $this->ae->image_type
            );
        }

        return implode(',', $imagelinks);
    }

    public function productsImage($obj, $ae)
    {
        $image = Product::getCover($obj->id);
        $imageLink = 'http://' . $this->link->getImageLink(
            $obj->link_rewrite[$this->ae->id_lang],
            $obj->id . '-' . $image['id_image'],
            $this->ae->image_type
        );

        return $imageLink;
    }

    public function productsImagePosition($obj)
    {
        $imagePosition = array();
        $images = $obj->getImages($obj->id);
        foreach ($images as $image) {
            $imagePosition[] = $image['position'];
        }

        return implode(',', $imagePosition);
    }

    public function productsImageAlt($obj, $ae)
    {
        $imagealts = array();
        $images = $obj->getImages($this->ae->id_lang);
        foreach ($images as $image) {
            if ($image['legend']) {
                $imagealts[] = $image['legend'];
            }
        }

        return implode(',', $imagealts);
    }

    public function productsDefaultCombination()
    {
        return '';
    }

    public function productsTags($obj, $ae)
    {
        return $obj->getTags($this->ae->id_lang);
    }

    public function productsAccessories($obj, $ae)
    {
        if ($accessories = $obj->getAccessories($this->ae->id_lang, false)) {
            $accessoriesRef = array();
            foreach ($accessories as $value) {
                $accessoriesRef[] = $value['reference'];
            }
            return implode(',', $accessoriesRef);
        } else {
            return '';
        }
    }

    public function productsUnitPrice($obj)
    {
        return $obj->unit_price;
    }

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

    public function combinationIdProductAttribute($obj, $product_attribute)
    {
        return $product_attribute['id_product_attribute'];
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

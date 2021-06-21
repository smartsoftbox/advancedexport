<?php
/**
 * 2020 Smart Soft.
 *
 * @author    Marcin Kubiak
 * @copyright Smart Soft
 * @license   Commercial License
 *  International Registered Trademark & Property of Smart Soft
 */

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once dirname(__FILE__) . '/../../controllers/admin/AdminAdvancedExportBaseController.php';

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE); // todo check if you really need this line

class AdminAdvancedExportPrestaImportController extends AdminImportControllerCore
{

    public function __construct()
    {
        parent::__construct();

        if ((int) Tools::getValue('entity') ===
            $this->entities[$this->trans('Combinations', array(), 'Admin.Global')]) {
            $this->available_fields['id_product_attribute'] = array('label' => $this->trans('Product Attribute Id', array()));
        }
    }

    protected function excelToCsvFile($filename)
    {
        if (preg_match('#(.*?)\.(csv)#is', $filename)) {
            $dest_file = self::getPath((string)(preg_replace('/\.{2,}/', '.', $filename)));
        } else {
            $csv_folder = self::getPath();
            $excel_folder = $csv_folder . 'csvfromexcel/';
            $info = pathinfo($filename);
            $csv_name = basename($filename, '.' . $info['extension']) . '.csv';
            $dest_file = $excel_folder . $csv_name;

            if (!is_dir($excel_folder)) {
                mkdir($excel_folder);
            }

            if (!is_file($dest_file)) {
                // validator error function exists
                $reader_excel = IOFactory::createReaderForFile($csv_folder . $filename);
                $reader_excel->setReadDataOnly(true);
                $excel_file = $reader_excel->load($csv_folder . $filename);
                // validator error function exists
                $csv_writer = IOFactory::createWriter($excel_file, 'Csv');

                $csv_writer->setSheetIndex(0);
                $csv_writer->setDelimiter(';');
                $csv_writer->save($dest_file);
            }
        }

        return $dest_file;
    }

    public static function getPath($file = '')
    {
        $id = Tools::getValue('id');
        // when there is no file specify it only needs path without filename
        $path = _AE_IMPORT_PATH_ . $id . '/' . $file;
        return $path;
    }

    protected function receiveTab()
    {
        $type_value = Tools::getValue('type_value') ? Tools::getValue('type_value') : array();
        foreach ($type_value as $nb => $type) {
            if ($type != 'no') {
                self::$column_mask[$type] = $nb;
            }
        }
    }

    protected function attributeImportOne($info, $default_language, &$groups, &$attributes, $regenerate, $shop_is_feature_active, $validateOnly = false)
    {
        /* start modification */
        if(isset($info['id_product_attribute']) && isset($info['id_product'])) {
            $id_product_attribute = $info['id_product_attribute'];
            $product = new Product((int) $info['id_product'], false, $default_language);
            $attribute_combinations = $product->getAttributeCombinationsById($id_product_attribute, $default_language);
            foreach ($attribute_combinations as $attribute_combination) {
                foreach ($attribute_combination as $key => $field) {
                    if(!isset($info[$key])) {
                        $info[$key] = $field;
                    }
                }
            }
        } else {
            AdminImportController::setDefaultValues($info);
        }
        /* end modification */

        if (!$shop_is_feature_active) {
            $info['shop'] = 1;
        } elseif (!isset($info['shop']) || empty($info['shop'])) {
            $info['shop'] = implode($this->multiple_value_separator, Shop::getContextListShopID());
        }

        // Get shops for each attributes
        $info['shop'] = explode($this->multiple_value_separator, $info['shop']);

        $id_shop_list = array();
        if (is_array($info['shop']) && count($info['shop'])) {
            foreach ($info['shop'] as $shop) {
                if (!empty($shop) && !is_numeric($shop)) {
                    $id_shop_list[] = Shop::getIdByName($shop);
                } elseif (!empty($shop)) {
                    $id_shop_list[] = $shop;
                }
            }
        }

        if (isset($info['id_product']) && $info['id_product']) {
            $product = new Product((int)$info['id_product'], false, $default_language);
        } elseif (Tools::getValue('match_ref') && isset($info['product_reference']) && $info['product_reference']) {
            $datas = Db::getInstance()->getRow('
				SELECT p.`id_product`
				FROM `' . _DB_PREFIX_ . 'product` p
				' . Shop::addSqlAssociation('product', 'p') . '
				WHERE p.`reference` = "' . pSQL($info['product_reference']) . '"
			', false);
            if (isset($datas['id_product']) && $datas['id_product']) {
                $product = new Product((int)$datas['id_product'], false, $default_language);
            } else {
                return;
            }
        } else {
            return;
        }

        $id_image = array();

        if (isset($info['image_url']) && $info['image_url']) {
            $info['image_url'] = explode($this->multiple_value_separator, $info['image_url']);

            if (is_array($info['image_url']) && count($info['image_url'])) {
                foreach ($info['image_url'] as $key => $url) {
                    $url = trim($url);
                    $product_has_images = (bool)Image::getImages($this->context->language->id, $product->id);

                    $image = new Image();
                    $image->id_product = (int)$product->id;
                    $image->position = Image::getHighestPosition($product->id) + 1;
                    $image->cover = (!$product_has_images) ? true : false;

                    if (isset($info['image_alt'])) {
                        $alt = self::split($info['image_alt']);
                        if (isset($alt[$key]) && Tools::strlen($alt[$key]) > 0) {
                            $alt = self::createMultiLangField($alt[$key]);
                            $image->legend = $alt;
                        }
                    }

                    $field_error = $image->validateFields(UNFRIENDLY_ERROR, true);
                    $lang_field_error = $image->validateFieldsLang(UNFRIENDLY_ERROR, true);

                    if ($field_error === true &&
                        $lang_field_error === true &&
                        !$validateOnly &&
                        $image->add()) {
                        $image->associateTo($id_shop_list);
                        // FIXME: 2s/image !
                        if (!AdminImportController::copyImg($product->id, $image->id, $url, 'products', !$regenerate)) {
                            $this->warnings[] = $this->trans(
                                'Error copying image: %url%',
                                array('%url%' => Tools::htmlentitiesUTF8($url)),
                                'Admin.Advparameters.Notification'
                            );
                            $image->delete();
                        } else {
                            $id_image[] = (int)$image->id;
                        }
                        // until here
                    } else {
                        if (!$validateOnly) {
                            $this->warnings[] = $this->trans(
                                '%data% cannot be saved',
                                array(
                                    '%data%' => (isset($image->id_product) ? ' (' . Tools::htmlentitiesUTF8($image->id_product) . ')' : ''),
                                ),
                                'Admin.Advparameters.Notification'
                            );
                        }
                        if ($field_error !== true || $lang_field_error !== true) {
                            $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '') . mysql_error();
                        }
                    }
                }
            }
        } elseif (isset($info['image_position']) && $info['image_position']) {
            $info['image_position'] = explode($this->multiple_value_separator, $info['image_position']);

            if (is_array($info['image_position']) && count($info['image_position'])) {
                foreach ($info['image_position'] as $position) {
                    // choose images from product by position
                    $images = $product->getImages($default_language);

                    if ($images) {
                        foreach ($images as $row) {
                            if ($row['position'] == (int)$position) {
                                $id_image[] = (int)$row['id_image'];

                                break;
                            }
                        }
                    }
                    if (empty($id_image)) {
                        $this->warnings[] = sprintf(
                            $this->trans('No image was found for combination with id_product = %s and image position = %s.',
                                array(), 'Admin.Advparameters.Notification'),
                            Tools::htmlentitiesUTF8($product->id),
                            (int)$position
                        );
                    }
                }
            }
        }

        $id_attribute_group = 0;
        // groups
        $groups_attributes = array();
        if (isset($info['group'])) {
            foreach (explode($this->multiple_value_separator, $info['group']) as $key => $group) {
                if (empty($group)) {
                    continue;
                }
                $tab_group = explode(':', $group);
                $group = trim($tab_group[0]);
                if (!isset($tab_group[1])) {
                    $type = 'select';
                } else {
                    $type = trim($tab_group[1]);
                }

                // sets group
                $groups_attributes[$key]['group'] = $group;

                // if position is filled
                if (isset($tab_group[2])) {
                    $position = trim($tab_group[2]);
                } else {
                    $position = false;
                }

                if (!isset($groups[$group])) {
                    $obj = new AttributeGroup();
                    $obj->is_color_group = false;
                    $obj->group_type = pSQL($type);
                    $obj->name[$default_language] = $group;
                    $obj->public_name[$default_language] = $group;
                    $obj->position = (!$position) ? AttributeGroup::getHigherPosition() + 1 : $position;

                    if (($field_error = $obj->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                        ($lang_field_error = $obj->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true) {
                        // here, cannot avoid attributeGroup insertion to avoid an error during validation step.
                        //if (!$validateOnly) {
                        $obj->add();
                        $obj->associateTo($id_shop_list);
                        $groups[$group] = $obj->id;
                        //}
                    } else {
                        $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '');
                    }

                    // fills groups attributes
                    $id_attribute_group = $obj->id;
                    $groups_attributes[$key]['id'] = $id_attribute_group;
                } else {
                    // already exists

                    $id_attribute_group = $groups[$group];
                    $groups_attributes[$key]['id'] = $id_attribute_group;
                }
            }
        }

        // inits attribute
        $id_product_attribute = 0;
        $id_product_attribute_update = false;
        $attributes_to_add = array();

        // for each attribute
        if (isset($info['attribute'])) {
            foreach (explode($this->multiple_value_separator, $info['attribute']) as $key => $attribute) {
                if (empty($attribute)) {
                    continue;
                }
                $tab_attribute = explode(':', $attribute);
                $attribute = trim($tab_attribute[0]);
                // if position is filled
                if (isset($tab_attribute[1])) {
                    $position = trim($tab_attribute[1]);
                } else {
                    $position = false;
                }

                if (isset($groups_attributes[$key])) {
                    $group = $groups_attributes[$key]['group'];
                    if (!isset($attributes[$group . '_' . $attribute]) && count($groups_attributes[$key]) == 2) {
                        $id_attribute_group = $groups_attributes[$key]['id'];
                        $obj = new Attribute();
                        // sets the proper id (corresponding to the right key)
                        $obj->id_attribute_group = $groups_attributes[$key]['id'];
                        $obj->name[$default_language] = str_replace('\n', '', str_replace('\r', '', $attribute));
                        $obj->position = (!$position && isset($groups[$group])) ? Attribute::getHigherPosition($groups[$group]) + 1 : $position;

                        if (($field_error = $obj->validateFields(UNFRIENDLY_ERROR, true)) === true &&
                            ($lang_field_error = $obj->validateFieldsLang(UNFRIENDLY_ERROR, true)) === true) {
                            if (!$validateOnly) {
                                $obj->add();
                                $obj->associateTo($id_shop_list);
                                $attributes[$group . '_' . $attribute] = $obj->id;
                            }
                        } else {
                            $this->errors[] = ($field_error !== true ? $field_error : '') . (isset($lang_field_error) && $lang_field_error !== true ? $lang_field_error : '');
                        }
                    }

                    $info['minimal_quantity'] = isset($info['minimal_quantity']) && $info['minimal_quantity'] ? (int)$info['minimal_quantity'] : 1;
                    $info['low_stock_threshold'] = empty($info['low_stock_threshold']) && '0' != $info['low_stock_threshold'] ? null : (int)$info['low_stock_threshold'];
                    $info['low_stock_alert'] = !empty($info['low_stock_alert']);

                    $info['wholesale_price'] = str_replace(',', '.', $info['wholesale_price']);
                    $info['price'] = str_replace(',', '.', $info['price']);
                    $info['ecotax'] = str_replace(',', '.', $info['ecotax']);
                    $info['weight'] = str_replace(',', '.', $info['weight']);
                    $info['available_date'] = Validate::isDate($info['available_date']) ? $info['available_date'] : null;

                    if (!Validate::isEan13($info['ean13'])) {
                        $this->warnings[] = $this->trans(
                            'EAN13 "%ean13%" has incorrect value for product with id %id%.',
                            array(
                                '%ean13%' => Tools::htmlentitiesUTF8($info['ean13']),
                                '%id%' => Tools::htmlentitiesUTF8($product->id),
                            ),
                            'Admin.Advparameters.Notification'
                        );
                        $info['ean13'] = '';
                    }

                    if ($info['default_on'] && !$validateOnly) {
                        $product->deleteDefaultAttributes();
                    }

                    // if a reference is specified for this product, get the associate id_product_attribute to UPDATE
                    if (isset($info['reference']) && !empty($info['reference'])) {
                        $id_product_attribute = Combination::getIdByReference($product->id,
                            (string)($info['reference']));

                        // updates the attribute
                        if ($id_product_attribute && !$validateOnly) {
                            // gets all the combinations of this product
                            $attribute_combinations = $product->getAttributeCombinations($default_language);
                            foreach ($attribute_combinations as $attribute_combination) {
                                if ($id_product_attribute && $id_product_attribute === $attribute_combination['id_product_attribute']) {
                                    // FIXME: ~3s/declinaison
                                    $product->updateAttribute(
                                        $id_product_attribute,
                                        (float) $info['wholesale_price'],
                                        (float) $info['price'],
                                        (float) $info['weight'],
                                        0,
                                        (Configuration::get('PS_USE_ECOTAX') ? (float) $info['ecotax'] : 0),
                                        $id_image,
                                        (string) $info['reference'],
                                        (string) $info['ean13'],
                                        ((int) $info['default_on'] ? (int) $info['default_on'] : null),
                                        0,
                                        (string) $info['upc'],
                                        (int) $info['minimal_quantity'],
                                        $info['available_date'],
                                        null,
                                        $id_shop_list,
                                        '',
                                        $info['low_stock_threshold'],
                                        $info['low_stock_alert']
                                    );
                                    $id_product_attribute_update = true;
                                    if (isset($info['supplier_reference']) && !empty($info['supplier_reference'])) {
                                        $product->addSupplierReference($product->id_supplier, $id_product_attribute, $info['supplier_reference']);
                                    }
                                    // until here
                                }
                            }
                        }
                    }

                    // if no attribute reference is specified, creates a new one
                    if (!$id_product_attribute && !$validateOnly) {
                        $id_product_attribute = $product->addCombinationEntity(
                            (float)$info['wholesale_price'],
                            (float)$info['price'],
                            (float)$info['weight'],
                            0,
                            (Configuration::get('PS_USE_ECOTAX') ? (float)$info['ecotax'] : 0),
                            (int)$info['quantity'],
                            $id_image,
                            (string)$info['reference'],
                            0,
                            (string)$info['ean13'],
                            ((int)$info['default_on'] ? (int)$info['default_on'] : null),
                            0,
                            (string)$info['upc'],
                            (int)$info['minimal_quantity'],
                            $id_shop_list,
                            $info['available_date'],
                            '',
                            $info['low_stock_threshold'],
                            $info['low_stock_alert']
                        );

                        if (isset($info['supplier_reference']) && !empty($info['supplier_reference'])) {
                            $product->addSupplierReference($product->id_supplier, $id_product_attribute,
                                $info['supplier_reference']);
                        }
                    }

                    // fills our attributes array, in order to add the attributes to the product_attribute afterwards
                    if (isset($attributes[$group . '_' . $attribute])) {
                        $attributes_to_add[] = (int)$attributes[$group . '_' . $attribute];
                    }

                    // after insertion, we clean attribute position and group attribute position
                    if (!$validateOnly) {
                        $obj = new Attribute();
                        $obj->cleanPositions((int)$id_attribute_group, false);
                        AttributeGroup::cleanPositions();
                    }
                }
            }
        }


        // if a reference is specified for this product, get the associate id_product_attribute to UPDATE
        if (isset($info['id_product_attribute']) && !empty($info['id_product_attribute']) && !isset($info['attribute']) && !isset($info['group'])) {
            $id_product_attribute = $info['id_product_attribute'];

            // updates the attribute
            if ($id_product_attribute && !$validateOnly) {
                // gets all the combinations of this product
                $attribute_combinations = $product->getAttributeCombinationsById($id_product_attribute,
                    $default_language);
                // FIXME: ~3s/declinaison
                foreach ($attribute_combinations as $attribute_combination) {
                    // FIXME: ~3s/declinaison
                    $product->updateAttribute(
                        $id_product_attribute,
                        (float) $info['wholesale_price'],
                        (float) $info['price'],
                        (float) $info['weight'],
                        (float) $info['unit_price_impact'],
                        (float) $info['ecotax'],
                        $id_image,
                        (string) $info['reference'],
                        (string) $info['ean13'],
                        ((int) $info['default_on'] ? (int) $info['default_on'] : null),
                        $info['location'],
                        (string) $info['upc'],
                        (int) $info['minimal_quantity'],
                        $info['available_date'],
                        null,
                        $id_shop_list,
                        $info['isbn'],
                        $info['low_stock_threshold'],
                        $info['low_stock_alert'],
                        (isset($info['mpn']) ? $info['mpn'] : '')
                    );
                    $id_product_attribute_update = true;
                    if (isset($info['supplier_reference']) && !empty($info['supplier_reference'])) {
                        $product->addSupplierReference($product->id_supplier, $id_product_attribute, $info['supplier_reference']);
                    }
                    // until here
                }
            }
        }

        $product->checkDefaultAttributes();
        if (!$product->cache_default_attribute && !$validateOnly) {
            Product::updateDefaultAttribute($product->id);
        }
        if ($id_product_attribute) {
            if (!$validateOnly && !isset($info['id_product_attribute'])) {
                // now adds the attributes in the attribute_combination table
                if ($id_product_attribute_update) {
                    Db::getInstance()->execute('
						DELETE FROM ' . _DB_PREFIX_ . 'product_attribute_combination
						WHERE id_product_attribute = ' . (int)$id_product_attribute);
                }

                foreach ($attributes_to_add as $attribute_to_add) {
                    Db::getInstance()->execute('
						INSERT IGNORE INTO ' . _DB_PREFIX_ . 'product_attribute_combination (id_attribute, id_product_attribute)
						VALUES (' . (int)$attribute_to_add . ',' . (int)$id_product_attribute . ')', false);
                }
            }

            // set advanced stock managment
            if (isset($info['advanced_stock_management'])) {
                if ($info['advanced_stock_management'] != 1 && $info['advanced_stock_management'] != 0) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management has incorrect value. Not set for product with id %id%.',
                        array(
                            '%id%' => Tools::htmlentitiesUTF8($product->id),
                        ),
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $info['advanced_stock_management'] == 1) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, cannot enable on product with id %id%.',
                        array(
                            '%id%' => Tools::htmlentitiesUTF8($product->id),
                        ),
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!$validateOnly) {
                    $product->setAdvancedStockManagement($info['advanced_stock_management']);
                }
                // automaticly disable depends on stock, if a_s_m set to disabled
                if (!$validateOnly && StockAvailable::dependsOnStock($product->id) == 1 && $info['advanced_stock_management'] == 0) {
                    StockAvailable::setProductDependsOnStock($product->id, 0, null, $id_product_attribute);
                }
            }

            // Check if warehouse exists
            if (isset($info['warehouse']) && $info['warehouse']) {
                if (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, warehouse is not set on product with id %id%.',
                        array('%id%' => Tools::htmlentitiesUTF8($product->id)),
                        'Admin.Advparameters.Notification'
                    );
                } else {
                    if (Warehouse::exists($info['warehouse'])) {
                        $warehouse_location_entity = new WarehouseProductLocation();
                        $warehouse_location_entity->id_product = $product->id;
                        $warehouse_location_entity->id_product_attribute = $id_product_attribute;
                        $warehouse_location_entity->id_warehouse = $info['warehouse'];
                        if (!$validateOnly) {
                            if (WarehouseProductLocation::getProductLocation($product->id, $id_product_attribute,
                                    $info['warehouse']) !== false) {
                                $warehouse_location_entity->update();
                            } else {
                                $warehouse_location_entity->save();
                            }
                            StockAvailable::synchronize($product->id);
                        }
                    } else {
                        $this->warnings[] = $this->trans(
                            'Warehouse did not exist, cannot set on product %name%.',
                            array(
                                '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language]),
                            ),
                            'Admin.Advparameters.Notification'
                        );
                    }
                }
            }

            // stock available
            if (isset($info['depends_on_stock'])) {
                if ($info['depends_on_stock'] != 0 && $info['depends_on_stock'] != 1) {
                    $this->warnings[] = $this->trans(
                        'Incorrect value for "Depends on stock" for product %name% ',
                        array(
                            '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language]),
                        ),
                        'Admin.Notifications.Error'
                    );
                } elseif ((!$info['advanced_stock_management'] || $info['advanced_stock_management'] == 0) && $info['depends_on_stock'] == 1) {
                    $this->warnings[] = $this->trans(
                        'Advanced stock management is not enabled, cannot set "Depends on stock" for product %name% ',
                        array(
                            '%name%' => Tools::htmlentitiesUTF8($product->name[$default_language]),
                        ),
                        'Admin.Advparameters.Notification'
                    );
                } elseif (!$validateOnly) {
                    StockAvailable::setProductDependsOnStock($product->id, $info['depends_on_stock'], null,
                        $id_product_attribute);
                }

                // This code allows us to set qty and disable depends on stock
                if (isset($info['quantity'])) {
                    // if depends on stock and quantity, add quantity to stock
                    if ($info['depends_on_stock'] == 1) {
                        $stock_manager = StockManagerFactory::getManager();
                        $price = str_replace(',', '.', $info['wholesale_price']);
                        if ($price == 0) {
                            $price = 0.000001;
                        }
                        $price = round((float)$price, 6);
                        $warehouse = new Warehouse($info['warehouse']);
                        if (!$validateOnly && $stock_manager->addProduct((int)$product->id, $id_product_attribute,
                                $warehouse, (int)$info['quantity'], 1, $price, true)) {
                            StockAvailable::synchronize((int)$product->id);
                        }
                    } elseif (!$validateOnly) {
                        if ($shop_is_feature_active) {
                            foreach ($id_shop_list as $shop) {
                                StockAvailable::setQuantity((int)$product->id, $id_product_attribute,
                                    (int)$info['quantity'], (int)$shop);
                            }
                        } else {
                            StockAvailable::setQuantity((int)$product->id, $id_product_attribute,
                                (int)$info['quantity'], $this->context->shop->id);
                        }
                    }
                }
            } elseif (!$validateOnly) { // if not depends_on_stock set, use normal qty
                if ($shop_is_feature_active) {
                    foreach ($id_shop_list as $shop) {
                        StockAvailable::setQuantity((int)$product->id, $id_product_attribute, (int)$info['quantity'],
                            (int)$shop);
                    }
                } else {
                    StockAvailable::setQuantity((int)$product->id, $id_product_attribute, (int)$info['quantity'],
                        $this->context->shop->id);
                }
            }
        }
    }
}

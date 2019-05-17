<?php 

namespace PichchDev\CustomSorting\Block\Plugin\Product\ProductList;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar {
    public function setCollection($collection) {
      $this->_collection = $collection;
      $this->_collection->setCurPage($this->getCurrentPage());
      $limit = (int) $this->getLimit();

      if ($limit) {
          $this->_collection->setPageSize($limit);
      }

      if ($this->getCurrentOrder()) {
          switch ($this->getCurrentOrder()) {
            case 'most_viewed':
            $this->_collection->addAttributeToSelect('*');
            $this->_collection
                    ->getSelect()
                    ->join(
                        ['report_viewed_product_index'],
                        "e.entity_id = report_viewed_product_index.product_id",
                        array('product_views' => 'COUNT(report_viewed_product_index.product_id)')
                    )
                    ->join(
                        ['cataloginventory_stock_item'],
                        "e.entity_id = cataloginventory_stock_item.product_id"
                    )
                    ->group("report_viewed_product_index.product_id")
                    ->order(array('cataloginventory_stock_item.is_in_stock DESC','product_views desc'));
            break;
            case 'best_seller':
            $this->_collection->addAttributeToSelect('*');
            $this->_collection
                    ->getSelect()
                    ->join(
                        ['sales_order_item'],
                        "e.entity_id = sales_order_item.product_id",
                        array('orders' => 'COUNT(sales_order_item.product_id)')
                    )
                    ->join(
                        ['cataloginventory_stock_item'],
                        "e.entity_id = cataloginventory_stock_item.product_id"
                    )
                    ->group("sales_order_item.product_id")
                    ->order(array('cataloginventory_stock_item.is_in_stock DESC','orders desc'));
            break;
            case 'discount_desc':
            $this->_collection->addAttributeToSelect('*');
            $this->_collection
                    ->getSelect()
                    ->join(
                        ['catalog_product_index_price'],
                        "e.entity_id = catalog_product_index_price.entity_id",
                        array('discount' => '((catalog_product_index_price.price-catalog_product_index_price.final_price)/catalog_product_index_price.price)')
                    )
                    ->join(
                        ['cataloginventory_stock_item'],
                        "e.entity_id = cataloginventory_stock_item.product_id"
                    )
                    ->group("catalog_product_index_price.entity_id")
                    ->order(array('cataloginventory_stock_item.is_in_stock DESC','discount desc'));
            break;
            case 'price_asc':
            $this->_collection
                ->getSelect()
                ->join(
                    ['cataloginventory_stock_item'],
                    "e.entity_id = cataloginventory_stock_item.product_id"
                )
                ->order(array('cataloginventory_stock_item.is_in_stock DESC','price_index.final_price ASC'));
            break;
            case 'price_desc':
            $this->_collection
                ->getSelect()
                ->join(
                    ['cataloginventory_stock_item'],
                    "e.entity_id = cataloginventory_stock_item.product_id"
                )
                ->order(array('cataloginventory_stock_item.is_in_stock DESC','price_index.final_price DESC'));
            break;
            default:
            $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
            break;
          }
      }
      return $this;
    }
  }
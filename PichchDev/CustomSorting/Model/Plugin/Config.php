<?php
namespace PichchDev\CustomSorting\Model\Plugin;
class Config {

   public function afterGetAttributeUsedForSortByArray(
   \Magento\Catalog\Model\Config $catalogConfig, $options
   ) {

    /** comment out default sorting options **/
    //unset($options['position']);
    unset($options['name']);
    unset($options['price']);
    

    /** add custom sorting options (dropdown list) **/
    $options['most_viewed'] = __('Most Viewed');
    $options['best_seller'] = __('Best Seller');
    $options['discount_desc'] = __('Discount (%)');
    $options['price_asc'] = __('Price (Low to High)');
    $options['price_desc'] = __('Price (High to Low)');
    return $options;
   }
}
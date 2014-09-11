<?php
/**
 * @var ProductController $this
 * @var ProductList $productList
 */
?>
<div class="m20 nofloat">
  <ul id="bike-list" class="bike-list">
    <?php
    $this->widget('FListView', array(
      'id' => 'vitrine',
      'htmlOptions'   => array('class' => 'm20'),
      'dataProvider'  => $productList->getProducts(),
      'itemView'      => '_product_block',
      'pagerCssClass' => 'page_filter m20 clearfix',
    )); ?>
  </ul>
</div>

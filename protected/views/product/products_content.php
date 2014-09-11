<?php
/**
 * @var ProductController $this
 * @var FActiveRecord $model
 * @var array $_data_
 * @var ProductList $productList
 */
?>

<?php $this->renderPartial('/breadcrumbs');?>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">

      <?php echo $this->renderPartial('_sections_menu', $_data_)?>

    </div>
    <div class="span10">

      <?php if( !empty($model->notice) ) {?>
      <div class="text-container float-none s12" style="padding: 10px">
        <?php echo $model->notice?>
      </div>
      <?php } ?>

      <?php $this->renderPartial('_products', $_data_)?>

    </div>
  </div>
</div>
<?php
/**
 * @author Alexey Tatarinov <tatarinov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 *
 * return array(
 *  'featuresBehavior' => array('class' => 'backend.modules.product.modules.feature.frontend.FeatureBehavior'),
 * );
 */
Yii::import('backend.modules.product.modules.feature.frontend.*');

/**
 * Class FeatureBehavior
 *
 *  @property FActiveRecord $owner
 */
class FeatureBehavior extends AssociationBehavior
{
  /**
   * @return Feature[]
   */
  public function getFeatures()
  {
    return $this->getAssociationForMe('Feature')->getModels();
  }
} 
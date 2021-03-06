<?php
/**
 * @author Alexey Tatarinov <tatarinov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.widgets
 */
class BAssociationButton extends CWidget
{
  public $name;

  public $model;

  public $count = null;

  public $iframeAction;

  public $ajaxAction = 'association';

  public $parameters = array();

  public $assignerOptions = array();

  public function init()
  {
    $parameters = array(
      'popup' => true,
      'srcId' => $this->model->getPrimaryKey(),
      'src' => get_class($this->model),
      'dst' => $this->name
    );

    $this->count = $this->getAssociationsCount($parameters);

    foreach($this->parameters as $name => $value)
    {
      if( preg_match('/\w+\[\w+\]/', $name) )
        $parameters[$name] = $value;
      else
        $parameters[ucfirst($this->name)."[".$name."]"] = $value;
    }

    $this->parameters = $parameters;
  }

  public function run()
  {
    Yii::app()->controller->widget('BAssignerButton', array(
      'label' => $this->count ? '<span>'.$this->count.'</span>' : '',
      'encodeLabel' => false,
      'bindHandlerByClass' => 'js-'.str_replace('_', '-',Utils::toSnakeCase($this->name)).'-association',
      'htmlOptions' => array(
        'class' => 'btn-assign'.($this->count ? " active" : ""),
        'href' => '#'.($this->count ? $this->count : ''),
        'rel' => 'tooltip',
        'data-original-title' => 'Привязка',
        'data-iframeurl' => Yii::app()->controller->createUrl($this->iframeAction, $this->parameters),
        'data-submiturl' => Yii::app()->controller->createUrl($this->ajaxAction, $this->parameters),
      ),
      'assignerOptions' => $this->assignerOptions
    ));
  }

  protected function getAssociationsCount($parameters)
  {
    $criteria = new CDbCriteria();

    $criteria->addColumnCondition(array('src' => $parameters['src']));
    $criteria->addColumnCondition(array('src_id' => $parameters['srcId']));
    $criteria->addColumnCondition(array('dst' => $parameters['dst']));

    return BAssociation::model()->count($criteria);
  }
}
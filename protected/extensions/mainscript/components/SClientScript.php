<?php
/**
 * @author Nikita Melnikov <melnikov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package ext.mainscript
 */
class SClientScript extends CClientScript
{
  public $coreScriptPosition = self::POS_END;

  public $defaultScriptFilePosition = self::POS_END;

  /**
   * @param string $output
   *
   * @return void
   */
  public function render(&$output)
  {
    $this->raiseEvent('onBeforeRenderClientScript', new CEvent($this));

    $this->prepareScripts();

    parent::render($output);
  }

  /**
   * @param $url
   * @param null $position
   *
   * @return $this
   */
  public function unregisterScriptFile($url, $position = null)
  {
    if( $position === null )
      $position = $this->defaultScriptFilePosition;

    unset($this->scriptFiles[$position][$url]);

    return $this;
  }

  public function onBeforeRenderClientScript(CEvent $event)
  {
  }

  /**
   * Происходит очистка уже загруженных скриптов,
   * для того, чтобы в нужном порядке загрузить список скриптов
   * По порядку идут:
   *  1) файлы скриптов из ядра Yii
   *  2) основной скрипт
   *  3) восстанавливаются остальные скрипты
   */
  private function prepareScripts()
  {
    //Clean total scripts
    $totalScripts = $this->scriptFiles;
    $this->scriptFiles = array();

    $this->prepareCoreScripts();
    $this->prepareMainScript();
    $this->restoreScripts($totalScripts);
  }

  private function prepareMainScript()
  {
    foreach(Yii::app()->mainscript->getModel()->getScriptList() as $path)
    {
      $url = Yii::app()->assetManager->publish($path, true);

      $this->registerScriptFile($url);

      if( file_exists($sourceMap = $path.'.map') )
        Yii::app()->assetManager->publish($sourceMap, true);
    }
  }

  private function prepareCoreScripts()
  {
    if( Yii::app()->mainscript->mode === 'frontend' )
      $this->coreScripts = array();
  }

  /**
   * @param array $totalScripts
   */
  private function restoreScripts($totalScripts)
  {
    foreach($totalScripts as $position => $scriptList)
    {
      foreach($scriptList as $script)
      {
        $this->registerScriptFile($script, $position);
      }
    }
  }
}

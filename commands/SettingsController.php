<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2018 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace pheme\settings\commands;

use pheme\settings\components\Settings;
use PHP_CodeSniffer\Tokenizers\PHP;
use yii\console\Controller;
use yii\console\widgets\Table;
use yii\helpers\VarDumper;

class SettingsController extends Controller
{
    public $defaultAction = 'list';

    /** @var Settings */
    private $_settings;

    public function init()
    {
        $this->_settings = \Yii::$app->settings;
    }

    public function afterAction($action, $result)
    {
        $this->stdout(PHP_EOL);
        return parent::afterAction($action, $result);
    }

    /**
     * Shows settings sections/keys
     * @param null $section
     */
    public function actionList($section = null)
    {
        $rawConfig = $this->_settings->getRawConfig();
        if ($section) {
            $keys = array_keys($rawConfig[$section]);
        } else {
            $keys = array_keys($rawConfig);
        }
        sort($keys);
        $this->stdout(implode("\n", $keys));
    }

    /**
     * Sets a setting given by section.key
     * @param $key
     * @param $value
     */
    public function actionSet($key, $value)
    {
        if ($this->_settings->set($key, $value)) {
            $this->stdout($this->_settings->get($key));
        } else {
            \Yii::error('Setting could not be changed');
        }
    }

    /**
     * Gets a setting given by section.key
     * @param $key
     */
    public function actionGet($key)
    {
        $this->stdout($this->_settings->get($key));
    }
}

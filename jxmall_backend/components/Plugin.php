<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-10-29
 * Time: 0:36
 */

namespace app\components;
use app\listeners\BaseListener;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use yii\base\Component;
use app\models\Plugin as PluginModel;

class Plugin extends Component
{

    public $currentPlugin;
    public $plugins;
    private $list;
    private $install_type = 0;
    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-29
     * @Time: 11:50
     * @Note:获取数据库标记的已安装的插件列表
     * @return mixed
     */
    public function getList()
    {
        $this->list = PluginModel::find()->where([
            'is_delete' => 0,
        ])->all();
        return $this->list;
    }

    public function registerListeners()
    {
        $list = PluginModel::find()->where([
            'is_delete' => 0,
        ])->all();
        /**
         * @var PluginModel[] $list
         */
        foreach ($list as $item) {
            $Class = '\\app\\plugins\\' . $item->name . '\\Plugin';
            if (class_exists($Class)) {
                /** @var \app\plugins\Plugin $plugin */
                $plugin = new $Class();
                $listeners = $plugin->getListeners();
                foreach ($listeners as $listenerClass) {
                    /**
                     * @var BaseListener $listener
                     */
                    $listener = new $listenerClass();
                    if ($listener instanceof BaseListener) {
                        $listener->register();
                    }
                }
            }
        }
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-10-29
     * @Time: 11:50
     * @Note:扫描插件目录列表
     * @return array
     * @throws \Exception
     */
    public function scanPluginList()
    {
        $baseDir = \Yii::$app->basePath . '/plugins';
        if (!is_dir($baseDir)) {
            return [];
        }
        $handle = opendir($baseDir);
        if (!$handle) {
            throw new \Exception('无法访问目录`' . $baseDir . '`，请确认该目录是否有访问权限。');
        }
        $list = [];
        while (($file = readdir($handle)) !== false) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            if (!is_dir($baseDir . '/' . $file)) {
                continue;
            }
            try {
                $plugin = $this->getPlugin($file);
                $list[] = $plugin;
            } catch (\Exception $e) {
            }
        }
        closedir($handle);
        return $list;
    }

    public function install($name)
    {
        $plugin = $this->getPlugin($name);
        try {
            $this->saveData($plugin);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return true;
    }

    /**
     * @param \app\plugins\Plugin $plugin
     * @return bool
     * @throws \Exception
     */
    private function saveData($plugin)
    {
        $pluginModel = PluginModel::findOne(['name' => $plugin->getName()]);
        $versionFile = \Yii::$app->basePath . '/plugins/' . $plugin->getName() . '/version';
        if (file_exists($versionFile)) {
            $version = file_get_contents($versionFile);
            if (!$version || !preg_match('/^\d*(\.\d*)*$/', $version)) {
                $version = '0.0.0';
            }
        } else {
            file_put_contents($versionFile, '0.0.0');
            $version = '0.0.0';
        }
        if (!$pluginModel) {
            $pluginModel = new PluginModel();
        }
        $pluginModel->name = $plugin->getName();
        $pluginModel->label = $plugin->getLabel();
        $pluginModel->version = $version;
        $pluginModel->is_delete = 0;
        $pluginModel->install_type = $this->install_type;
        $pluginModel->description = $plugin->getDsc();
        $pluginModel->logo = $plugin->getLogo();
        $pluginModel->type = $plugin->getType();
        //$pluginModel
        if (!$pluginModel->save()) {
            throw new \Exception('插件安装失败: ' . (new BaseModel())->responseErrorMsg($pluginModel));
        }
        return true;
    }

    public function getPlugin($name)
    {
        $Class = 'app\\plugins\\' . $name . '\\Plugin';
        if (!class_exists($Class)) {
            throw new ClassNotFoundException('插件`' . $name . '`相关类Plugin不存在。', $Class);
        }
        if (!$this->plugins) {
            $this->plugins = [];
        }
        if (!empty($this->plugins[$name])) {
            return $this->plugins[$name];
        }
        $object = new $Class();
        $this->plugins[$name] = $object;
        return $this->plugins[$name];
    }

    /**
     * @param \app\plugins\Plugin $plugin
     */
    public function setCurrentPlugin($plugin)
    {
        $this->currentPlugin = $plugin;
    }
}

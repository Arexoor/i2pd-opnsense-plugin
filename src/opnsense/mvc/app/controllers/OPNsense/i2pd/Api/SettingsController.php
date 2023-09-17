<?php
namespace OPNsense\i2pd\Api;

use OPNsense\Base\ApiControllerBase;
use OPNsense\i2pd\i2pd;
use OPNsense\Core\Config;
use OPNsense\Core\Backend;

/**
 * Class SettingsController Handles settings related API actions for the i2pd module
 * @package OPNsense\i2pd
 */
class SettingsController extends ApiControllerBase
{
    /**
     * retrieve i2pd settings
     * @return array settings
     * @throws \OPNsense\Base\ModelException
     * @throws \ReflectionException
     */
    public function getAction()
    {
        // define list of configurable settings
        $result = array();
        if ($this->request->isGet()) {
            $mdli2pd = new i2pd();
            $result['i2pd'] = $mdli2pd->getNodes();
        }
        return $result;
    }

    /**
     * update i2pd settings
     * @return array status
     * @throws \OPNsense\Base\ModelException
     * @throws \ReflectionException
     */
    public function setAction()
    {
        $result = array("result" => "failed");
        if ($this->request->isPost()) {
            $backend = new Backend();
            // load model and update with provided data
            $mdli2pd = new i2pd();
            $mdli2pd->setNodes($this->request->getPost("i2pd"));

            // perform validation
            $valMsgs = $mdli2pd->performValidation();
            foreach ($valMsgs as $field => $msg) {
                if (!array_key_exists("validations", $result)) {
                    $result["validations"] = array();
                }
                $result["validations"]["i2pd." . $msg->getField()] = $msg->getMessage();
            }

            // serialize model to config and save
            if ($valMsgs->count() == 0) {
                $mdli2pd->serializeToConfig();
                Config::getInstance()->save();
                $backend->configdRun("i2pd generateConfig");
                $result["result"] = "saved";
            }
        }
        return $result;
    }
}

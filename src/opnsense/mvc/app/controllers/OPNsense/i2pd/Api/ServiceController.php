<?php
namespace OPNsense\i2pd\Api;

use OPNsense\Base\ApiControllerBase;
use OPNsense\Core\Backend;
use OPNsense\i2pd\i2pd;

/**
 * Class ServiceController
 * @package OPNsense\Cron
 */
class ServiceController extends ApiControllerBase
{
    /**
     * reconfigure i2pd
     */
    public function reloadAction()
    {
        if ($this->request->isPost()) {
            $backend = new Backend();
            $i2pd = new i2pd();
            //possible states running, stopped, disabled
            $runStatus = $this->statusAction();
            if ($i2pd->general->Enabled->__toString() == 1) {
                if($runStatus["status"] == 'running') {
                    $bckresult = trim($backend->configdRun("i2pd restart"));
                } elseif ($runStatus["status"] == 'stopped') {
                    $bckresult = trim($backend->configdRun("i2pd start"));
                } elseif ($runStatus["status"] == 'disabled') {
                    $bckresult = trim($backend->configdRun("i2pd stop"));
                }
            } else {
                $bckresult = trim($backend->configdRun("i2pd stop"));
            }
            
            if ($bckresult == "OK") {
                // only return valid json type responses
                return array("message" => "ok");
            }
        }
        return array("message" => "unable to run reload config action");
    }

    /**
     * start i2pd
     */
    public function startAction() {
        if ($this->request->isPost()) {
            $backend = new Backend();
            $bckresult = trim($backend->configdRun("i2pd start"));
            if ($bckresult == "OK") {
                // only return valid json type responses
                return array("message" => "ok");
            }
        }
        return array("message" => "unable to run start action");
    }
    
    /**
     * stop i2pd
     */
    public function stopAction() {
        if ($this->request->isPost()) {
            $backend = new Backend();
            $bckresult = trim($backend->configdRun("i2pd stop"));
            if ($bckresult == "OK") {
                // only return valid json type responses
                return array("message" => "ok");
            }
        }
        return array("message" => "unable to run stop action");
    }
    
    /**
     * reload i2pd
     */
    public function restartAction() {
        if ($this->request->isPost()) {
            $backend = new Backend();
            $bckresult = trim($backend->configdRun("i2pd restart"));
            if ($bckresult == "OK") {
                // only return valid json type responses
                return array("message" => "ok");
            }
        }
        return array("message" => "unable to run restart action");
    }
    
    /**
     * retrieve status of i2pd
     * @return array
     * @throws \Exception
     */
    public function statusAction()
    {
        $backend = new Backend();
        $i2pd = new i2pd();
        $response = $backend->configdRun('i2pd status');

        if (strpos($response, 'is running') > 0) {
            $status = 'running';
        } elseif ($i2pd->general->Enabled->__toString() == 1) {
            $status = 'stopped';
        } else {
            $status = 'disabled';
        }
        return array('status' => $status);
    }
}

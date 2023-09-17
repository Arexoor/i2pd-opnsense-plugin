<?php
namespace OPNsense\i2pd;

/**
 * Class IndexController
 * @package OPNsense\i2pd
 */
class IndexController extends \OPNsense\Base\IndexController
{
    public function indexAction()
    {
        // pick the template to serve to our users.
        $this->view->pick('OPNsense/i2pd/index');
        // fetch form data "general" in
        $this->view->generalForm = $this->getForm("general");
        $this->view->httpProxyForm = $this->getForm("httpProxy");
        $this->view->httpWebconsoleForm = $this->getForm("httpWebconsole");
    }
}

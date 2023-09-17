<?php
function i2pd_enabled()
{
    $model = new \OPNsense\i2pd\i2pd();
    if ((string)$model->general->Enabled == '1') {
        return true;
    }

    return false;
}

function i2pd_firewall($fw)
{
    if (i2pd_enabled()) {
    }
}

function i2pd_services()
{
    global $config;

    $services = array();

    if (i2pd_enabled()) {
        $services[] = array(
            'description' => gettext('The i2p Router'),
            'configd' => array(
                'restart' => array('i2pd restart'),
                'start' => array('i2pd start'),
                'stop' => array('i2pd stop'),
            ),
            'name' => 'i2p',
            'pidfile' => '/var/run/i2pd/i2pd.pid'
        );
    }

    return $services;
}
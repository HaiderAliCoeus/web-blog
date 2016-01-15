<?php

namespace Test;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Test\Model\Test;
use Test\Model\TestTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Test\Model\TestTable' =>  function($sm) {
                    $tableGateway = $sm->get('TestTableGateway');
                    $table = new TestTable($tableGateway);
                    return $table;
                },
                'TestTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Test());
                    return new TableGateway('test', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}

<?php
namespace api\controllers;

class SiteController extends \leon2012\phpapi\Controller 
{
    private $_a;

    public function testAction()
    {
        return 'test';
    }

    public function indexAction()
    {
        return [$this->application->actionName, $this->controller, $this->action, $this->_a];
    }

    public function beforeAction()
    {
        $this->_a = 'b';
    }

    public function redirectAction()
    {
        $this->redirect('site/test', ['a'=>'b', 'c'=>'d']);
    }
}
<?php
namespace api\controllers;

class SiteController extends \leon2012\phpapi\Controller 
{
    private $_a;

    public function testAction()
    {
        return $this->application->request->post('name', 'leon');
    }

    public function indexAction($a, $c)
    {
        return [$this->application->actionName, $this->controller, $this->action, $this->_a, $a, $c];
    }

    public function beforeAction()
    {
        $this->_a = 'b';
    }

    public function redirectAction()
    {
        $this->redirect('site/test', ['a'=>'b', 'c'=>'d']);
    }

    public function postAction()
    {
        return $this->post('name', 'leon');
    }
}
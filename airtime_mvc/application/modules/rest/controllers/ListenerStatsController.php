<?php

class Rest_ListenerStatsController extends Zend_Rest_Controller
{
    public function init()
    {
        $this->view->layout()->disableLayout();

        // Remove reliance on .phtml files to render requests
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        Logging::info("index");
    }

    public function getAction()
    {
        Logging::info("get");
    }

    public function geolocationAction()
    {
        Logging::info("geolocation");
    }

    public function putAction()
    {

    }

    public function postAction()
    {

    }

    public function deleteAction()
    {

    }
}
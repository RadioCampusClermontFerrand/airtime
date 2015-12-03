<?php

class RotationController extends Zend_Controller_Action {

    public function init() {
        $CC_CONFIG = Config::getConfig();
        $baseUrl = Application_Common_OsPath::getBaseDir();

        $headScript = $this->view->headScript();
        AirtimeTableView::injectTableJavaScriptDependencies($headScript, $baseUrl, $CC_CONFIG['airtime_version']);
        $this->view->headScript()->appendFile($baseUrl.'js/airtime/library/library.js?'.$CC_CONFIG['airtime_version'],'text/javascript');
        $this->view->headScript()->appendFile($baseUrl.'js/airtime/library/events/library_showbuilder.js?'.$CC_CONFIG['airtime_version'],'text/javascript');

        $this->view->headScript()->appendFile($baseUrl.'js/airtime/widgets/table.js?'.$CC_CONFIG['airtime_version'], 'text/javascript');
        $this->view->headScript()->appendFile($baseUrl.'js/airtime/schedule/rotation.js?'.$CC_CONFIG['airtime_version'], 'text/javascript');

        $this->view->headLink()->appendStylesheet($baseUrl.'css/datatables/css/ColVis.css?'.$CC_CONFIG['airtime_version']);
        $this->view->headLink()->appendStylesheet($baseUrl.'css/datatables/css/dataTables.colReorder.min.css?'.$CC_CONFIG['airtime_version']);

        $this->view->headLink()->appendStylesheet($baseUrl.'css/dashboard.css?'.$CC_CONFIG['airtime_version']);
        $this->view->headLink()->appendStylesheet($baseUrl.'css/rotations.css?'.$CC_CONFIG['airtime_version']);

        $csrf_namespace = new Zend_Session_Namespace('csrf_namespace');
        $csrf_element = new Zend_Form_Element_Hidden('csrf_token');
        $csrf_element->setValue($csrf_namespace->authtoken)->setRequired('true')->removeDecorator('HtmlTag')->removeDecorator('Label');
        $this->view->csrf = $csrf_element;
    }

    /**
     * Render the Rotation settings page
     */
    public function settingsAction() { }

}
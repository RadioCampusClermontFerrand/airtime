<?php

class Rest_RotationController extends Zend_Rest_Controller {

    public function init() {
        $this->view->layout()->disableLayout();
        // Remove reliance on .phtml files to render requests
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->setScriptPath(APPLICATION_PATH . 'views/scripts/');
    }

    /**
     */
    public function indexAction() {
        $rotationCount = RotationQuery::create()->count();

        $offset = $this->_getParam('offset', 0);
        $limit = $this->_getParam('limit', $rotationCount);

        $sortColumn = $this->_getParam('sort', PodcastPeer::ID);
        $sortDir = $this->_getParam('sort_dir', Criteria::ASC);

        $result = RotationQuery::create()
            // Don't return the Station podcast - we fetch it separately
            ->setLimit($limit)
            ->setOffset($offset)
            ->orderBy($sortColumn, $sortDir)
            ->find();
        $rotations = $result->toArray();
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('X-TOTAL-COUNT', $rotationCount)
            ->appendBody(json_encode($rotations));
    }

    /**
     */
    public function getAction() {
        // TODO: Implement getAction() method.
    }

    /**
     */
    public function postAction() {
        // TODO: Implement postAction() method.
    }

    /**
     */
    public function putAction() {
        // TODO: Implement putAction() method.
    }

    /**
     */
    public function deleteAction() {
        // TODO: Implement deleteAction() method.
    }
}
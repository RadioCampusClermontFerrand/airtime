<?php

/**
 * Class Rest_RotationController
 */
class Rest_RotationController extends Zend_Rest_Controller {

    /** @var Application_Service_RotationService $_service */
    private $_service;

    /**
     *
     */
    public function init() {
        $this->view->layout()->disableLayout();
        // Remove reliance on .phtml files to render requests
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->setScriptPath(APPLICATION_PATH . 'views/scripts/');
        $this->_service = new Application_Service_RotationService();
    }

    /**
     *
     */
    public function indexAction() {
        $offset = $this->_getParam('offset', 0);
        $limit = $this->_getParam('limit', 0);

        $sortColumn = $this->_getParam('sort', RotationPeer::ID);
        $sortDir = $this->_getParam('sort_dir', Criteria::ASC);

        $query = RotationQuery::create();
        if ($limit > 0) { $query->setLimit($limit); }
        $query->setOffset($offset)
            ->orderBy($sortColumn, $sortDir);
        $result = $query->find();
        $rotations = $result->toArray(null, false, BasePeer::TYPE_FIELDNAME);
        $this->_setResponse(HttpResponseType::OK, json_encode($rotations), array(
            'X-TOTAL-COUNT' => $result->count()
        ));
    }

    /**
     *
     */
    public function getAction() {
        if (!($id = $this->_getParam('id', false))) {
            $this->_setResponse(HttpResponseType::BAD_REQUEST, "No Rotation ID specified");
            return;
        }

        $rotation = $this->_service->retrieve($id);
        $rotation = $rotation->toArray(BasePeer::TYPE_FIELDNAME);

        if (!empty($rotation)) {
            $this->_setResponse(HttpResponseType::OK, json_encode($rotation));
        } else {
            $this->_setResponse(HttpResponseType::NOT_FOUND, "No Rotation with ID $id exists");
        }
    }

    /**
     *
     */
    public function postAction() {
        $requestData = $this->getRequest()->getPost();
        $this->_service->create($requestData);
        $this->_setResponse(HttpResponseType::CREATED, "Rotation added successfully");
    }

    /**
     *
     */
    public function putAction() {
        if (!($id = $this->_getParam('id', false))) {
            $this->_setResponse(HttpResponseType::BAD_REQUEST, "No Rotation ID specified");
            return;
        }

        $requestData = $this->getRequest()->getPost();
        if (empty($requestData)) {
            $requestData = $this->getRequest()->getRawBody();
        }
        $rotation = $this->_service->update($id, $requestData);

        if (!empty($rotation)) {
            $this->_setResponse(HttpResponseType::OK, "Rotation updated successfully");
        } else {
            $this->_setResponse(HttpResponseType::NOT_FOUND, "No Rotation with ID $id exists");
        }
    }

    /**
     *
     */
    public function deleteAction() {
        if (!($id = $this->_getParam('id', false))) {
            $this->_setResponse(HttpResponseType::BAD_REQUEST, "No Rotation ID specified");
            return;
        }

        try {
            if ($this->_service->delete($id)) {
                $this->_setResponse(HttpResponseType::OK, "Rotation deleted successfully");
            } else {
                $this->_setResponse(HttpResponseType::BAD_REQUEST, "No Rotation with ID $id exists");
            }
        } catch (Exception $e) {
            Logging::error($e->getMessage());
            $this->_setResponse(HttpResponseType::SERVER_ERROR, "Rotation could not be deleted");
        }
    }

    /**
     *
     *
     * @param $code
     * @param $message
     * @param array $headers
     *
     * @throws Zend_Controller_Response_Exception
     */
    private function _setResponse($code, $message, $headers = array()) {
        $response = $this->getResponse();
        $response->setHttpResponseCode($code)
            ->appendBody($message);
        foreach ($headers as $header => $val) {
            $response->setHeader($header, $val);
        }
    }
}
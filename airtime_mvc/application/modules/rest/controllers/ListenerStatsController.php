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
        $totalListenerStatCount = ListenerStatsQuery::create()->count();

        // Check if offset and limit were sent with request.
        // Default limit to zero and offset to $totalListenerStatCount
        $offset = $this->_getParam('offset', 0);
        $limit = $this->_getParam('limit', $totalListenerStatCount);

        //Sorting parameters
        $sortColumn = $this->_getParam('sort', ListenerStatsPeer::ID);
        $sortDir = $this->_getParam('sort_dir', Criteria::ASC);

        $query = ListenerStatsQuery::create()
            ->setLimit($limit)
            ->setOffset($offset)
            ->orderBy($sortColumn, $sortDir);

        $queryResult = $query->find();

        $listenerStatArray = array();
        foreach ($queryResult as $listener)
        {
            array_push($listenerStatArray, $listener->toArray(BasePeer::TYPE_FIELDNAME));
        }

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('X-TOTAL-COUNT', $totalListenerStatCount)
            ->appendBody(json_encode($listenerStatArray));
    }

    public function getAction()
    {
        $id = $this->getId();
        if (!$id) {
            return;
        }

        try {
            $this->getResponse()
                ->setHttpResponseCode(200)
                ->appendBody(json_encode(ListenerStats::getListenerStatById($id)));
        } catch (ListenerStatNotFoundException $e) {
            $this->listenerStatNotFoundResponse();
            Logging::error($e->getMessage());
        } catch (Exception $e) {

        }
    }

    public function globalGeolocationAction()
    {
        $start = $this->_getParam('start', null);
        $end = $this->_getParam('end', null);

        if (!$this->validateDateRange($start, $end)) {
            return;
        }

        $this->getResponse()
            ->setHttpResponseCode(201)
            ->appendBody(json_encode(ListenerStats::getGlobalGeoLocationsStats($start, $end)));
    }

    public function countryGeolocationAction()
    {
        $country = $this->getCountry();
        if (!$country) {
            return;
        }

        try {
            $start = $this->_getParam('start', null);
            $end = $this->_getParam('end', null);

            if (!$this->validateDateRange($start, $end)) {
                return;
            }

            $this->getResponse()
                ->setHttpResponseCode(200)
                ->appendBody(json_encode(ListenerStats::getCountryGeoLocationStats($country, $start, $end)));
        } catch (Exception $e) {
            $this->getResponse()
                ->setHttpResponseCode(400)
                ->appendBody("Error: ". $e->getMessage());
        }

    }

    public function aggregateTuningAction()
    {
        $start = $this->_getParam('start', null);
        $end = $this->_getParam('end', null);

        if (!$this->validateDateRange($start, $end)) {
            return;
        }

        $this->getResponse()
            ->setHttpResponseCode(201)
            ->appendBody(json_encode(ListenerStats::getAggregateTuningHours($start, $end)));
    }

    private function validateDateRange($start, $end)
    {
        $timestampRegex = "/^[0-9]{1,4}-[0-9]{1,2}-[0-9]{1,2}( [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2})?$/";
        if (!is_null($start) && !is_null($end) &&
            (!preg_match($timestampRegex, $start) || !preg_match($timestampRegex, $end))) {

            $resp = $this->getResponse();
            $resp->setHttpResponseCode(400);
            $resp->appendBody("Error: Invalid timestamp");
            return false;
        }
        return true;
    }

    private function getCountry()
    {
        if (!$country = $this->_getParam('country',false)) {
            $resp = $this->getResponse();
            $resp->setHttpResponseCode(400);
            $resp->appendBody("ERROR: No country name specified.");
            return false;
        }
        return $country;
    }

    private function getId()
    {
        if (!$id = $this->_getParam('id', false)) {
            $resp = $this->getResponse();
            $resp->setHttpResponseCode(400);
            $resp->appendBody("ERROR: No listener stat ID specified.");
            return false;
        }
        return $id;
    }

    private function listenerStatNotFoundResponse()
    {
        $resp = $this->getResponse();
        $resp->setHttpResponseCode(404);
        $resp->appendBody("ERROR: Listener stat not found.");
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
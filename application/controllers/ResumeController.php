<?php
/**
 * ResumeController.php
 *
 * @name    ResumeController.php
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
/**
 * Resume REST controller
 *
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
class ResumeController extends Zend_Rest_Controller
{
    const RESUME_FILE = '/public/xml/resume.xml';
    
    public function init() {
        //load the resume
        if (file_exists(BASE_PATH . self::RESUME_FILE)) {
            $xmlContent     = file_get_contents(BASE_PATH . self::RESUME_FILE);
            $jsonContent    = Zend_Json::fromXml($xmlContent, true);
            $this->resume   = Zend_Json::decode($jsonContent);

            //identify all resume sections
            foreach ($this->resume['resume'] as $key => $section) {
                $this->sections[$key] = $section['title'];
            }
        } else {
            $this->_helper->json(array('success' => 0, 'message' => 'Resume file not found'));
        }
    }
    
    public function preDispatch() {
        //it is possible to disallow all non-AJAX requests, if it is not desired to serve HTTP requests to the service
        /* if(!$this->_request->isXmlHttpRequest()) {
            $this->_helper->json(array('success' => 0, 'message' => 'HTTP requests are not allowed'));
        } */
        
        //disable layout and view renderer as this controller returns JSON data only
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function __call($actionName, $params) {
        $action = $this->_request->getParam('action');
        //serve short-hand requests for the existing resume sections (e.g. /resume/summary)
        if (isset($this->sections[$action])) {
            $this->_forward('section', null, null, array('id' => $action));
        } else {
            $this->getResponse()->setHttpResponseCode(404);
            $this->_helper->json(array('success' => 0, 'message' => 'service \'' . $action . '\' not found'));
        }
    }

    /**
     * indexAction() returns a list of resume sections
     *
     * @return void
     */
    public function sectionAction() {
        $id = $this->_request->getParam('id');
        if (empty($id)) {
            $this->_helper->json(array('success' => 1, 'message' => 'success', 'data' => $this->sections));
        } else {
            unset($this->resume['resume'][$id]['title']);
            $this->_helper->json(array('success' => 1, 'message' => 'success', 'data' => $this->resume['resume'][$id]));
        }
    }
        
    /**
     * indexAction()
     *
     * @return void
     */
    public function indexAction() {
        $this->_forward('section');
    }
    
    /**
     * getAction() Handles GET and returns a specific resource item
     *
     * @return void
     */
    public function getAction() {
        $this->_forward('section');
    }

    /**
     * getAction() Handles POST requests to create a new resource item
     *
     * @return void
     */
    public function postAction() {
        $this->_helper->json(array('success' => 0, 'message' => 'this is a read only service, POST operation is not allowed'));
    }

    /**
     * getAction() Handles PUT requests to update a specific resource item
     *
     * @return void
     */
 
    public function putAction() {
        $this->_helper->json(array('success' => 0, 'message' => 'this is a read only service, PUT operation is not allowed'));
    }
    /**
     * getAction() Handles DELETE requests to delete a specific item
     *
     * @return void
     */
    public function deleteAction() {
        $this->_helper->json(array('success' => 0, 'message' => 'this is read only service, DELETE operation is not allowed'));
    }
}
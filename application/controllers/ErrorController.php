<?php
/**
 * ErrorController.php
 *
 * @name    ErrorController.php
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
/**
 * Error handler controller
 *
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
class ErrorController extends Zend_Controller_Action
{
    /**
     * Shows the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_forward('error');
    }

    /**
     * handles errors by checking if there is a redirection link in case of non-dispatchable error and
     * redirects if there is a redirection set up or shows 404 page if not, or
     * displays an error page with a message in case of application errors
     *
     * @return void
     */
    public function errorAction()
    {
        $error = $this->_getParam('error_handler');
        if (is_object($error)) {
            switch ($error->type) {
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                    //page not found error
                    $this->_forward('not-found');
                    break;
                default:
                    if (APP_ENVIRONMENT != 'production') {
                        $this->view->error = $error;
                        $this->view->backtrace = debug_backtrace();
                    }

                    break;
            }
        }
    }

    public function notFoundAction() {
        // 404 error -- controller or action not found
        $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
        $this->getResponse()->setHttpResponseCode(404);

        if (APP_ENVIRONMENT != 'production') {
            $this->view->error = $this->_request->getParam('error_handler');
        }
    }
}

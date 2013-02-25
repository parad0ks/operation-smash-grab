<?php
/**
 * Bootstrap.php
 *
 * @name    Bootstrap.php
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
/**
 * Application bootstrapper
 *
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Resource invocation method and setter for view script and helper paths
     *
     * @return void
     */
    protected function _initView()
    {
        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/views/scripts');
        $view->addScriptPath(APPLICATION_PATH . '/views/partials');
        
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setView($view);
        Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/views/layouts'));
    }
    
    /**
     * Sets plugins that will run for each request
     *
     * @return void
     */
    protected function _initPlugins()
    {
        //@NOTE: the order in which plugins are being registered is exteremely important
        //@NOTE: Do not change unless you fully understand possible consequences
        $controller = Zend_Controller_Front::getInstance();
        $controller->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
    }

    /**
     * Resource invocation method for Zend routes
     *
     * @return void
     */
    protected function _initRoutes()
    {
        Zend_Controller_Front::getInstance()->getRouter()
            ->addRoute('about', new Zend_Controller_Router_Route('/about',  array('controller' => 'index', 'action' => 'about')))
            ->addRoute('help', new Zend_Controller_Router_Route('/help',  array('controller' => 'index', 'action' => 'help')))
            //->addRoute('section', new Zend_Controller_Router_Route('/resume/:id', array('controller' => 'resume', 'action' => 'section')))
        ;
    }
}
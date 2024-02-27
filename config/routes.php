<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('DashedRoute');

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
   // $routes->connect('/pages/privacy-policy', ['controller' => 'Pages', 'action' => 'content','privacy-policy']);
   // $routes->connect('/pages/terms', ['controller' => 'Pages', 'action' => 'content','terms']);
//	$routes->connect('/pages/subscription', ['controller' => 'Pages', 'action' => 'subscription']);
//	$routes->connect('/pages/contact', ['controller' => 'Pages', 'action' => 'contact']);
$routes->connect('/pages/price_update', ['controller' => 'Pages', 'action' => 'priceUpdate']);		

$routes->connect('/pages/price_update_night', ['controller' => 'Pages', 'action' => 'priceUpdatnight']);

    $routes->connect('/pages/checkusermembership', ['controller' => 'Pages', 'action' => 'checkusermembership']);
	$routes->connect('/pages/changebtcstatus', ['controller' => 'Pages', 'action' => 'changebtcstatus']);		
	$routes->connect('/pages/comparebtc', ['controller' => 'Pages', 'action' => 'comparebtc']);
	$routes->connect('/getcurrentprice', ['controller' => 'Pages', 'action' => 'getcurrentprice']);
	$routes->connect('/getallcurrentprice', ['controller' => 'Pages', 'action' => 'getallcurrentprice']);
    $routes->connect('/validatetokens', ['controller' => 'Pages', 'action' => 'validatetokens']);
	$routes->connect('/ethvalidatetokens', ['controller' => 'Pages', 'action' => 'ethvalidatetokens']);
	$routes->connect('/checkvalidatetokens', ['controller' => 'Pages', 'action' => 'checkvalidatetokens']);
	$routes->connect('/updatevalidatetokens', ['controller' => 'Pages', 'action' => 'updatevalidatetokens']);
    $routes->connect('/depositethtokens', ['controller' => 'Pages', 'action' => 'depositethtokens']);
	$routes->connect('/wccethdeposit', ['controller' => 'Pages', 'action' => 'wccethdeposit']);
	$routes->connect('/wccvalidateuser', ['controller' => 'Pages', 'action' => 'wccvalidateuser']);
	$routes->connect('/getcurrentpriceusd', ['controller' => 'Pages', 'action' => 'getcurrentpriceusd']);
    $routes->connect('/updatewithrawalstatuseth', ['controller' => 'Pages', 'action' => 'updatewithrawalstatuseth']);
    $routes->connect('/updatewithrawalstatus', ['controller' => 'Pages', 'action' => 'updatewithrawalstatus']);
    $routes->connect('/updatewithrawalstatususd', ['controller' => 'Pages', 'action' => 'updatewithrawalstatususd']);
    $routes->connect('/disablewithrawalstatus', ['controller' => 'Pages', 'action' => 'disablewithrawalstatus']);
	$routes->connect('/pages/ethwithdrawalstatus', ['controller' => 'Pages', 'action' => 'ethwithdrawalstatus']);
	$routes->connect('/pages/coincallback', ['controller' => 'Pages', 'action' => 'coincallback']);
	$routes->connect('/btccallback', ['controller' => 'Pages', 'action' => 'btccallback']);
	$routes->connect('/listing', ['controller' => 'Listing', 'action' => 'index']);
	$routes->connect('/validateuser', ['controller' => 'Pages', 'action' => 'validateuser']);
	$routes->connect('/pages/listing', ['controller' => 'Pages', 'action' => 'listing']);
	$routes->connect('/pages/support', ['controller' => 'Pages', 'action' => 'support']);
	$routes->connect('/support', ['controller' => 'Pages', 'action' => 'support']);
	$routes->connect('/depositram', ['controller' => 'Pages', 'action' => 'depositram']);
	$routes->connect('/depositrealram', ['controller' => 'Pages', 'action' => 'depositrealram']);
	$routes->connect('/depositeth', ['controller' => 'Pages', 'action' => 'depositeth']);
	$routes->connect('/getusercurrentbalance', ['controller' => 'Pages', 'action' => 'getusercurrentbalance']);
	$routes->connect('/withdrawalapi', ['controller' => 'Pages', 'action' => 'withdrawalapi']);
	$routes->connect('/getethaddress', ['controller' => 'Pages', 'action' => 'getethaddress']);
	$routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'home']);
    $routes->connect('/verify/*', ['controller' => 'Users', 'action' => 'verify']);

    $routes->connect('/users/verify/*', ['controller' => 'Users', 'action' => 'verify']);
    $routes->connect('/users/register', ['controller' => 'Users', 'action' => 'front-register']);
    $routes->connect('/users/login', ['controller' => 'Users', 'action' => 'front-login']);
    $routes->connect('/users/logout', ['controller' => 'Users', 'action' => 'logout']);
    $routes->connect('/users/forgot-password', ['controller' => 'Users', 'action' => 'forgot-password']);
    $routes->connect('/users/contact', ['controller' => 'Users', 'action' => 'contact']);
	$routes->connect('/users/user-cron', ['controller' => 'Users', 'action' => 'userCron']);
	$routes->connect('/returndata', ['controller' => 'Pages', 'action' => 'returndata']);
    //$routes->connect('/*', ['controller' => 'Pages', 'action' => 'home']);
	$routes->connect('/', ['controller' => 'Pages', 'action' => 'home']);
	$routes->connect('/pages/hometest', ['controller' => 'Pages', 'action' => 'hometest']);
	$routes->connect('/pages/home2', ['controller' => 'Pages', 'action' => 'home2']);
	$routes->connect('/pages/home3', ['controller' => 'Pages', 'action' => 'home3']);
	
	
	//-- 2020-10-19  jaedeuk test 
	$routes->connect('/a1', ['controller' => 'A1', 'action' => 'home']);
    $routes->connect('/a1/view1', ['controller' => 'A1', 'action' => 'view1']);
	 $routes->connect('/a1/write1', ['controller' => 'A1', 'action' => 'write1']);
	$routes->connect('/a1/edit1', ['controller' => 'A1', 'action' => 'edit1']);
    //$routes->connect('/*', ['prefix'=>'front','controller' => 'Users', 'action' => 'register']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
   // $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
 
 
 // Or with prefix()
/*Router::prefix('Admin', ['_namePrefix' => 'admin:'], function ($routes) {
    // Connect routes.
});*/
Router::prefix('tech', function ($routes) {
    $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
	$routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
	$routes->connect('/dashboard', ['controller' => 'Pages', 'action' => 'dashboard']);
	$routes->connect('/403', ['controller' => 'Pages', 'action' => 'forbidden']);
	$routes->fallbacks('DashedRoute');
});


Router::prefix('front2', function ($routes) {
    $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
	$routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
	$routes->connect('/impersonate/*', ['controller' => 'Users', 'action' => 'impersonate']);
	$routes->connect('/register', ['controller' => 'Users', 'action' => 'register']);
	$routes->connect('/register/*', ['controller' => 'Users', 'action' => 'register']);
	$routes->connect('/forgot', ['controller' => 'Users', 'action' => 'forgetPassword']);
	$routes->connect('/successregister', ['controller' => 'Users', 'action' => 'successregister']);
	$routes->connect('/dashboard', ['controller' => 'Pages', 'action' => 'dashboard']);
	$routes->connect('/wallet', ['controller' => 'Wallet', 'action' => 'index']);
	$routes->connect('/customer', ['controller' => 'Customer', 'action' => 'board']);
	//$routes->connect('/customer/*', ['controller' => 'Customer', 'action' => 'board']);
	$routes->connect('/customer/notice', ['controller' => 'Customer', 'action' => 'board', 'notice']);
	//$routes->connect('/customer/faq', ['controller' => 'Customer', 'action' => 'board', 'faq']);
	$routes->connect('/customer/joininfo', ['controller' => 'Customer', 'action' => 'board', 'joininfo']);
	//$routes->connect('/customer/authinfo', ['controller' => 'Customer', 'action' => 'board', 'authinfo']);
	$routes->connect('/users/checkphoneunique', ['controller' => 'Users', 'action' => 'checkPhoneUnique']);


	$routes->connect('/customer/qna', ['controller' => 'Customer', 'action' => 'board', 'qna']);
	$routes->connect('/403', ['controller' => 'Pages', 'action' => 'forbidden']);
	$routes->fallbacks('DashedRoute');
});

Router::prefix('api', function ($routes) {
	$routes->connect('/3/info', ['controller' => 'Apis', 'action' => 'getinfo']);
	$routes->connect('/3/ticker/*', ['controller' => 'Apis', 'action' => 'getticker']);
	$routes->connect('/3/depth/*', ['controller' => 'Apis', 'action' => 'getdepth']);
	$routes->connect('/3/trades/*', ['controller' => 'Apis', 'action' => 'gettrades']);
    $routes->extensions(['json']);
	$routes->fallbacks('DashedRoute');
});

//test! bt ojt
Router::prefix('TESTOJT', function ($routes) {
	$routes->connect('/', ['controller' => 'TESTOJT', 'action' => 'index']);
	$routes->fallbacks('DashedRoute');
});


Plugin::routes();

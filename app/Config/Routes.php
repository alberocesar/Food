<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::novo');
$routes->get('logout', 'Login::logout');
$routes->get('registrar', '');
$routes->get('admin/categorias', 'Admin\categorias::index');
$routes->get('admin/produtos/editar/(:num)', 'Admin\Produtos::editar/$1');
$routes->get('admin/produtos/cadastrarespecificacoes/(:num)', 'Admin\Produtos::cadastrarEspecificacoes/$1');

$routes->group('admin', function ($routes) {
    $routes->add('formas', 'Admin\FormasPagamentos::index');
    $routes->add('formas/show/(:num)', 'Admin\FormasPagamentos::show/$1');
    $routes->add('formas/criar', 'Admin\FormasPagamentos::criar/$1');
    $routes->add('formas/editar/(:num)', 'Admin\FormasPagamentos::editar/$1');
    $routes->add('formas/desfazerexclusao/(:num)', 'Admin\FormasPagamentos::desfazerexclusao/$1');
    $routes->add('formaspagamento/procurar', 'Admin\FormasPagamentos::procurar');

    /*  Para o POST*/
    $routes->post('formas/atualizar/(:num)', 'Admin\FormasPagamentos::atualizar/$1');
    $routes->post('formas/cadastrar', 'Admin\FormasPagamentos::cadastrar');
    
    $routes->match(['get', 'post'], 'formas/excluir/(:num)', 'Admin\FormasPagamentos::excluir/$1');


    $routes->match(['get', 'post'], 'expedientes', 'Admin\Expedientes::expedientes');

    
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

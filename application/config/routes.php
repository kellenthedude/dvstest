<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['404_override'] = '';

$route['experiment/create'] = 'experiment/create';
$route['experiment/delete/(:any)'] = 'experiment/delete/$1';
$route['experiment/set_hide/(:any)/(:any)'] = 'experiment/set_hide/$1/$2';
$route['experiment/(:any)'] = 'experiment/view/$1';
$route['experiment'] = 'experiment';

$route['product/get_labels_by_target/(:any)'] = 'product/get_labels_by_target/$1';

$route['spillover/get'] = 'spillover/get_spillover';
$route['spillover/get/(:any)'] = 'spillover/get_spillover/$1';

$route['node/set/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'node/set/$1/$2/$3/$4/$5/$6/$7/$8';
$route['node/set/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'node/set/$1/$2/$3/$4/$5/$6/$7/$8/$9';
$route['node/get_exp_nodes/(:any)/(:any)'] = 'node/get_exp_nodes/$1/$2';



/* End of file routes.php */
/* Location: ./application/config/routes.php */
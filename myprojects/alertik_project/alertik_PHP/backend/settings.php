<?phpini_set("display_errors", 1);ini_set("error_reporting", 1);error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));$theme='theme/backend';include '../lib/auth-browser.php';$users=[	'shahin'=>['pass'=>'sssss','id'=>1,'name'=>'Shahin'],	'maryam'=>['pass'=>'8406773','id'=>2,'name'=>'Maryam'],	'masi'=>['pass'=>'Masi00MasS','id'=>3,'name'=>'Masoumeh'],	'johann'=>['pass'=>'john4321john','id'=>4,'name'=>'johann'],	'soudabeh'=>['pass'=>'S0udabeh654','id'=>5,'name'=>'Soudabeh','permission'=>'basic'],	'mahnaz'=>['pass'=>'M@hnaz987','id'=>6,'name'=>'Mahnaz','permission'=>'basic'],	'sepid'=>['pass'=>'Sep!d321','id'=>7,'name'=>'Sepide','permission'=>'basic'],	'maede'=>['pass'=>'chubby','id'=>8,'name'=>'maede','permission'=>'basic'],	'soli'=>['pass'=>'chubby','id'=>9,'name'=>'soli','permission'=>'basic'],	'abi'=>['pass'=>'43218765','id'=>10,'name'=>'ABI','permission'=>'basic'],];if (PHP_SAPI != 'cli') $current_user=user_authenticate();include '../conf.php';include '../lib/mysql.php';include '../lib/web.php';include '../lib/lock.php';include '../lib/table.php';include '../'.CONF_TYPE.'/backend/settings.php';include '../'.CONF_TYPE.'/backend/theme_libs.php';
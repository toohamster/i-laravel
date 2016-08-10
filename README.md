# i-laravel
Laravel 简易化

在你的 **config/app.php** 中增加如下配置:
```
'providers' => [
			'ILaravel\ViewServiceProvider',
		],

'aliases' => [
	'Controller' => 'ILaravel\Controller',
	'View' => 'ILaravel\Facades\View',
],
```
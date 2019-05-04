<?php

return [
	'domain' => 'testapp.lc',
	'encoding' => 'utf-8',
	'timezone' => 'Europe/Moscow',
	'title' => 'Блог',
	'salt' => [
        'common' => 'Ywu202Jsj2o)01jHsaj2l!skqlqo@Jb3j22028eGBsjbjikaJA',
        'csrf' => 'KL1-2#21kk01920Jay1u203716Y!!200Jbj#2kAK201@91018HAo4%7628_102Aj'
    ],
	'hosts' => [
		'default' => '//testapp.lc',
		'static' => '//static.testapp.lc'
	],
	'db' => [
		'host' => '127.0.0.1',
		'user' => 'root',
		'password' => '123456',
		'name' => 'demo_blog',
		'encoding' => 'utf8'
	],
	'paths' => [
		'upload' => ROOT_DIR . 'init/static/upload/'
	]
];
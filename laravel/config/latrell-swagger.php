<?php
return array(
	'enable' => config('app.debug'),

	'prefix' => 'api-docs',

	'paths' => base_path('app'),
	'output' => storage_path('swagger/docs'),
	'exclude' => null,
	'default-base-path' => '/api/v1',
	'default-api-version' => null,
	'default-swagger-version' => null,
	'api-doc-template' => null,
	'suffix' => '.json',

	'title' => 'Swagger UI'
);
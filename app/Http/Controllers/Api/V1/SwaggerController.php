<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class SwaggerController extends Controller
{
    /**
     * @return string
     */
    public function yamlConvert(): string
    {
        $yamlFilePath = resource_path('swagger/openapi.yaml');
        $yaml = Yaml::parse(file_get_contents($yamlFilePath));
        $json = json_encode($yaml, JSON_PRETTY_PRINT);
        file_put_contents(storage_path('api-docs/api-docs.json'), $json);
        return 'ok';
    }
}

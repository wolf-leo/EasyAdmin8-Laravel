<?php

namespace App\Http\Services;

use App\Http\Services\auth\Node;
use Doctrine\Common\Annotations\AnnotationException;

class NodeService
{

    /**
     * 获取节点服务
     * @return array
     */
    public function getNodeList(): array
    {
        $basePath      = app_path() . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'admin';
        $baseNamespace = 'App\Http\Controllers\admin';
        try {
            $nodeList = (new Node($basePath, $baseNamespace))->getNodeList();
        } catch (AnnotationException | \ReflectionException $e) {
            $nodeList = [];
        }
        return $nodeList;
    }
}

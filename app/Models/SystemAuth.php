<?php

namespace App\Models;

class SystemAuth extends BaseModel
{
    /**
     * @param $authId
     * @return array
     */
    public function getAuthorizeNodeListByAdminId($authId): array
    {
        $checkNodeList = SystemAuthNode::where('auth_id', $authId)->pluck('node_id')->toArray();
        $systemNode    = new SystemNode();
        $nodeList      = $systemNode->where('is_auth', 1)->select(explode(',', 'id,node,title,type,is_auth'))->get()->toArray();
        $newNodeList   = [];
        foreach ($nodeList as $vo) {
            if ($vo['type'] == 1) {
                $vo            = array_merge($vo, ['field' => 'node', 'spread' => true]);
                $vo['checked'] = false;
                $vo['title']   = "{$vo['title']}【{$vo['node']}】";
                $children      = [];
                foreach ($nodeList as $v) {
                    if ($v['type'] == 2 && str_contains($v['node'], $vo['node'] . '/')) {

                        $v            = array_merge($v, ['field' => 'node', 'spread' => true]);
                        $v['checked'] = in_array($v['id'], $checkNodeList);
                        $v['title']   = "{$v['title']}【{$v['node']}】";
                        $children[]   = $v;
                    }
                }
                !empty($children) && $vo['children'] = $children;
                $newNodeList[] = $vo;
            }
        }
        return $newNodeList;
    }
}

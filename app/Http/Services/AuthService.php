<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

/**
 * 权限验证服务
 * Class AuthService
 * @package app\common\service
 */
class AuthService
{

    /**
     * 用户ID
     * @var int
     */
    protected int $adminId = 0;

    /**
     * 默认配置
     * @var array
     */
    protected array $config = [
        'auth_on'          => true,              // 权限开关
        'system_admin'     => 'system_admin',    // 用户表
        'system_auth'      => 'system_auth',     // 权限表
        'system_node'      => 'system_node',     // 节点表
        'system_auth_node' => 'system_auth_node',// 权限-节点表
    ];

    /**
     * 管理员信息
     */
    protected array $adminInfo;

    /**
     * 所有节点信息
     * @var array
     */
    protected array $nodeList;

    /**
     * 管理员所有授权节点
     * @var array
     */
    protected array $adminNode;

    /***
     * 构造方法
     * AuthService constructor.
     * @param null $adminId
     */
    public function __construct($adminId = null)
    {
        $this->adminId   = $adminId;
        $this->adminInfo = $this->getAdminInfo();
        $this->nodeList  = $this->getNodeList();
        $this->adminNode = $this->getAdminNode();
        return $this;
    }

    /**
     * 检测检测权限
     * @param null $node
     * @return bool
     */
    public function checkNode($node = null): bool
    {
        // 判断是否为超级管理员
        if ($this->adminId == SUPER_ADMIN_ID) {
            return true;
        }
        // 判断权限验证开关
        if ($this->config['auth_on'] == false) {
            return true;
        }
        // 判断是否需要获取当前节点
        if (empty($node)) {
            $node = $this->getCurrentNode();
        } else {
            $node = $this->parseNodeStr($node);
        }
        // 判断是否加入节点控制，优先获取缓存信息
        if (!isset($this->nodeList[$node])) {
            return false;
        }
        $nodeInfo = $this->nodeList[$node];
        if ($nodeInfo['is_auth'] == 0) {
            return true;
        }
        // 用户验证，优先获取缓存信息
        if (empty($this->adminInfo) || $this->adminInfo['status'] != 1 || empty($this->adminInfo['auth_ids'])) {
            return false;
        }
        // 判断该节点是否允许访问
        if (in_array($node, $this->adminNode)) {
            return true;
        }
        return false;
    }

    /**
     * 获取当前节点
     * @return string
     */
    public function getCurrentNode()
    {
        return $this->parseNodeStr(request()->controller() . '/' . request()->action());
    }

    /**
     * 获取当前管理员所有节点
     * @return array
     */
    public function getAdminNode(): array
    {
        $nodeList  = [];
        $adminInfo = DB::table($this->config['system_admin'])
            ->where([
                        'id'     => $this->adminId,
                        'status' => 1,
                    ])->first();
        $adminInfo = get_object_vars($adminInfo);
        if (!empty($adminInfo) && !empty($adminInfo['auth_ids'])) {
            $buildAuthSql     = DB::table($this->config['system_auth'])
                ->distinct(true)
                ->whereIn('id', $adminInfo['auth_ids'])
                ->select('id')
                ->toSql();
            $buildAuthNodeSql = DB::table($this->config['system_auth_node'])
                ->distinct(true)
                ->where("auth_id IN {$buildAuthSql}")
                ->select('node_id')
                ->toSql();
            $nodeList         = DB::table($this->config['system_node'])
                ->distinct(true)
                ->where("id IN {$buildAuthNodeSql}")->get()
                ->keyBy('node')->toArray();
        }
        return $nodeList;
    }

    public function getNodeList(): array
    {
        return DB::table($this->config['system_node'])->select('id', 'node', 'title', 'type', 'is_auth')->get()->keyBy('node')->toArray();
    }

    public function getAdminInfo()
    {
        $result = DB::table($this->config['system_admin'])
            ->where('id', $this->adminId)
            ->first();
        return get_object_vars($result);
    }

    /**
     * 驼峰转下划线规则
     * @param string $node
     * @return string
     */
    public function parseNodeStr($node): string
    {
        $array = explode('/', $node);
        foreach ($array as $key => $val) {
            if ($key == 0) {
                $val = explode('.', $val);
                foreach ($val as &$vo) {
                    $vo = \think\helper\Str::snake(lcfirst($vo));
                }
                $val         = implode('.', $val);
                $array[$key] = $val;
            }
        }
        $node = implode('/', $array);
        return $node;
    }

}

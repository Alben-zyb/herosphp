<?php
/**
 * 控制器抽象基类, 所有的控制器类都必须继承此类。
 * 每个操作对应一个方法。
 * ---------------------------------------------------------------------
 * @author yangjian<yangjian102621@gmail.com>
 * @since 2013-05 v1.0.0
 */

namespace herosphp\core;


abstract class Controller extends Template {


    /**
     * 视图模板名称
     * @var string
     */
    private $view = null;

	/**
     * 控制器初始化方法，每次请求必须先调用的方法，action子类可以重写这个方法进行页面的初始化
	 */
	public function C_start() {}

    /**
     * 我的方法
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $request = WebApplication::getInstance()->getHttpRequest();

        /*//获取当前登陆管理员
        $managerService = Loader::service(ManagerService::class);
        $loginUser = $managerService->getLoginManager();
        //        if (!$loginUser) {
        //            location("/admin/login/index");
        //        }
        $this->loginUser = ModelTransformUtils::map2Model(Manager::class, $loginUser);
        $this->assign('loginUser', $this->loginUser);

        if ($this->serviceClass != null) {
            $this->service = Loader::service($this->serviceClass);
        }*/

        $module = $request->getModule();
        $action = $request->getAction();
        $this->assign("index_url", "/{$module}/{$action}/index");
        $this->assign('add_url',"/{$module}/{$action}/add");
        $this->assign('edit_url',"/{$module}/{$action}/edit");
        $this->assign("insert_url", "/{$module}/{$action}/insert");
        $this->assign("update_url", "/{$module}/{$action}/update");
        $this->assign('delete_url',"/{$module}/{$action}/delete");
        $this->assign('deletes_url',"/{$module}/{$action}/deletes");

    }

    /**
     * 设置视图模板
     * @param       string      $view      模板名称
     */
    public function setView( $view ) {
        $this->view = $view;
    }

    /**
     * 获取视图
     * @return string
     */
    public function getView() {
        return $this->view;
    }

    //析够函数
    public function __destruct()
    {
        $liseners = WebApplication::getInstance()->getListeners();
        //调用响应发送后生命周期监听器
        if ( !empty($liseners) ) {
            foreach ( $liseners as $listener ) {
                $listener->actionInvokeFinally(WebApplication::getInstance()->getHttpRequest(), $this);
            }
        }
    }

}
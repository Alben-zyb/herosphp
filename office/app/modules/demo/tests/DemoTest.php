<?php
/**
 * 单元测试类
 * @author yangjian<yangjian102621@gmail.com>
 * @date 2017-06-20
 */

namespace app\demo\tests;
use app\admin\service\AdminService;
use herosphp\core\Loader;
use herosphp\files\FileUtils;
use herosphp\string\StringUtils;
use herosphp\utils\JsonResult;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class DemoTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    /**
     * @test
     */
    public function sqlBuilder() {

        $model = Loader::model(UserDao::class);
        $model->where("name", "aaaa")->where("age", ">", 100)
            ->where(function($model) use ($model) {
                $model->where("id", 10)->whereOr('name', "xiaoming");
            })
            ->whereOr(function($model) use ($model) {
                $model->where("ischeck", 0)->where("addtime", ">", "2017-06-20 13:30:30")->whereOr('ddd', 'xxx');
            });
        print_r($model->getSqlBuilder()->buildQueryString());
    }

    /**
     * @test
     */
    public function findOne() {
        $model = Loader::service(AdminService::class);
        print_r($model->findOne());
    }

    /**
     * 查找某个字段是否包含某个字符串
     * @test
     */
    public function contain() {
        $model = Loader::service(AdminService::class);
        $users = $model->fields('id, username')->where('username', 'CONTAIN', 'admin')->find();
        var_dump($users);
    }

    /**
     * 使用七牛进行文件上传
     * @test
     */
    public function upload() {
        // 初始化 UploadManager 对象并进行文件的上传。
        $upConfigs = getConfig('qiniu_upload_configs');
        $uploadMgr = new UploadManager();
        // 构建鉴权对象
        $auth = new Auth($upConfigs['ACCESS_KEY'], $upConfigs['SECRET_KEY']);
        // 生成上传 Token
        $token = $auth->uploadToken($upConfigs['BUCKET']);

        $filePath = "/home/yangjian/ipfs/data/data-1MB.zip";

        // 上传到七牛后保存的文件名
        $key = basename($filePath);

        $start = timer();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            tprintError("文件上传失败：{$err->message()}");
        } else {
            $cost = timer() - $start;
            tprintOk("文件上传成功：".$upConfigs['BUCKET_DOMAIN'] . $ret['key'].", cost: {$cost} secs");
        }
    }

    /**
     * 使用 IPFS 进行文件上传
     * @test
     */
    public function uploadIPFS() {

        $start = timer();
        $filePath = "/home/yangjian/ipfs/data/data-1MB.zip";
        $ipfs = new IPFS("127.0.0.1", "8080", "5001");
        $hash = $ipfs->add($filePath);
        $cost = timer() - $start;
        tprintOk("文件上传成功：".$hash.", cost: {$cost} secs");
    }
}

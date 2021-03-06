<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-22
 * Time: 22:36
 */

namespace app\forms\common\file;


use app\core\ApiCode;
use app\helpers\OptionHelper;
use app\helpers\ResponseHelper;
use app\models\BaseModel;
use app\models\File;
use Exception;
use OSS\Core\OssException;
use OSS\OssClient;
use Qcloud\Cos\Client;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\web\UploadedFile;

/**
 * Class FileUploadForm
 * @package app\forms\mall\file
 * @Notes
 */
class FileUploadForm extends BaseModel
{
    protected $docExt = ['txt', 'docx', 'doc', 'pptx', 'ppt', 'xls', 'csv', 'pdf', 'md', 'xlsx', 'pem', 'txt'];
    protected $imageExt = ['jpg', 'bmp', 'png', 'gif', 'jpeg', 'webp',];
    protected $videoExt = ['mp4', 'ogg',];
    public $type;
    public $savePath;
    public $baseWebPath;
    public $baseWebUrl;
    public $saveName;
    public $saveFile;
    public $url;
    public $saveThumbFolder;
    public $thumb_url;
    public $group_id;
    public $is_site;
    public $location = "local";


    public function rules()
    {
        return [[['group_id', 'is_site'], 'integer']]; // TODO: Change the autogenerated stub
    }

    /**
     * @var UploadedFile $file
     */
    public $file;

    public function validateFileExt()
    {
        $allSupportExt = array_merge($this->docExt, $this->imageExt, $this->videoExt);
        if (!in_array($this->file->getExtension(), $allSupportExt)) {

            return false;
        }
        return true;
    }

    public function saveFile()
    {
        $this->file = UploadedFile::getInstanceByName('file');
        if (!$this->validateFileExt()) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '文件格式不支持');
        }
        if (empty($this->file)) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '请上传文件');
        }
        $dateFolder = date('Ymd');
        if (in_array($this->file->getExtension(), $this->imageExt)) {
            $this->type = 'image';
            $this->savePath = '/uploads/images/original/' . $dateFolder . '/';
        }
        if (in_array($this->file->getExtension(), $this->videoExt)) {
            $this->type = 'video';
            $this->savePath = '/uploads/video/original/' . $dateFolder . '/';
        }
        if (in_array($this->file->getExtension(), $this->docExt)) {
            $this->type = 'doc';
            $this->savePath = '/uploads/doc/original/' . $dateFolder . '/';
        }
        $this->baseWebPath = \Yii::$app->basePath . '/web';
        $this->baseWebUrl = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl;
        $this->saveName = md5_file($this->file->tempName) . '.' . $this->file->getExtension();
        $this->saveFile = $this->baseWebPath . $this->savePath . $this->saveName;
        $res = null;
        if (\Yii::$app->mall) {
            $setting = OptionHelper::get('mall_setting', \Yii::$app->mall->id);
            \Yii::warning($setting);
            if ($setting && isset($setting['file_location'])) {
                if ($setting['file_location'] == 'local' || $setting['file_location'] == "" || !$setting['file_location']) {
                    $this->location = 'local';
                    $res = $this->saveToLocal();
                }
                if ($setting['file_location'] == 'cos') {
                    $cos = OptionHelper::get('cos', \Yii::$app->mall->id);
                    if ($cos) {
                        $this->location = 'cos';
                        $res = $this->saveToCos($cos);
                    }
                }
                if ($setting['file_location'] == 'oss') {
                    $oss = OptionHelper::get('oss', \Yii::$app->mall->id);
                    if ($oss) {
                        $this->location = 'oss';
                        $res = $this->saveToOss($oss);
                    }
                }
                if ($setting['file_location'] == 'qiniu') {
                    $qiniu = OptionHelper::get('qiniu', \Yii::$app->mall->id);
                    if ($qiniu) {
                        $this->location = 'qiniu';
                        $res = $this->saveToQiniu($qiniu);
                    }
                }
            } else {
                $res = $this->saveToLocal();
            }
        } else {
            $res = $this->saveToLocal();
        }
        \Yii::warning($res);
        if (!$res) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '保存失败');
        }
        $file = new File();
        if (\Yii::$app->mall) {
            $file->mall_id = \Yii::$app->mall->id;
        } else {
            $file->mall_id = 0;
        }
        $file->type = $this->type;
        $file->thumb_url = $this->thumb_url;
        $file->url = $this->url;
        $file->name = $this->file->name;
        $file->path = $this->saveFile;
        $file->location = $this->location;
        if ($this->group_id) {
            $file->group_id = $this->group_id;
        }
        if ($this->is_site) {
            $file->is_site = $this->is_site;
        }
        if (!\Yii::$app->admin->isGuest) {
            $file->admin_id = \Yii::$app->admin->identity->id;
        }
        if (!$file->save()) {
            unlink($file->path);
            return ResponseHelper::send(ApiCode::CODE_FAIL, $file->getFirstErrors());
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', $res);
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-09-23
     * @Time: 14:13
     * @Note:本地
     * @return array
     * @throws \Exception
     */
    public function saveToLocal()
    {

        $this->url = $this->baseWebUrl . "/" . $this->savePath . $this->saveName;
        if (!is_dir($this->baseWebPath . $this->savePath)) {
            if (!make_dir($this->baseWebPath . $this->savePath)) {
                throw new \Exception('上传失败，创建文件夹失败`'
                    . $this->baseWebPath
                    . $this->savePath
                    . '`，请检查目录写入权限。');
            }
        }
        if (!$this->file->saveAs($this->saveFile)) {
            if (!copy($this->file->tempName, $this->saveFile)) {
                throw new \Exception('文件保存失败，请检查目录写入权限。');
            }
        }
        $this->thumb_url = $this->url;
        return ['url' => $this->url, 'extension' => $this->file->getExtension(), 'size' => $this->file->size, 'thumb_url' => $this->thumb_url, 'type' => $this->type, 'name' => $this->file->name];
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-15
     * @Time: 10:37
     * @Note:
     * @param array $oss
     * @return array|bool
     */
    public function saveToOss($oss)
    {
        if (!$oss) {
            return false;
        }
        $accessKey = $oss['access_key'];
        $accessSecret = $oss['access_secret'];
        $bucketName = $oss['bucket'];
        $endPoint = $oss['end_point'];
        $res = null;
        try {
            $ossClient = new OssClient($accessKey, $accessSecret, $endPoint, false);
            $object = trim($this->savePath . $this->saveName, '/');
            $this->saveFile = $object;


            $res = $ossClient->uploadFile($bucketName, $object, $this->file->tempName);
        } catch (OssException $e) {

        }
        if ($res) {
            $this->url = $res['info']['url'];
            $this->thumb_url = $res['info']['url'];
            return ['url' => $this->url, 'extension' => $this->file->getExtension(), 'size' => $this->file->size, 'thumb_url' => $this->url, 'type' => $this->type, 'name' => $this->file->name];
        }
        return false;
    }

    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-15
     * @Time: 10:36
     * @Note:
     * @param array $cos
     * @return array|bool
     */
    public function saveToCos($cos)
    {
        $region = $cos['region'];
        $bucket = $cos['bucket'];
        $secretKey = $cos['secret_key'];
        $secretId = $cos['secret_id'];
        $client = new Client([
            'region' => $region,
            'credentials' => [
                'secretKey' => $secretKey,
                'secretId' => $secretId
            ]
        ]);
        $key = trim($this->savePath . $this->saveName, '/');
        /** @var \GuzzleHttp\Command\Result $result */
        //
        try {
            $this->saveFile = $key;

            $result = $client->upload($bucket, $key, fopen($this->file->tempName, 'rb'));
            //腾讯云对象存储报错
            if ($result) {
                $this->url = "http://" . $result['Location'];
                $this->thumb_url = $this->url;
                return ['url' => $this->url, 'extension' => $this->file->getExtension(), 'size' => $this->file->size, 'thumb_url' => $this->url, 'type' => $this->type, 'name' => $this->file->name];
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }


    /**
     * @Author: 动力宇宙 ganxiaohao
     * @Date: 2020-11-15
     * @Time: 10:37
     * @Note:
     * @param  array $qiniu
     * @return array|bool
     * @throws Exception
     */
    public function saveToQiniu($qiniu)
    {
        $accessKey = $qiniu['access_key'];
        $accessSecret = $qiniu['access_secret'];
        $bucket = $qiniu['bucket'];
        $domain = $qiniu['domain'];
        $uploadManager = new UploadManager();
        $auth = new Auth($accessKey, $accessSecret);
        $token = $auth->uploadToken($bucket);
        $key = trim($this->savePath . $this->saveName, '/');
        $this->saveFile = $key;
        list($result, $error) = $uploadManager->putFile($token, $key, $this->file->tempName);
        if ($result) {
            $this->url = $domain . $result['key'];
            $this->thumb_url = $domain . $result['key'];
            return ['url' => $this->url, 'extension' => $this->file->getExtension(), 'size' => $this->file->size, 'thumb_url' => $this->url, 'type' => $this->type, 'name' => $this->file->name];
        } else {
            return false;
        }
    }

    public function saveUserFile()
    {
        $this->file = UploadedFile::getInstanceByName('file');
        if (!$this->validateFileExt()) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '文件格式不支持');
        }
        if (empty($this->file)) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '请上传文件');
        }
        $dateFolder = date('Ymd');
        if (in_array($this->file->getExtension(), $this->imageExt)) {
            $this->type = 'image';
            $this->savePath = '/uploads/images/original/' . $dateFolder . '/';
        }
        if (in_array($this->file->getExtension(), $this->videoExt)) {
            $this->type = 'video';
            $this->savePath = '/uploads/video/original/' . $dateFolder . '/';
        }
        if (in_array($this->file->getExtension(), $this->docExt)) {
            $this->type = 'doc';
            $this->savePath = '/uploads/doc/original/' . $dateFolder . '/';
        }
        $this->baseWebPath = \Yii::$app->basePath . '/web';
        $this->baseWebUrl = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl;
        $this->saveName = md5_file($this->file->tempName) . '.' . $this->file->getExtension();
        $this->saveFile = $this->baseWebPath . $this->savePath . $this->saveName;
        $res = null;
        if (\Yii::$app->mall) {
            $setting = OptionHelper::get('mall_setting', \Yii::$app->mall->id);
            if ($setting && isset($setting['file_location'])) {
                if ($setting['file_location'] == 'local'||$setting['file_location'] =='') {
                    $this->location = "local";
                    $res = $this->saveToLocal();
                }
                if ($setting['file_location'] == 'cos') {
                    $cos = OptionHelper::get('cos', \Yii::$app->mall->id);
                    if ($cos) {
                        $this->location = "cos";
                        $res = $this->saveToCos($cos);
                    }
                }
                if ($setting['file_location'] == 'oss') {
                    $oss = OptionHelper::get('oss', \Yii::$app->mall->id);
                    if ($oss) {
                        $this->location = "oss";
                        $res = $this->saveToOss($oss);
                    }
                }
                if ($setting['file_location'] == 'qiniu') {
                    $qiniu = OptionHelper::get('qiniu', \Yii::$app->mall->id);
                    if ($qiniu) {
                        $this->location = "qiniu";
                        $res = $this->saveToQiniu($qiniu);
                    }
                }
            } else {
                $res = $this->saveToLocal();
            }
        } else {
            $res = $this->saveToLocal();
        }
        if (!$res) {
            return $this->apiResponse(ApiCode::CODE_FAIL, '保存失败');
        }
        $file = new File();
        $file->mall_id = 0;
        $file->type = $this->type;
        $file->thumb_url = $this->thumb_url;
        $file->location = $this->location;
        $file->path = $this->saveFile;
        $file->url = $this->url;
        $file->name = $this->file->name;
        if (\Yii::$app->user->identity->id) {
            $file->user_id = \Yii::$app->user->identity->id;
        }
        if (!$file->save()) {
            unlink($file->path);
            return ResponseHelper::send(ApiCode::CODE_FAIL, $file->getFirstErrors());
        }
        return $this->apiResponse(ApiCode::CODE_SUCCESS, '', $res);
    }

}

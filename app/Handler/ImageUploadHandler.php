<?php


namespace App\Handler;


use OSS\OssClient;
use OSS\Core\OssException;
use App\Models\Upload;

class ImageUploadHandler
{
    protected $allowed_ext = ['png', 'jpg', 'jpeg'];
    /**
     * @var OssClient
     */
    private $ossClient;

    public function __construct()
    {
        $this->ossClient = new OssClient(config('aliyun.access_key_id'),
            config('aliyun.access_key_secret'), config('aliyun.endpoint'));
    }

    public function store($file, $style = 'article')
    {
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }
        $name = uniqid('', TRUE) . '.' . $file->getClientOriginalExtension();
        $oss_path = 'img/' . date('Y/m') . '/' . $name;

        /* 拼接访问地址 */
        if ($style) {
            $url = config('aliyun.domain') . $oss_path . '!' . $style;
        } else {
            $url = config('aliyun.domain') . $oss_path;
        }

        $id = $this->log($oss_path);

        try {
            $this->ossClient->multiuploadFile(config('aliyun.bucket'), $oss_path, $file->getRealPath());
            return array('status' => true, 'url' => $url, 'id' => $id);
        } catch (OssException $e) {
            return array('status' => false, 'error' => $e->getMessage());
        }
    }

    static private function log($oss_path)
    {
        $log = Upload::create(['oss_path' => $oss_path]);
        return $log->id;
    }

    public function delete($log_id)
    {
        $file = Upload::find($log_id);
        self::delete_log($log_id);
        if (empty($file->oss_path)) {
            return;
        }
        return $this->ossClient->deleteObject(config('aliyun.bucket'), $file->oss_path);
    }

    static private function delete_log($log_id)
    {
        return Upload::destroy($log_id);
    }

}

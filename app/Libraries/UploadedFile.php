<?php

namespace App\Libraries;

use Request;
use Storage;
use Log;

class UploadedFile
{
    // 上传文件实例集合
    private $file;

    // 存储的磁盘
    private $disk = 'qiniu';

    // 限制上传文件类型
    private $allowExtension = [
        'jpg', 'jpeg', 'pjpeg', 'gif', 'png', 'bmp', 'webp', 'zip', 'rar', 'pdf', 'txt', 'csv', 'doc', 'xls', 'xlsx'
    ];

    // 上传文件大小(最大5M)
    private $maxSize = 5242880;

    // 错误信息
    private $error;

    // 资源类型
    protected $mimeType = [
        'pdf' => 'application/pdf',
        'text' => 'text/plain',
        'png' => 'image/png',
        'jpg' => 'image/jpg,image/jpeg,image/pjpeg',
        'gif' => 'image/gif',
        'csv' => 'text/csv',
    ];

    // 文件类型分类
    protected $fileType = [
        'files' => ['zip', 'rar', 'pdf', 'txt', 'csv', 'doc', 'docx', 'xlsx', 'xls'],
        'images' => ['jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp', 'webp'],
        'videos' => ['avi', 'mp4', 'flv'],
        'audio' => ['mp3', 'ogg', 'wma', 'wav']
    ];

    public function setMaxSize($size)
    {
        if (! empty($size) && is_numeric($size)) {
            $this->maxSize = $size;
        }

        return $this;
    }

    public function disk($disk = null)
    {
        if (! empty($disk) && is_string($disk)) {
            $this->disk = $disk;
        }

        return $this;
    }

    public function file($key = null, $default = null)
    {
        $this->file = Request::file($key, $default);


        // 上传文件数组集合
        $files = [];

        if (is_array($this->file)) {
            foreach ($this->file as $key => $val) {
                array_push($files, $val);
            }
        } else {
            array_push($files, $this->file);
        }

        array_walk($files, function ($file) {
            $this->checkFile($file);
        });

        return $this;
    }

    // 获取上传文件实例
    public function getFiles()
    {
        return $this->file;
    }

//    // 检测文件后缀及大小
    protected function checkFile($file)
    {
        // 检查文件大小
        if ($this->checkSize($file) !== true) {
            return false;
        }

        // 检查文件后缀
        if ($this->checkExtension($file) !== true) {
            return false;
        }

        return true;
    }

    /**
     * Store the uploaded file on a filesystem disk.
     * 存储上传文件（调用此方法上传文件）
     * @param  array $options
     * @return string|false
     */
    public function store($options = [])
    {
        if (is_array($this->file)) {
            $storePath = [];
            $files = $this->file;

            foreach ($files as $key => $file) {
                $this->file = $file;

                $path = $this->buildSavePath($file);
                $basename = $this->buildBasename($file);
                $s_p = $this->storeAs($path, $basename, $options);

                array_push($storePath, $s_p);
            }
        } else {
            $path = $this->buildSavePath($this->file);
            $basename = $this->buildBasename($this->file);
            $storePath = $this->storeAs($path, $basename, $options);
        }

        return $storePath;
    }

    public function buildBasename($file)
    {
        $md5file = md5_file($file->getRealPath());
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            $extension = $file->clientExtension();
        }
        $basename = $md5file . '.' . $extension;

        return $basename;
    }

    /**
     * 获取文件存储路径
     * @param  Object $file 上传文件实例
     * @return boolean|string
     */
    private function buildSavePath($file)
    {
        // 文件类型分类
        $fileType = $this->fileType;
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            $extension = $file->clientExtension();
        }
        $savePath = '';

        foreach ($fileType as $key => $val) {
            if (! is_array($val)) {
                continue;
            }

            if (! in_array($extension, $val)) {
                continue;
            }

            $savePath = $key;
        }

        if (empty($savePath)) {
            $this->error = '文件存储路径创建失败！';

            throw new Exception($this->error);

            return false;
        }

        $savePath = $savePath . '/' . date("Ymd", time());

        return $savePath;
    }

    public function getRealPath()
    {
        return $this->file->getRealPath();
    }

    public function getClientOriginalExtension()
    {
        return $this->file->getClientOriginalExtension();
    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string $path
     * @param  string $name
     * @param  array  $options
     * @return string|false
     */
    public function storeAs($path, $name, $options = [])
    {
        $options = ! empty($options) ? $options : $this->disk;

        $storePath = $this->file->storeAs($path, $name, $options);

        if (! $storePath) {
            $this->error = '文件保存出现错误！';

            return false;
        }

        $disk = is_array($options) ? (! empty($options['disk']) ? $options['disk'] : $this->disk) : $this->disk;

        if ($disk === 'public') {
            $storePath = config('filesystems.disks.public.local_path', '') . '/' . $storePath;
        }
        return $storePath;
    }

    /**
     * Returns the upload error.
     * 获取错误信息
     *
     * If the upload was successful, the constant UPLOAD_ERR_OK is returned.
     * Otherwise one of the other UPLOAD_ERR_XXX constants is returned.
     *
     * @return int The upload error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 检测上传文件大小
     * @param  \Illuminate\Http\UploadedFile|array|null $file 上传文件实例
     * @return bool
     */
    private function checkSize($file)
    {
        // 检查文件大小
//        if ($file->getSize() > $this->maxSize) {
//            $this->error = '上传文件大小不超过' . ($this->maxSize / 1048576) . 'M！';
//
//            throw new \Exception($this->error);
//
//            return false;
//        }

        return true;
    }

    /**
     * 检测上传文件后缀
     * @param  mixed $file 当前操作文件实例
     * @return bool
     */
    private function checkExtension($file)
    {
        // 检测文件原始后缀
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            $extension = $file->clientExtension();
        }

        if (! in_array($extension, $this->allowExtension)) {
            $this->error = '不允许的文件后缀！';
            throw new \Exception($this->error);

            return false;
        }

        return true;
    }


}
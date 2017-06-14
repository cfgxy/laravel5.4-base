<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/12/6
 * Time: 上午10:49
 */

namespace Guxy\Common\Requests;

class DefaultImportRequest extends ActionRequest
{
    protected $file_size_limit = 4096;
    protected $allowd_file_types = ['xls', 'xlsx', 'csv'];

    public function rules()
    {
        return [
            'upfile'    => "required|file|max:{$this->file_size_limit}|mimes:" . implode(',', $this->allowd_file_types)
        ];
    }

    public function sanitize()
    {
        return guxy_mask($this->files, ['upfile']);
    }

    /**
     * @return array|\Illuminate\Http\UploadedFile|null
     */
    public function validatedUpfile()
    {
        return $this->file('upfile');
    }
}

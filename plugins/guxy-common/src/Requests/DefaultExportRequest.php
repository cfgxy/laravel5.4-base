<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/12/6
 * Time: 下午1:52
 */

namespace Guxy\Common\Requests;


class DefaultExportRequest extends ActionRequest
{
    public function rules()
    {
        return [
            'field_range'     => 'required|in:shown,all',
            'row_range'       => 'required|in:page,selected,all,full_all',
            'fields.*'        => 'required_if:field_range,shown|string',
            'rows.*'          => 'required_if:row_range,selected|integer'
        ];
    }

    public function sanitize()
    {
        $range_fields = ['field_range', 'row_range'];
        $fields = ['fields', 'rows'];
        $this->text($this->query, $range_fields);
        $this->commaArray($this->query, $fields);
        $this->numberInt($this->query, 'rows[]');

        $mFields = $this->query('fields');
        if ($mFields) {
            $mFields = array_diff($mFields, ['_id', '_colvis']);
            array_unshift($mFields, 'id');
            $this->query->set('fields', $mFields);
        }

        return guxy_mask($this->query, array_merge($fields, $range_fields));
    }
}
<?php
namespace Admin42\Link\Adapter;

class ExternLink implements AdapterInterface
{
    public function assemble($value)
    {
        return $this->getLinkData($value);
    }

    public function getDisplayName($value)
    {
        return $this->getLinkData($value);
    }


    protected function getLinkData($value)
    {
        if (empty($value['url'])) {
            return '';
        }
        
        $value["url"] = str_replace("http://", "", $value["url"]);
        $value["url"] = str_replace("https://", "", $value["url"]);
        $value["url"] = str_replace("mailto:", "", $value["url"]);

        if (empty($value['type'])) {
            $value['type'] = "http://";
        }

        return $value['type'] . $value['url'];
    }
}

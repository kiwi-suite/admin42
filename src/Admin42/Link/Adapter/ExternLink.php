<?php
namespace Admin42\Link\Adapter;

class ExternLink implements AdapterInterface
{
    /**
     * @param mixed $value
     * @param array $options
     * @return string
     */
    public function assemble($value, $options = array())
    {
        return $this->getLinkData($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function getDisplayName($value)
    {
        return $this->getLinkData($value);
    }

    /**
     * @param $value
     * @return string
     */
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

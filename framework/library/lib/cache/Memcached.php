<?php
namespace Framework\library\lib\cache;

class Memcached
{
    private $time = 3600;  #存活时间
    private $mem;

    public function __construct($option)
    {
        $this->mem = new \Memcached();

        $this->mem->setOption(\Memcached::OPT_COMPRESSION, false); //关闭压缩功能
        $this->mem->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);//使用binary二进制协议

        try{
            $this->mem->addServers($option['servers']);
        } catch (Exception $e) {
            \Framework\library\log::alert( json_encode(array($e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage())));
        }
    }

    public function get($name)
    {
        return $this->mem->get($name);
    }

    public function set($name, $value, $time = NULL)
    {
        if (!$time) {
            $time = $this->time;
        }
        $ret = $this->mem->set($name, $value, $time);
        if (!$ret) {
            \Framework\library\log::alert($this->mem->getResultMessage());
        }
        return $ret;

    }

    public function del($name)
    {
        $ret = $this->mem->delete($name);
        if (!$ret) {
            \Framework\library\log::alert($this->mem->getResultMessage());
        }
        return $ret;

    }

    public function clear()
    {
        $ret = $this->mem->flush();
        if (!$ret) {
            \Framework\library\log::alert($this->mem->getResultMessage());
        }
        return $ret;
    }
}

<?php
namespace Swoole\WebSocket;

/**
 * @since 4.5.4
 */
class Frame
{
    /**
     * @return mixed
     */
    public function __toString()
    {
    }

    /**
     * @param $data[required]
     * @param $opcode[optional]
     * @param $flags[optional]
     *
     * @return mixed
     */
    public static function pack($data, $opcode = null, $flags = null)
    {
    }

    /**
     * @param $data[required]
     *
     * @return mixed
     */
    public static function unpack($data)
    {
    }
}

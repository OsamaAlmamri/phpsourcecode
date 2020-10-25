<?php
namespace Kafka\Protocol;

class ListGroup extends Protocol
{

    /**
     * list group request encode
     *
     * @param array $payloads
     * @access public
     * @return string
     */
    public function encode($payloads)
    {
        $header = $this->requestHeader('kafka-php', self::LIST_GROUPS_REQUEST, self::LIST_GROUPS_REQUEST);
        $data   = self::encodeString($header, self::PACK_INT32);

        return $data;
    }

    /**
     * decode group response
     *
     * @access public
     * @return array
     */
    public function decode($data)
    {
        $offset    = 0;
        $errorCode = self::unpack(self::BIT_B16_SIGNED, substr($data, $offset, 2));
        $offset   += 2;
        $groups    = $this->decodeArray(substr($data, $offset), [$this, 'listGroup']);

        return [
            'errorCode' => $errorCode,
            'groups' => $groups['data'],
        ];
    }

    /**
     * decode list group response
     *
     * @access protected
     * @return array
     */
    protected function listGroup($data)
    {
        $offset       = 0;
        $groupId      = $this->decodeString(substr($data, $offset), self::BIT_B16);
        $offset      += $groupId['length'];
        $protocolType = $this->decodeString(substr($data, $offset), self::BIT_B16);
        $offset      += $protocolType['length'];

        return [
            'length' => $offset,
            'data' => [
                'groupId' => $groupId['data'],
                'protocolType' => $protocolType['data'],
            ]
        ];
    }
}

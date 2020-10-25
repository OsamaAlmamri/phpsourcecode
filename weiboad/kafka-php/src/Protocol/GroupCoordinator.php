<?php
namespace Kafka\Protocol;

class GroupCoordinator extends Protocol
{

    /**
     * group coordinator request encode
     *
     * @param array $payloads
     * @access public
     * @return string
     */
    public function encode($payloads)
    {
        if (! isset($payloads['group_id'])) {
            throw new \Kafka\Exception\Protocol('given group coordinator invalid. `group_id` is undefined.');
        }

        $header = $this->requestHeader('kafka-php', self::GROUP_COORDINATOR_REQUEST, self::GROUP_COORDINATOR_REQUEST);
        $data   = self::encodeString($payloads['group_id'], self::PACK_INT16);
        $data   = self::encodeString($header . $data, self::PACK_INT32);

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
        $offset          = 0;
        $errorCode       = self::unpack(self::BIT_B16_SIGNED, substr($data, $offset, 2));
        $offset         += 2;
        $coordinatorId   = self::unpack(self::BIT_B32, substr($data, $offset, 4));
        $offset         += 4;
        $hosts           = $this->decodeString(substr($data, $offset), self::BIT_B16);
        $offset         += $hosts['length'];
        $coordinatorPort = self::unpack(self::BIT_B32, substr($data, $offset, 4));
        $offset         += 4;

        return [
            'errorCode' => $errorCode,
            'coordinatorId' => $coordinatorId,
            'coordinatorHost' => $hosts['data'],
            'coordinatorPort' => $coordinatorPort
        ];
    }
}

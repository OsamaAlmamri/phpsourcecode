<?php
namespace Kafka\Protocol;

class Heartbeat extends Protocol
{

    /**
     * heartbeat request encode
     *
     * @param array $payloads
     * @access public
     * @return string
     */
    public function encode($payloads)
    {
        if (! isset($payloads['group_id'])) {
            throw new \Kafka\Exception\Protocol('given heartbeat data invalid. `group_id` is undefined.');
        }
        if (! isset($payloads['generation_id'])) {
            throw new \Kafka\Exception\Protocol('given heartbeat data invalid. `generation_id` is undefined.');
        }
        if (! isset($payloads['member_id'])) {
            throw new \Kafka\Exception\Protocol('given heartbeat data invalid. `member_id` is undefined.');
        }

        $header = $this->requestHeader('kafka-php', self::HEART_BEAT_REQUEST, self::HEART_BEAT_REQUEST);
        $data   = self::encodeString($payloads['group_id'], self::PACK_INT16);
        $data  .= self::pack(self::BIT_B32, $payloads['generation_id']);
        $data  .= self::encodeString($payloads['member_id'], self::PACK_INT16);

        $data = self::encodeString($header . $data, self::PACK_INT32);

        return $data;
    }

    /**
     * decode heart beat response
     *
     * @access public
     * @return array
     */
    public function decode($data)
    {
        $offset    = 0;
        $errorCode = self::unpack(self::BIT_B16_SIGNED, substr($data, $offset, 2));
        $offset   += 2;

        return [
            'errorCode' => $errorCode,
        ];
    }
}

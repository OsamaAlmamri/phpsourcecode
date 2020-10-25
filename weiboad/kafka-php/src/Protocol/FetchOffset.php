<?php
namespace Kafka\Protocol;

class FetchOffset extends Protocol
{

    /**
     * fetch offset request encode
     *
     * @param array $payloads
     * @access public
     * @return string
     */
    public function encode($payloads)
    {
        if (! isset($payloads['data'])) {
            throw new \Kafka\Exception\Protocol('given fetch offset data invalid. `data` is undefined.');
        }

        if (! isset($payloads['group_id'])) {
            throw new \Kafka\Exception\Protocol('given fetch offset data invalid. `group_id` is undefined.');
        }

        $header = $this->requestHeader('kafka-php', self::OFFSET_FETCH_REQUEST, self::OFFSET_FETCH_REQUEST);
        $data   = self::encodeString($payloads['group_id'], self::PACK_INT16);
        $data  .= self::encodeArray($payloads['data'], [$this, 'encodeOffsetTopic']);
        $data   = self::encodeString($header . $data, self::PACK_INT32);

        return $data;
    }

    /**
     * decode group response
     *
     * @param string $data Bytes to be decode
     * @access public
     * @return array
     */
    public function decode($data)
    {
        $offset  = 0;
        $version = $this->getApiVersion(self::OFFSET_REQUEST);
        $topics  = $this->decodeArray(substr($data, $offset), [$this, 'offsetTopic'], $version);
        $offset += $topics['length'];

        return $topics['data'];
    }

    /**
     * encode signal part
     *
     * @param byte[] values partions data
     * @access protected
     * @return string
     */
    protected function encodeOffsetPartion($values)
    {
        return self::pack(self::BIT_B32, $values);
    }

    /**
     * encode signal topic
     *
     * @param partions
     * @access protected
     * @return string
     */
    protected function encodeOffsetTopic($values)
    {
        if (! isset($values['topic_name'])) {
            throw new \Kafka\Exception\Protocol('given fetch offset data invalid. `topic_name` is undefined.');
        }

        if (! isset($values['partitions']) || empty($values['partitions'])) {
            throw new \Kafka\Exception\Protocol('given fetch offset data invalid. `partitions` is undefined.');
        }

        $topic      = self::encodeString($values['topic_name'], self::PACK_INT16);
        $partitions = self::encodeArray($values['partitions'], [$this, 'encodeOffsetPartion']);

        return $topic . $partitions;
    }

    /**
     * decode offset topic response
     *
     * @access protected
     * @return array
     */
    protected function offsetTopic($data, $version)
    {
        $offset    = 0;
        $topicInfo = $this->decodeString(substr($data, $offset), self::BIT_B16);
        $offset   += $topicInfo['length'];

        $partitions = $this->decodeArray(substr($data, $offset), [$this, 'offsetPartition'], $version);
        $offset    += $partitions['length'];

        return [
            'length' => $offset,
            'data' => [
                'topicName' => $topicInfo['data'],
                'partitions'  => $partitions['data'],
            ]
        ];
    }

    /**
     * decode offset partition response
     *
     * @access protected
     * @return array
     */
    protected function offsetPartition($data, $version)
    {
        $offset = 0;

        $partitionId = self::unpack(self::BIT_B32, substr($data, $offset, 4));
        $offset     += 4;

        $roffset = self::unpack(self::BIT_B64, substr($data, $offset, 8));
        $offset += 8;

        $metadata  = $this->decodeString(substr($data, $offset), self::BIT_B16);
        $offset   += $metadata['length'];
        $errorCode = self::unpack(self::BIT_B16_SIGNED, substr($data, $offset, 2));
        $offset   += 2;

        return [
            'length' => $offset,
            'data' => [
                'partition' => $partitionId,
                'errorCode' => $errorCode,
                'metadata' => $metadata['data'],
                'offset' => $roffset,
            ]
        ];
    }
}

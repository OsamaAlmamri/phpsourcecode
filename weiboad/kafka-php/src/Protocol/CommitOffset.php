<?php
namespace Kafka\Protocol;

class CommitOffset extends Protocol
{

    /**
     * commit offset request encode
     *
     * @param array $payloads
     * @access public
     * @return string
     */
    public function encode($payloads)
    {
        if (! isset($payloads['group_id'])) {
            throw new \Kafka\Exception\Protocol('given commit offset data invalid. `group_id` is undefined.');
        }

        if (! isset($payloads['data'])) {
            throw new \Kafka\Exception\Protocol('given commit data invalid. `data` is undefined.');
        }

        if (! isset($payloads['generation_id'])) {
            $payloads['generation_id'] = -1;
        }

        if (! isset($payloads['member_id'])) {
            $payloads['member_id'] = '';
        }

        if (! isset($payloads['retention_time'])) {
            $payloads['retention_time'] = -1;
        }

        $version = $this->getApiVersion(self::OFFSET_COMMIT_REQUEST);

        $header = $this->requestHeader('kafka-php', self::OFFSET_COMMIT_REQUEST, self::OFFSET_COMMIT_REQUEST);

        $data = self::encodeString($payloads['group_id'], self::PACK_INT16);
        if ($version == self::API_VERSION1) {
            $data .= self::pack(self::BIT_B32, $payloads['generation_id']);
            $data .= self::encodeString($payloads['member_id'], self::PACK_INT16);
        }
        if ($version == self::API_VERSION2) {
            $data .= self::pack(self::BIT_B32, $payloads['generation_id']);
            $data .= self::encodeString($payloads['member_id'], self::PACK_INT16);
            $data .= self::pack(self::BIT_B64, $payloads['retention_time']);
        }

        $data .= self::encodeArray($payloads['data'], [$this, 'encodeTopic']);
        $data  = self::encodeString($header . $data, self::PACK_INT32);

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
        $offset  = 0;
        $version = $this->getApiVersion(self::OFFSET_REQUEST);
        $topics  = $this->decodeArray(substr($data, $offset), [$this, 'decodeTopic'], $version);
        $offset += $topics['length'];

        return $topics['data'];
    }

    /**
     * encode commit offset topic array
     *
     * @param array $values
     * @access public
     * @return array
     */
    protected function encodeTopic($values)
    {
        if (! isset($values['topic_name'])) {
            throw new \Kafka\Exception\Protocol('given commit offset data invalid. `topic_name` is undefined.');
        }
        if (! isset($values['partitions'])) {
            throw new \Kafka\Exception\Protocol('given commit offset data invalid. `partitions` is undefined.');
        }

        $data  = self::encodeString($values['topic_name'], self::PACK_INT16);
        $data .= self::encodeArray($values['partitions'], [$this, 'encodePartition']);

        return $data;
    }

    /**
     * encode commit offset partition array
     *
     * @param array $values
     * @access public
     * @return array
     */
    protected function encodePartition($values)
    {
        if (! isset($values['partition'])) {
            throw new \Kafka\Exception\Protocol('given commit offset data invalid. `partition` is undefined.');
        }
        if (! isset($values['offset'])) {
            throw new \Kafka\Exception\Protocol('given commit offset data invalid. `offset` is undefined.');
        }
        if (! isset($values['metadata'])) {
            $values['metadata'] = '';
        }
        if (! isset($values['timestamp'])) {
            $values['timestamp'] = time() * 1000;
        }
        $version = $this->getApiVersion(self::OFFSET_COMMIT_REQUEST);

        $data  = self::pack(self::BIT_B32, $values['partition']);
        $data .= self::pack(self::BIT_B64, $values['offset']);
        if ($version == self::API_VERSION1) {
            $data .= self::pack(self::BIT_B64, $values['timestamp']);
        }
        $data .= self::encodeString($values['metadata'], self::PACK_INT16);

        return $data;
    }

    /**
     * decode commit offset topic response
     *
     * @param string $data Bytes to be decoded
     * @param string $version
     *
     * @access protected
     * @return array
     */
    protected function decodeTopic($data, $version)
    {
        $offset    = 0;
        $topicInfo = $this->decodeString(substr($data, $offset), self::BIT_B16);
        $offset   += $topicInfo['length'];

        $partitions = $this->decodeArray(substr($data, $offset), [$this, 'decodePartition'], $version);
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
     * decode commit offset partition response
     *
     * @param string $data Bytes to be decoded
     * @param string $version
     *
     * @access protected
     * @return array
     */
    protected function decodePartition($data, $version)
    {
        $offset = 0;

        $partitionId = self::unpack(self::BIT_B32, substr($data, $offset, 4));
        $offset     += 4;

        $errorCode = self::unpack(self::BIT_B16_SIGNED, substr($data, $offset, 2));
        $offset   += 2;

        return [
            'length' => $offset,
            'data' => [
                'partition' => $partitionId,
                'errorCode' => $errorCode,
            ]
        ];
    }
}

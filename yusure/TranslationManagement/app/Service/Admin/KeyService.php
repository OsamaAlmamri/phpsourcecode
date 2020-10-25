<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\KeyRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
use DB;

/**
* Key Service
*/
class KeyService extends BaseService
{

    protected $keyRepository;

    public function __construct( KeyRepositoryEloquent $keyRepository )
    {
        $this->keyRepository = $keyRepository;
    }

    /**
     * 获取 key 和 源语言 列表
     * @author Yusure  http://yusure.cn
     * @date   2017-11-10
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function getKeyList( $id )
    {
        return $this->keyRepository->getKeyList( $id );
    }

    /**
     * 存储 key + source
     */
    public function storeKey( $id, $data )
    {
        try
        {
            if ( isset( $data['key_id'] ) && $data['key_id'] > 0 )
            {
                /* Update */
                $res = $this->keyRepository->updateKey( $id, $data );
            }
            else
            {
                $data = [
                    'project_id' => $id,
                    'key'        => trim( $data['key'] ),
                    'source'     => $data['source'],
                    'tag'        => $data['tag'] ?? 1,
                    'length'     => $data['length'] ?? 0,
                ];
                $res = $this->keyRepository->create( $data );
            }
            return $res;
        }
        catch ( Exception $e )
        {
            $this->sendSystemErrorMail( env('MAIL_SYSTEMERROR',''), $e );
            return false;
        }
    }

    /**
     * 删除翻译 key
     */
    public function deleteKey( $project_id, $key_id )
    {
        return $this->keyRepository->deleteKey( $project_id, $key_id );
    }

    /**
     * 更新排序
     */
    public function updateSort( $key_id, $sort )
    {
        return $this->keyRepository->batchUpdate( 'id', 'sort', $key_id, $sort );
    }

    /**
     * 更新 tag
     */
    public function updateTag( $key_id, $tag )
    {
        return $this->keyRepository->updateTag( $key_id, $tag );
    }

    /**
     * 修改 Length
     */
    public function updateLength( $key_id, $length )
    {
        return $this->keyRepository->updateLength( $key_id, $length );
    }

    /**
     * 导入源语言
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function importSource( $id, $url )
    {
        $res = xmlToArray( $url );
        $keys = array_keys( $res );
        $exist = $this->keyRepository->keyExist( $id, $keys );
        /* 如果有重复数据就从结果里面去除 */
        if ( $exist )
        {
            foreach ( $exist as $k => $item )
            {
                unset( $res[$item->key] );
            }
        }

        $sources = [];
        foreach ( $res as $key => $source )
        {
            $sources[] = ['project_id' => $id, 'key' => $key, 'source' => $source];
        }
        return $this->keyRepository->batchInsertKey( $sources );
    }
    
    public function importSourceOniOS( $id, $url )
    {
        $file_arr = file( $url );
        $source_data = $keys = [];
        foreach ( $file_arr as $k => $item )
        {
            $item_arr = explode( '=', $item );

            $key = trim( $item_arr[0] );
            $key = trim( $key, '"' );
            if ( in_array( $key, $keys ) )
            {
                continue;
            }
            else
            {
                $keys[] = $key;
            }

            $value = trim( $item_arr[1] );
            $value = trim( $value, ';' );
            $value = trim( $value, '"' );

            $res[$key] = $value;

            // $source_data[] = [
            //     'project_id' => $id,
            //     'key' => $key,
            //     'source' => $value
            // ];
        }

        $exist = $this->keyRepository->keyExist( $id, $keys );
        /* 如果有重复数据就从结果里面去除 */
        if ( $exist )
        {
            foreach ( $exist as $k => $item )
            {
                unset( $res[$item->key] );
            }
        }

        $sources = [];
        foreach ( $res as $key => $source )
        {
            $sources[] = ['project_id' => $id, 'key' => $key, 'source' => $source];
        }
        return $this->keyRepository->batchInsertKey( $sources );
    }
}
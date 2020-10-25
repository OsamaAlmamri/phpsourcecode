<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ContentRepository;
use App\Models\Content;
use DB;

/**
 * Class ContentRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ContentRepositoryEloquent extends BaseRepository implements ContentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Content::class;
    }

    /**
     * 判断译文是否存在
     */
    public function translated_exist( $language_id, $key_id )
    {
        $condition = ['language_id' => $language_id, 'key_id' => $key_id];
        return $this->model->where( $condition )->value( 'id' );
    }

    /**
     * 更新译文
     * @author Yusure  http://yusure.cn
     * @date   2017-11-14
     * @param  [param]
     * @return [type]     [description]
     */
    public function update_content( $language_id, $key_id, $content )
    {
        $condition = ['language_id' => $language_id, 'key_id' => $key_id];
        $data = ['content' => $content];
        return $this->model->where( $condition )->update( $data );
    }

    /**
     * 获取 翻译者 的译文
     */
    public function getTranslatedContents( $id )
    {
        return $this->model->where( 'language_id', $id )->select( 'id', 'content', 'key_id', 'flag' )->get()->toArray();
    }

    /**
     * 获取源对照内容
     */
    public function getSourceContents( $id )
    {
        return $this->model
                ->join( 'keys', 'contents.key_id', '=', 'keys.id' )
                ->where( 'contents.language_id', $id )
                ->orderBy( 'keys.sort', 'asc' )
                ->orderBy( 'keys.id', 'asc' )
                ->select( 'contents.key_id', 'contents.content', 'keys.key', 'keys.length' )
                ->get();
    }

    /**
     * 译文是否存在
     */
    public function contentExist( $language_id, $key_ids )
    {
        return $this->model->where( 'language_id', $language_id )->whereIn( 'key_id', $key_ids )->get();
    }

    /**
     * 批量写入译文
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @return [type]     [description]
     */
    public function batchInsertContent( $data )
    {
        return $this->model->insert( $data );
    }

    /**
     * 获取单个字段值
     */
    public function getField( $condition, $field = 'id' )
    {
        return $this->model->where( $condition )->value( $field );
    }

    /**
     * 获取一行信息
     */
    public function getInfo( $condition )
    {
        return $this->model->where( $condition )->first();
    }

    /**
     * 更新内容
     */
    public function update_data( $condition, $data )
    {
        return $this->model->where( $condition )->update( $data );
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

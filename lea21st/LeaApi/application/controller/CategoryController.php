<?php
/**
 * Created by PhpStorm.
 * User: lea
 * Date: 2018/3/27
 * Time: 15:42
 */

namespace app\controller;

use app\library\Auth;
use app\library\Y;
use app\model\Api;
use app\model\Category;
use think\Request;

class CategoryController extends BaseController
{
    /**
     * 添加页面和添加操作
     * @return mixed
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $post     = $request->post();
            $validate = new \app\validate\Category();
            if (!$validate->check($post)) {
                $this->error($validate->getError());
            }
            $post['project_id'] = session('project_id');
            $post['user_id']    = session('user.id');
            if ((new Category())->save($post) !== false) {
                $this->success('添加成功');
            }
            $this->error('添加失败');
        } else {
            return view('edit');
        }
    }

    /**
     * 修改页面和修改操作
     * @return mixed
     */
    public function edit(Request $request)
    {
        if ($request->isPost()) {
            $post     = $request->only(['title', 'sort', 'status', 'remark', 'id']);
            $validate = new \app\validate\Category();
            if (!$validate->check($post)) {
                $this->error($validate->getError());
            }
            if ((new Category())->save($post, ['id' => $post['id'], 'user_id' => session('user.id')]) !== false) {
                $this->success('更新成功');
            }
            $this->error('更新失败');
        } else {
            $id       = $request->get('id', 0);
            $category = Category::get($id);
            if (!$category || $category->user_id != session('user.id')) {
                $this->error('该分类不存在');
            }
            return view('edit', [
                'category' => $category
            ]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id', 0);
        if (Api::where('category_id', $id)->find()) {
            $this->error('该分类下有接口，无法删除');
        }

        if (Category::where('id', $id)->where('user_id', session('user.id'))->delete()) {
            $this->success('删除成功');
        }
        $this->error('删除失败');

    }
}
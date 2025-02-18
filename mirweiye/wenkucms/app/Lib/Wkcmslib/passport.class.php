<?php
/**
 * 文章模板标签解析
 *
 * @author andery
 */
class passport
{
    private $_error = 0;
    private $_us = null;

    public function __construct($name) {
        $file = LIB_PATH . 'Wkcmslib/passport/' . $name . '.php';
        include $file;
        $class = $name . '_passport';
        $this->_us  = new $class();
      
       
    }

    static function uc($name) {
        include LIB_PATH . 'Wkcmslib/passport/' . $name . '.php';
        $class = $name . '_passport';
        return new $class();
    }

    /**
     * 注册新用户
     */
    public function register($username, $password, $email) {
        if (!$add_data = $this->_us->register($username, $password, $email)) {
            $this->_error = $this->_us->get_error();
            return false;
        }
        //添加到本地
        return $this->_local_add($add_data);
    }

    
    public function uc_avatar($uid){
    	
    	
    	
    	$html=$this->_us->uc_avatar($uid);
    	return $html;
    	
    }
    
    
    
    /**
     * 修改用户资料
     * $force  是否强制修改
     */
    public function edit($uid, $old_password, $data, $force = false) {
        if (!$edit_data = $this->_us->edit($uid, $old_password, $data, $force)) {
            $this->_error = $this->_us->get_error();
            return false;
        }
        //本地修改
        return $this->_local_edit($uid, $edit_data);
    }

    /**
     * 删除用户
     */
    public function delete($uid) {
        if ($this->_us->delete($uid)) {
            $this->_error = $this->_us->get_error();
            return false;
        }
        return $this->_local_delete($uid);
    }

    /**
     * 获取用户信息
     */
    public function get($flag, $is_name = false) {
        return $this->_us->get($flag, $is_name = false);
    }

    /**
     * 登陆验证
     */
    public function auth($username, $password) {
       $result = $this->_us->auth($username, $password);
        if (!$result) {
        $uid = M('user')->where(array('username'=>$username,'status'=>1, 'password'=>md5($password.C('WEB_MD5'))))->getField('uid');
        if ($uid) {
            return $uid;
        } else {
        	
            $this->_error = L('auth_failed');
            return false;
        }
      }else{
        
        
        
        
        
        
        
        
        
        
        if (is_array($result)) {
            $uid = $this->_local_sync($result);
            return $uid;
        }else{
        	
        	return $result;
        	
        }
      }
        
    }

    /**
     * 同步登陆
     */
    public function synlogin($uid) {
        return $this->_us->synlogin($uid);
    }

    /**
     * 同步退出
     */
    public function synlogout() {
        return $this->_us->synlogout();
    }

    /**
     * 本地用户添加
     */
    private function _local_add($add_data) {
        $user_mod = D('user');
        if (false !== $user_mod->create($add_data)) {
            $uid = $user_mod->add();
            if (!$uid) {
                $this->_error = $user_mod->getError();
                return false;
            } else {
                return $uid;
            }
        } else {
            $this->_error = $user_mod->getError();
            return false;
        }
    }

    /**
     * 本地用户编辑
     */
    private function _local_edit($uid, $data) {
        isset($data['password']) && $data['password'] = md5($data['password']);
        M('user')->where(array('uid'=>$uid))->save($data);
        return true;
    }

    /**
     * 本地用户删除
     */
    private function _local_delete($uid) {
        $user_mod = D('user');
        return $user_mod->delete($uid);
    }

    private function _local_get($flag, $is_name = false) {
        if ($is_name) {
            $map = array('username' => $flag);
        } else {
            $map = array('uid' => intval($flag));
        }
        return M('user')->where($map)->find();
    }

    /**
     * 本地用户同步
     */
    private function _local_sync($user_info) {
        $local_info = $this->_local_get($user_info['username'], true);
        if (empty($local_info)) {
            $local_info['uid'] = $this->_local_add($user_info); //新增本地用户
        } else {
            $this->_local_edit($local_info['uid'], $user_info); //更新本地用户
        }
        return $local_info['uid'];
    }

    public function get_error() {
        return $this->_error;
    }
}
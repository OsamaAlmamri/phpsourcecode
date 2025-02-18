const app = getApp()
var api = require('../../utils/request');
var util = require('../../utils/util');
var common = require('../../utils/common');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loading: false,
    shopping_name:'购物积分',
    phone:0,
    disabled: false,
    countdown: 60,
    bank: {
      money: 0,
      due_money: 0,
      lack_money: 0,
      income_monney: 0,
      shop_money: 0
    },
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.getBank();
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    this.setData({
      shopping_name: app.globalData.config.shopping_name
    })
  },
  /**
   * 读取我的账单
   */
  getBank: function () {
    let that = this;
    api.Get("api/v3/fastshop/bank/index", function (result) {
      if (result.code == 200) {
        that.setData({ bank: result.data });
      }
    })
  },
  /**
   * 申请提现
   */
  onSubmit: function (e) {
    let that = this;
    var data = e.detail.value;
    var isPost = false;
     if(util.isNull(data.money)) {
      wx.showModal({
        content: '转账金额必须填写'
      })
     } else if (util.isNull(data.safepassword)) {
       wx.showModal({
         content: '安全密码必须填写'
       })
     } else if (util.isNull(data.phone)) {
      wx.showModal({
        content: '手机号必须输入'
      })
    } else if (!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(data.phone))) {
        wx.showModal({
          content: '手机号格式不正确'
        })
     }else if (util.isNull(data.code)) {
      wx.showModal({
        content: '验证码必须输入'
      })
    } else if (!(/^\d{6}$/.test(data.code))) {
        wx.showModal({
          content: '验证码输入错误'
        })
    }else {
      if (!(/^\d+$/.test(data.money))) {
        wx.showModal({
          content: '提现金额只能输入整数'
        })
      } else {
        var money = parseInt(data.money);
        var bank = this.data.bank;
        if (money > bank.due_money || money == 0) {
          wx.showModal({
            content: '超出允许提现金额'
          })
        }else{
          isPost = true;
        }
      }
    }
    //提交数据
    if (isPost == true) {
      wx.showLoading({title: '提交申请中',mask: true})
      var parms = {
        money: data.money,
        code: data.code,
        phone: data.phone,
        safepassword: data.safepassword,
      }
      api.Post('api/v3/fastshop/bank/transfer', parms, function (res) {
        wx.hideLoading();
        if (res.code == 200) {
          wx.showModal({
            showCancel: false,title: '友情提示',content: res.msg,
            complete: function () {
              wx.navigateBack({ delta: 1 })
            }
          })
        }
      })
    }
  },
  /**
  * 设置输入手机号
  */
  bindPhone: function (e) {
    let that = this;
    var phone = e.detail;
    var isPost = true;
    if (util.isNull(phone)) {
      isPost = false;
    } else {
      if (!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(phone))) {
        isPost = false;
      }
    }
    if (isPost) {
      that.setData({
        phone: phone
      })
    }
  },
  /**
   * 读取
   */
  getSms: function (e) {
    let that = this;
    var isPost = true;
    var phone = that.data.phone;
    if (util.isNull(phone)) {
      wx.showModal({
        content: '手机号必须输入'
      })
      isPost = false;
    } else {
      if (!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(phone))) {
        wx.showModal({
          content: '手机号格式不正确'
        })
        isPost = false;
      }
    }
    if (isPost) {
      api.Post('openapi/v1/user/getFriendPhoneCode', { phone: phone, types: 0 }, function (res) {
        wx.setStorageSync('session_id', res.data);
        wx.showModal({
          showCancel: false,
          content: '验证码已发送'
        })
        common.settime(that);
        that.setData({
          disabled: true
        })
      })
    }
  },
})
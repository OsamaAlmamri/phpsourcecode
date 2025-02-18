@extends('layouts.admin')
@section('content')
<!-- Main content -->
<section class="content">
  <!-- row -->
  <div class="row">
    <div class="col-xs-12">
      <div class="box" id="app-content">
        <div class="box-header">
          <h3 class="box-title"> 【 @{{cur_title}} 】 </h3>
        </div>
        <!-- /.box-header -->
          <div class="box-body">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_type')}}</span>
                <select class="form-control" v-model="params_data.type">
                  <option v-for="item in modellist" :value="item.value">@{{ item.text }}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_token')}}</span>
                <input type="text" class="form-control" v-model="params_data.token"   >
              </div>
            </div>
            
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_name')}}</span>
                <input type="text" class="form-control" v-model="params_data.name"   >
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_wechataccount')}}</span>
                <input type="text" class="form-control" v-model="params_data.wechataccount"   >
              </div>  
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_gid')}}</span>
                <input type="text" class="form-control" v-model="params_data.gid"   >
              </div>  
            </div>
            <div class="form-group">
              <label >{{trans('admin.fieldname_item_logo')}}</label><br>
              <!--
              <input type="file" @change="onFileChange" id="fileuploads" class="file"  accept="image/*">
              
              <p class="help-block">200*200</p>
              <img  v-if="image" :src="image" width="200" height="200" />
              -->
              <!-- file style -->
              <link rel="stylesheet" href="{{asset('/module/jQueryIpputCss')}}/css/style.css">
              <div class="uploader white" v-if="params_data.isattach == 0">
              <input type="text" class="filename" readonly/>
              <input type="button"  name="file" class="button" value="选择图片"/>
              <input type="file" size="30"  @change="onFileChange" />
              <input type="hidden" v-model="params_data.attachment" >
              </div>
              @ability('admin', 'delete')
              <button v-else type="button" @click="del_image_action()"  class="btn btn-block btn-danger btn-lg">删除图片</button>
              @endability
              <p class="help-block">缩略图200*200</p>
              <img  v-if="image" :src="image" width="200" height="200" />
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_appid')}}</span>
                <input type="text" class="form-control" v-model="params_data.appid"   >
              </div>  
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_appsecret')}}</span>
                <input type="text" class="form-control" v-model="params_data.appsecret"   >
              </div>  
            </div>
            <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_encodingaeskey')}}</span>
                  <textarea  class="form-control" rows="3" v-model="params_data.encodingaeskey" > </textarea>
                </div>
            </div>
            <div class="box-header with-border">
              <h3 class="box-title">{{trans('admin.define_model_wechat_tip_pay')}}</h3>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_mchid')}}</span>
                <input type="text" class="form-control" v-model="params_data.mchid"   >
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon minwidth">{{trans('admin.fieldname_item_paykey')}}</span>
                <input type="text" class="form-control" v-model="params_data.paykey"   >
              </div>
            </div>  
            <div class="form-group">
                <label >{{trans('admin.fieldname_item_status')}}</label>
                <div style="padding-left:10px;"><input type="radio"  value="1" v-model="params_data.status" style="margin-right:10px;"> {{trans('admin.website_status_on')}}</div>
                <div style="padding-left:10px;"><input type="radio"  value="0" v-model="params_data.status" style="margin-right:10px;"> {{trans('admin.website_status_off')}}</div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button v-if="params_data.id == 0" type="button" @click="check_action(apiurl_add)" class="btn btn-primary" > <i class="fa fa-hand-peace-o"></i> {{trans('admin.website_action_save')}}</button>
            <button v-else type="button" @click="check_action(apiurl_edit)" class="btn btn-primary" > <i class="fa fa-hand-peace-o"></i> {{trans('admin.website_action_save')}}</button>
            <button type="button" @click="back_action()" class="btn btn-primary" > <i class="fa fa-reply"></i> {{trans('admin.website_action_getback')}}</button>
          </div>

      </div>
      <!-- /.box -->
    </div>
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

<script type="text/javascript">
new Vue({
    el: '#app-content',
    data: {
             apiurl_add:            '{{ route("post.admin.wechat.api_add") }}', 
             apiurl_info:           '{{ route("post.admin.wechat.api_info") }}', 
             apiurl_edit:           '{{ route("post.admin.wechat.api_edit") }}',
             apiurl_del_image:      '{{ route("post.admin.deleteapi.api_del_image") }}',
             modellist:             eval(htmlspecialchars_decode('{{$website["modellist"]}}')), 
             params_data:
             {
                type                :1,
                token               :'',
                name                :'',
                wechataccount       :'',
                gid                 :'',
                appid               :'',
                appsecret           :'',
                encodingaeskey	    :'',
                attachment          :'',
                isattach            :0,
                mchid               :'',
                paykey              :'',
                status              :1,
                id                  :'{{$website["id"]}}',
             },
             image                  :'',
             cur_title              :'',
             cur_title_add          :'{{trans("admin.website_action_add")}}',
             cur_title_edit         :'{{trans("admin.website_action_edit")}}',
             del_data:
             {
                id                  :'{{$website["id"]}}',
                modelname           :'{{getCurrentControllerName()}}',
             }
          },
    created: function (){ 
            //这里是vue初始化完成后执行的函数 
            if(this.params_data.id>0)
            {
                this.cur_title=this.cur_title_edit;
                this.get_info_action();
            }
            else
            {
                this.cur_title=this.cur_title_add;
            }
    }, 
    methods: 
    {
      //选择图片
      onFileChange:function(e) {
        var files = e.target.files || e.dataTransfer.files;
        if (!files.length)
          return;
        this.createImage(files[0]);
      },
      //创建图片
      createImage:function(file) 
      {
        var image = new Image();
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (e) => {
          this.params_data.attachment=e.target.result;
          this.image=e.target.result;
          //console.log(this.params_data.attachment);
        };
      },
      //获取数据详情
      get_info_action:function()
      {
        this.$http.post(this.apiurl_info,this.params_data,
        {
          before:function(request)
          {
            loadi=layer.load("...");
          },
        })
        .then((response) => 
        {
          this.ready_info_action(response);
        },(response) => 
        {
          //响应错误
          layer.close(loadi);
          var msg="{{trans('admin.message_outtime')}}";
          layermsg_error(msg);
        })
        .catch(function(response) {
          //异常抛出
          layer.close(loadi);
          var msg="{{trans('admin.message_error')}}";
          layermsg_error(msg);
        })
      },
      //点击数据验证
      check_action:function(posturl)
      {
          if (this.params_data.token=='')
          {
              var msg="{{trans('admin.option_wechat_failure_token')}}";
              layermsg_error(msg);
          }
          else if (this.params_data.name=='')
          {
              var msg="{{trans('admin.option_wechat_failure_name')}}";
              layermsg_error(msg);
          }
          else if (this.params_data.gid=='')
          {
              var msg="{{trans('admin.option_wechat_failure_gid')}}";
              layermsg_error(msg);
          }
          else
          {
              this.post_action(posturl);
          }
      },
      //提交数据
      post_action:function(posturl)
      {
        this.$http.post(posturl,this.params_data,{
          before:function(request)
          {
            loadi=layer.load("等待中...");
          },
        })
        .then((response) => 
        {
          this.return_info_action(response);

        },(response) => 
        {
          //响应错误
          layer.close(loadi);
          var msg="{{trans('admin.message_outtime')}}";
          layermsg_error(msg);
        })
        .catch(function(response) {
          //异常抛出
          layer.close(loadi);
          var msg="{{trans('admin.message_error')}}";
          layermsg_error(msg);
        })

      },
      //返回信息处理
      return_info_action:function(response)
      {
        layer.close(loadi);
        var statusinfo=response.data;
        if(statusinfo.status==1)
        {
            if(statusinfo.is_reload==1)
            {
              layermsg_success_reload(statusinfo.info);
            }
            else
            {
              if(statusinfo.curl)
              {
                layermsg_s(statusinfo.info,statusinfo.curl);
              }
              else
              {
                layermsg_success(statusinfo.info);
              }
            }
        }
        else
        {
            if(statusinfo.curl)
            {
              layermsg_e(statusinfo.info,statusinfo.curl);
            }
            else
            {

              layermsg_error(statusinfo.info);
            }
        }
      },
      //处理初始化数据
      ready_info_action:function(response)
      {
        layer.close(loadi);
        var statusinfo=response.data;
        if(statusinfo.status==1)
        {
            this.params_data=statusinfo.resource;
            this.params_data.waytype="{{$website['waytype']}}";

            if(this.params_data.attachment)
            {
              this.image="/uploads/"+this.del_data.modelname+"/thumb/"+this.params_data.attachment;
              this.params_data.attachment="";
            }
        }
        else
        {
            if(statusinfo.curl)
            {
              layermsg_e(statusinfo.info,statusinfo.curl);
            }
            else
            {
              layermsg_error(statusinfo.info);
            }
        }
      },
      //点击返回
      back_action:function()
      {
        goback();
      },
      del_image_action:function()
      {
        this.$http.post(this.apiurl_del_image,this.del_data,{
          before:function(request)
          {
            loadi=layer.load("...");
          },
        })
        .then((response) => 
        {
          this.return_info_action(response);

        },(response) => 
        {
          //响应错误
          layer.close(loadi);
          var msg="{{trans('admin.message_outtime')}}";
          layermsg_error(msg);
        })
        .catch(function(response) {
          //异常抛出
          layer.close(loadi);
          var msg="{{trans('admin.message_error')}}";
          layermsg_error(msg);
        })
      }
    }               
});
document.body.onbeforeunload = function (event)
{
    var c = event || window.event;
    if (/webkit/.test(navigator.userAgent.toLowerCase())) {
        //return "离开页面将导致数据丢失！";
        $('.filename').val("尚未选择图片");
    }
    else
    {
        //c.returnValue = "离开页面将导致数据丢失！";
        $('.filename').val("尚未选择图片");
    }
}
$(function()
{
    $("input[type=file]").change(function(){$(this).parents(".uploader").find(".filename").val($(this).val());});
    $("input[type=file]").each(function(){
    if($(this).val()==""){$(this).parents(".uploader").find(".filename").val("尚未选择图片");}
    });
    
});

</script>
@endsection
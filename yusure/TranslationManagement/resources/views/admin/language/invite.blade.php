@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet">
@endsection
@section('content')
@inject( 'ProjectPresenter', 'App\Presenters\Admin\ProjectPresenter' )
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/project.invite')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/project')}}">{!!trans('admin/breadcrumb.project.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.project.invite')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    @include('flash::message')
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/project.invite')!!}</h5>
          <div class="ibox-tools">
              <a class="collapse-link">
                  <i class="fa fa-chevron-up"></i>
              </a>
              <a class="close-link">
                  <i class="fa fa-times"></i>
              </a>
          </div>
        </div>
        <div class="ibox-content">
          <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('invite') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/project.model.invite')}}</label>
              <div class="col-sm-10">
                {!! $ProjectPresenter->showInviteUser( $all_user, $invite_user ) !!}
                @if ($errors->has('languages'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('languages') }}</span>
                @endif
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  <a class="btn btn-white" href="{{url( 'admin/project/'.$project_id )}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
                  <button class="btn btn-primary" type="submit">{!!trans('admin/action.actionButton.submit')!!}</button>
              </div>
            </div>
          </form>
        </div>
    </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script type="text/javascript" src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/project/project.js')}}"></script>
@endsection
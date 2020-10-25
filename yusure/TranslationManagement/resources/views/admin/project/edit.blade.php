@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet">
@endsection
@section('content')
@inject( 'ProjectPresenter', 'App\Presenters\Admin\ProjectPresenter' )
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/project.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/project')}}">{!!trans('admin/breadcrumb.project.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.project.edit')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/project.edit')!!}</h5>
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
          <form method="post" action="{{url('admin/project', [$project->id])}}?callback={{urlencode( url()->previous() )}}" class="form-horizontal" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <input type="hidden" name="id" value="{{$project->id}}">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/project.model.name')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name', $project->name)}}" placeholder="{{trans('admin/project.model.name')}}">
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/project.model.description')}}</label>
              <div class="col-sm-10">
                <textarea type="text" class="form-control" name="description" placeholder="{{trans('admin/project.model.description')}}">{{old('description', $project->description)}}</textarea>
                @if ($errors->has('description'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('description') }}</span>
                @endif
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('translation_language') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/project.model.translation_language')}}</label>
              <div class="col-sm-10">
                {!! $ProjectPresenter->showLanguages( $project->languages ) !!}
                @if ($errors->has('languages'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('languages') }}</span>
                @endif
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('belongs_application') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/project.model.belongs_application')}}</label>
              <div class="col-sm-10">
                {!! $ProjectPresenter->showApps( $apps, $project->app_id ) !!}
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  <a class="btn btn-white" href="{{url()->previous()}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
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
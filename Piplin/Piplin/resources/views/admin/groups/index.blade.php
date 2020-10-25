@extends('layouts.admin')

@section('admin-content')
<div class="panel-body" id="no_groups">
    <p>{{ trans('groups.none') }}</p>
</div>

<div class="panel-body table-responsive" id="group_list">

    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('groups.name') }}</th>
                <th>{{ trans('groups.projects') }}</th>
                <th class="text-right">{{ trans('app.actions') }}</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    {!! $groups->render() !!}
</div>
@include('admin._dialogs.group')
@stop

@section('right-buttons')
<div class="pull-right">
    <button type="button" class="btn btn-primary" title="{{ trans('groups.create') }}" data-toggle="modal" data-target="#group"><span class="piplin piplin-plus"></span> {{ trans('groups.create') }}</button>
</div>
@stop

@push('javascript')
<script type="text/javascript">
    var groups = {!! $groups->toJson() !!};

    new Piplin.GroupsTab();
    Piplin.Groups.add(groups.data);

    @if(isset($action) && $action == 'create')
    $('button.btn.btn-primary').trigger('click');
    @endif
</script>
@endpush

@push('templates')
<script type="text/template" id="group-template">
    <td data-group-id="<%- id %>"><span class="drag-handle"><i class="piplin piplin-drag"></i></span><%- name %></td>
    <td><%- project_count %></td>
    <td>
        <div class="btn-group pull-right">
            <button class="btn btn-default btn-edit" title="{{ trans('app.edit') }}" data-toggle="modal" data-target="#group" data-group-id="<%- id %>"><i class="piplin piplin-edit"></i></button>
            <button class="btn btn-danger btn-delete" title="{{ trans('app.delete') }}" data-toggle="modal" data-target="#model-trash" data-group-id="<%- id %>"><i class="piplin piplin-delete"></i></button>
        </div>
    </td>
</script>
@endpush

<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ trans('members.label') }}</h3>
        <div class="pull-right">
            @if($project->can('manage'))
            <button type="button" class="btn btn-primary" title="{{ trans('members.create') }}" data-toggle="modal" data-target="#member"><span class="piplin piplin-plus"></span> {{ trans('members.create') }}</button>
            @endif
        </div>
    </div>

    <div class="box-body" id="no_members">
        <p>{{ trans('members.none') }}</p>
    </div>

    <div class="box-body table-responsive">
        <table class="table table-striped" id="member_list">
            <thead>
                <tr>
                    <th>{{ trans('users.name') }}</th>
                    <th>{{ trans('users.email') }}</th>
                    <th>{{ trans('members.status') }}</th>
                    <th class="text-right">{{ trans('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

@push('templates')
    <script type="text/template" id="member-template">
        <td><%- name %></td>
        <td><%- email %></td>
        <td>{{ trans('members.joined') }}</td>
        <td class="text-right">
            @if($project->can('manage'))
            <div class="btn-group">
                <button type="button" class="btn btn-danger btn-delete" title="{{ trans('members.delete') }}" data-toggle="modal" data-backdrop="static" data-target="#model-trash"><i class="piplin piplin-leave"></i></button>
            </div>
            @endif
        </td>
    </script>
@endpush
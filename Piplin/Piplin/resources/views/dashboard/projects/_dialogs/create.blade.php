<div class="modal fade" id="project_create" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="piplin piplin-project"></i> <span>{{ trans('projects.create') }}</span></h4>
            </div>
            <form class="form-horizontal" role="form">
                <input type="hidden" id="project_id" name="id" />
                <div class="modal-body">
                    <div class="callout callout-danger">
                        <i class="icon piplin piplin-warning"></i> {{ trans('projects.warning') }}
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="project_name">{{ trans('projects.name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="project_name" placeholder="{{ trans('projects.name_placeholder') }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="project_description">{{ trans('projects.description') }}</label>
                        <div class="col-sm-9">
                            <textarea rows="3" id="project_description" class="form-control" name="description" placeholder="{{ trans('projects.description') }}"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="project_repository">{{ trans('projects.repository_path') }} <i class="piplin piplin-info" data-html="true" data-toggle="tooltip" data-placement="right" title="{!! trans('keys.git_keys') !!}"></i></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="repository" id="project_repository" placeholder="https://username:password@gitlab.com/group/project.git" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="project_deploy_path">{{ trans('projects.deploy_path') }} <i class="piplin piplin-info" data-html="true" data-toggle="tooltip" data-placement="right" title="{!! trans('projects.deploy_path_help') !!}"></i></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="deploy_path" id="project_deploy_path"  placeholder="/var/www/app" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="project_branch">{{ trans('projects.branch') }}</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="branch" value="master" id="project_branch"  placeholder="master" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('projects.options') }}</label>
                        <div class="col-sm-9 checkbox">
                            <label for="project_allow_other_branch">
                                <input type="checkbox" value="1" name="allow_other_branch" id="project_allow_other_branch" />
                                {{ trans('projects.change_branch') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-save"><i class="piplin piplin-save"></i> {{ trans('app.save') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('app.cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
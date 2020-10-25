<div class="modal fade" id="key" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="piplin piplin-key"></i> <span>{{ trans('keys.create') }}</span></h4>
            </div>
            <form class="form-horizontal" role="form">
                <input type="hidden" id="key_id" name="id" />
                <div class="modal-body">

                    <div class="callout callout-danger">
                        <i class="icon piplin piplin-warning"></i> {{ trans('keys.warning') }}
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="key_name">{{ trans('keys.name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="key_name" placeholder="{{ trans('keys.name') }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('keys.private_ssh_key') }} <i class="piplin piplin-help" data-toggle="tooltip" data-placement="right" title="{{ trans('keys.ssh_key_info') }}"></i></label>
						<div class="col-sm-9">
                        <textarea name="private_key" rows="10" id="key_private_key" class="form-control" placeholder="{{ trans('keys.ssh_key_example') }}"></textarea>
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

@include('dashboard.projects._dialogs.public_key')
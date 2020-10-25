(function ($) {

    $('#hook').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        $('.btn-danger', modal).hide();
        $('.callout-danger', modal).hide();
        $('.callout-warning', modal).hide();
        $('.has-error', modal).removeClass('has-error');
        $('.label-danger', modal).remove();

        if (button.hasClass('btn-edit')) {
            $('.btn-danger', modal).show();
        } else {
            $('#hook_id').val('');
            $('#hook_name').val('');
            $('#hook_type').val('');
            $('#hook :input[id^=hook_config]').val('');
            $('#hook .hook-config input[type=checkbox]').prop('checked', true);
            $('#hook .hook-enabled input[type=checkbox]').prop('checked', true);
            $('#hook .modal-footer').hide();
            $('.hook-config').hide();
            $('.hook-enabled').hide();
            $('#hook-type').show();
            modal.find('.modal-title span').text(trans('hooks.create'));
        }
    });

    $('#hook #hook-type a.btn-app').on('click', function(event) {
        var button = $(event.currentTarget);
        var modal = $('#hook');

        if (button.attr('disabled')) {
            $('.callout-warning', modal).show();
            return;
        }

        $('.callout-warning', modal).hide();

        var type = button.data('type');
        setTitleWithIcon(type, 'create');
    });

    function setTitleWithIcon(type, action) {
        $('#hook .modal-title span').text(trans('hooks.' + action + '_' + type));

        var element = $('#hook .modal-title i').removeClass().addClass('ion');
        var icon = 'edit';

        if (type === 'slack') {
            icon = 'slack';
        } else if (type === 'dingtalk') {
            icon = 'pin';
        } else if (type === 'mail') {
            icon = 'email';
        } else if (type === 'custom') {
            icon = 'edit';
        }

        element.addClass('piplin-' + icon);

        $('#hook .modal-footer').show();
        $('.hook-config').hide();
        $('#hook-type').hide();
        $('#hook-name').show();
        $('#hook-triggers').show();
        $('.hook-enabled').show();
        $('#hook-config-' + type).show();
        $('#hook_type').val(type);
    }

    $('body').delegate('.hook-trash button.btn-delete','click', function (event) {
        var target = $(event.currentTarget);
        var icon = target.find('i');
        var dialog = target.parents('.modal');

        icon.removeClass().addClass('piplin piplin-load piplin-spin');
        dialog.find('input').attr('disabled', 'disabled');
        $('button.close', dialog).hide();

        var hook = Piplin.Hooks.get($('#model_id').val());

        hook.destroy({
            wait: true,
            success: function(model, response, options) {
                dialog.modal('hide');
                $('.callout-danger', dialog).hide();

                icon.removeClass().addClass('piplin piplin-delete');
                $('button.close', dialog).show();
                dialog.find('input').removeAttr('disabled');

                Piplin.toast(trans('hooks.delete_success'));
            },
            error: function() {
                icon.removeClass().addClass('piplin piplin-delete');
                $('button.close', dialog).show();
                dialog.find('input').removeAttr('disabled');
            }
        });
    });

    $('#hook button.btn-save').on('click', function (event) {
        var target = $(event.currentTarget);
        var icon = target.find('i');
        var dialog = target.parents('.modal');

        icon.removeClass().addClass('piplin piplin-load piplin-spin');
        dialog.find('input').attr('disabled', 'disabled');
        $('button.close', dialog).hide();

        var hook_id = $('#hook_id').val();

        if (hook_id) {
            var hook = Piplin.Hooks.get(hook_id);
        } else {
            var hook = new Piplin.Hook();
        }

        var data = {
          config:          null,
          name:            $('#hook_name').val(),
          type:            $('#hook_type').val(),
          project_id:      parseInt($('input[name="project_id"]').val()),
          enabled:         $('#hook_enabled').is(':checked'),
          on_task_success: $('#hook_on_task_success').is(':checked'),
          on_task_failure: $('#hook_on_task_failure').is(':checked')
        };

        $('#hook #hook-config-' + data.type + ' :input[id^=hook_config]').each(function(key, field) {
            var name = $(field).attr('name');

            data[name] = $(field).val();
        });

        hook.save(data, {
            wait: true,
            success: function(model, response, options) {
                dialog.modal('hide');
                $('.callout-danger', dialog).hide();

                icon.removeClass().addClass('piplin piplin-save');
                $('button.close', dialog).show();
                dialog.find('input').removeAttr('disabled');

                var msg = trans('hooks.edit_success');
                if (!hook_id) {
                    Piplin.Hooks.add(response);
                    msg = trans('hooks.create_success');
                }
                Piplin.toast(msg);
            },
            error: function(model, response, options) {
                $('.callout-danger', dialog).show();

                var errors = response.responseJSON;

                $('.has-error', dialog).removeClass('has-error');
                $('.label-danger', dialog).remove();

                $('form input', dialog).each(function (index, element) {
                    element = $(element);

                    var name = element.attr('name');

                    if (typeof errors[name] !== 'undefined') {
                        var parent = element.parent();
                        parent.addClass('has-error');
                        parent.append($('<span>').attr('class', 'label label-danger').text(errors[name]));
                    }
                });

                icon.removeClass().addClass('piplin piplin-save');
                $('button.close', dialog).show();
                dialog.find('input').removeAttr('disabled');
            }
        });
    });

    Piplin.Hook = Backbone.Model.extend({
        urlRoot: '/hooks/' + parseInt($('input[name="project_id"]').val())
    });

    var Hooks = Backbone.Collection.extend({
        model: Piplin.Hook
    });

    Piplin.Hooks = new Hooks();

    Piplin.HooksTab = Backbone.View.extend({
        el: '#app',
        events: {

        },
        initialize: function() {
            this.$list = $('#hook_list tbody');

            $('#no_hooks').show();
            $('#hook_list').hide();

            this.listenTo(Piplin.Hooks, 'add', this.addOne);
            this.listenTo(Piplin.Hooks, 'reset', this.addAll);
            this.listenTo(Piplin.Hooks, 'remove', this.addAll);
            this.listenTo(Piplin.Hooks, 'all', this.render);


            Piplin.listener.on('hook:' + Piplin.events.MODEL_CHANGED, function (data) {
                var hook = Piplin.Hooks.get(parseInt(data.model.id));

                if (hook) {
                    hook.set(data.model);
                }
            });

            Piplin.listener.on('hook:' + Piplin.events.MODEL_CREATED, function (data) {
                if (parseInt(data.model.project_id) === parseInt(Piplin.project_id)) {
                    Piplin.Hooks.add(data.model);
                }
            });

            Piplin.listener.on('hook:' + Piplin.events.MODEL_TRASHED, function (data) {
                var hook = Piplin.Hooks.get(parseInt(data.model.id));

                if (hook) {
                    Piplin.Hooks.remove(hook);
                }
            });
        },
        render: function () {
            if (Piplin.Hooks.length) {
                $('#no_hooks').hide();
                $('#hook_list').show();
            } else {
                $('#no_hooks').show();
                $('#hook_list').hide();
            }
        },
        addOne: function (hook) {
            var view = new Piplin.HookView({
                model: hook
            });

            this.$list.append(view.render().el);
        },
        addAll: function () {
            this.$list.html('');
            Piplin.Hooks.each(this.addOne, this);
        }
    });

    Piplin.HookView = Backbone.View.extend({
        tagName:  'tr',
        events: {
            'click .btn-edit': 'edit',
            'click .btn-delete': 'trash'
        },
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);

            this.template = _.template($('#hook-template').html());
        },
        render: function () {
            var data = this.model.toJSON();

            data.icon = 'edit';
            data.label = trans('hooks.custom');

            if (this.model.get('type') !== 'custom') {
                data.label = trans('hooks.' + this.model.get('type'));
            }

            if (this.model.get('type') === 'slack') {
                data.icon = 'slack';
            } else if (this.model.get('type') === 'dingtalk') {
                data.icon = 'pin';
            } else if (this.model.get('type') === 'mail') {
                data.icon = 'email';
            }

            this.$el.html(this.template(data));

            return this;
        },
        edit: function() {
            var type = this.model.get('type');

            $.each(this.model.get('config'), function(field, value) {
                $('#hook-config-' + type + ' #hook_config_' + field).val(value);
            });

            $('#hook_id').val(this.model.id);
            $('#hook_name').val(this.model.get('name'));
            $('#hook_type').val(type);
            $('#hook_enabled').prop('checked', (this.model.get('enabled') === true));
            $('#hook_on_task_success').prop('checked', (this.model.get('on_task_success') === true));
            $('#hook_on_task_failure').prop('checked', (this.model.get('on_task_failure') === true));

            setTitleWithIcon(this.model.get('type'), 'edit');
        },
        trash: function() {
            var target = $('#model_id');
            target.val(this.model.id);
            target.parents('.modal').removeClass().addClass('modal fade hook-trash');
        }
    });
})(jQuery);
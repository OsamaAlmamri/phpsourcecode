(function ($) {

    $('#key_list table').sortable({
        containerSelector: 'table',
        itemPath: '> tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>',
        delay: 500,
        onDrop: function (item, container, _super) {
            _super(item, container);

            var ids = [];
            $('tbody tr td:first-child', container.el[0]).each(function (idx, element) {
                ids.push($(element).data('key-id'));
            });

            $.ajax({
                url: '/admin/keys/reorder',
                method: 'POST',
                data: {
                    keys: ids
                }
            });
        }
    });

    $('#key').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        var title = trans('keys.create');

        $('.btn-danger', modal).hide();
        $('.callout-danger', modal).hide();
        $('.has-error', modal).removeClass('has-error');
        $('.label-danger', modal).remove();

        if (button.hasClass('btn-edit')) {
            title = trans('keys.edit');
            $('.btn-danger', modal).show();
        } else {
            $('#key_id').val('');
            $('#key_name').val('');
            $('#key_private_key').val('');
        }

        modal.find('.modal-title span').text(title);
    });

    $('body').delegate('.key-trash button.btn-delete','click', function (event) {
        var target = $(event.currentTarget);
        var icon = target.find('i');
        var dialog = target.parents('.modal');

        icon.removeClass().addClass('piplin piplin-load piplin-spin');
        dialog.find('input').attr('disabled', 'disabled');
        $('button.close', dialog).hide();

        var key = Piplin.Keys.get($('#model_id').val());

        key.destroy({
            wait: true,
            success: function(model, response, options) {
                dialog.modal('hide');
                $('.callout-danger', dialog).hide();

                icon.removeClass().addClass('piplin piplin-delete');
                $('button.close', dialog).show();
                dialog.find('input').removeAttr('disabled');

                Piplin.toast(trans('keys.delete_success'));
            },
            error: function() {
                icon.removeClass().addClass('piplin piplin-delete');
                $('button.close', dialog).show();
                dialog.find('input').removeAttr('disabled');
            }
        });
    });

    $('#key button.btn-save').on('click', function (event) {
        var target = $(event.currentTarget);
        var icon = target.find('i');
        var dialog = target.parents('.modal');

        icon.removeClass().addClass('piplin piplin-load piplin-spin');
        dialog.find('input').attr('disabled', 'disabled');
        $('button.close', dialog).hide();

        var key_id = $('#key_id').val();

        if (key_id) {
            var key = Piplin.Keys.get(key_id);
        } else {
            var key = new Piplin.Key();
        }

        key.save({
            name:         $('#key_name').val(),
            private_key:  $('#key_private_key').val()
        }, {
            wait: true,
            success: function(model, response, options) {
                dialog.modal('hide');
                $('.callout-danger', dialog).hide();

                icon.removeClass().addClass('piplin piplin-save');
                $('button.close', dialog).show();
                dialog.find('input').removeAttr('disabled');

                var msg = trans('keys.edit_success');
                if (!key_id) {
                    Piplin.Keys.add(response);
                    msg = trans('keys.create_success');
                }
                Piplin.toast(msg);
            },
            error: function(model, response, options) {
                $('.callout-danger', dialog).show();

                var errors = response.responseJSON;

                $('.has-error', dialog).removeClass('has-error');
                $('.label-danger', dialog).remove();

                $('form input, form textarea', dialog).each(function (index, element) {
                    element = $(element);

                    var name = element.attr('name');

                    if (typeof errors[name] !== 'undefined') {
                        var parent = element.parent('div');
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


    Piplin.Key = Backbone.Model.extend({
        urlRoot: '/admin/keys'
    });

    var Keys = Backbone.Collection.extend({
        model: Piplin.Key,
        comparator: function(keyA, keyB) {
            if (keyA.get('name') > keyB.get('name')) {
                return -1; // before
            } else if (keyA.get('name') < keyB.get('name')) {
                return 1; // after
            }

            return 0; // equal
        }
    });

    Piplin.Keys = new Keys();

    Piplin.KeysTab = Backbone.View.extend({
        el: '#app',
        events: {

        },
        initialize: function() {
            this.$list = $('#key_list tbody');

            $('#no_keys').show();
            $('#key_list').hide();

            this.listenTo(Piplin.Keys, 'add', this.addOne);
            this.listenTo(Piplin.Keys, 'reset', this.addAll);
            this.listenTo(Piplin.Keys, 'remove', this.addAll);
            this.listenTo(Piplin.Keys, 'all', this.render);

            Piplin.listener.on('key:' + Piplin.events.MODEL_CHANGED, function (data) {
                var key = Piplin.Keys.get(parseInt(data.model.id));

                if (key) {
                    key.set(data.model);
                }
            });

            Piplin.listener.on('key:' + Piplin.events.MODEL_CREATED, function (data) {
                Piplin.Keys.add(data.model);
            });

            Piplin.listener.on('key:' + Piplin.events.MODEL_TRASHED, function (data) {
                var key = Piplin.Keys.get(parseInt(data.model.id));

                if (key) {
                    Piplin.Keys.remove(key);
                }
            });
        },
        render: function () {
            if (Piplin.Keys.length) {
                $('#no_keys').hide();
                $('#key_list').show();
            } else {
                $('#no_keys').show();
                $('#key_list').hide();
            }
        },
        addOne: function (key) {

            var view = new Piplin.KeyView({
                model: key
            });

            this.$list.append(view.render().el);

            if (Piplin.Keys.length < 2) {
                $('.drag-handle', this.$list).hide();
            } else {
                $('.drag-handle', this.$list).show();
            }
        },
        addAll: function () {
            this.$list.html('');
            Piplin.Keys.each(this.addOne, this);
        }
    });

    Piplin.KeyView = Backbone.View.extend({
        tagName:  'tr',
        events: {
            'click .btn-show': 'show',
            'click .btn-edit': 'edit',
            'click .btn-delete': 'trash'
        },
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);

            this.template = _.template($('#key-template').html());
        },
        render: function () {
            var data = this.model.toJSON();

            this.$el.html(this.template(data));

            return this;
        },
        edit: function() {
            $('#key_id').val(this.model.id);
            $('#key_name').val(this.model.get('name'));
            $('#key_private_key').val(this.model.get('private_key'));

        },
        show: function() {
            var data = this.model.toJSON();

            $('#key_log pre').html(data.public_key);
        },
        trash: function() {
            var target = $('#model_id');
            target.val(this.model.id);
            target.parents('.modal').removeClass().addClass('modal fade key-trash');
        }
    });
})(jQuery);
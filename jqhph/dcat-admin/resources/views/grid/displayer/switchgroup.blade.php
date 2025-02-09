
<style>
    table.grid-switch-group td {
        padding: 3px 0;
        height:23px;
        border: 0;
    }
</style>

<table class="grid-switch-group">
    @foreach($columns as $column => $label)
        @php($checked = Illuminate\Support\Arr::get($row, $column) ? 'checked' : '')

        <tr style="box-shadow: none;background: transparent">
            <td>{{ $label }}:&nbsp;&nbsp;&nbsp;</td>
            <td><input name="{{ $column }}" data-path="{{ $resource }}" data-key="{{ $key }}" {{ $checked }}
                                                           type="checkbox" class="grid-column-switch-group" data-size="small" data-color="{{ $color }}"/></td>
        </tr>
    @endforeach
</table>

<script require="@switchery">
    var swt = $('.grid-column-switch-group'),
        reload = '{{ $refresh }}',
        that;
    function initSwitchery() {
        swt.each(function() {
            that = $(this);
            that.parent().find('.switchery').remove();

            new Switchery(that[0], that.data())
        })
    }
    initSwitchery();
    swt.off('change').change(function(e) {
        var that = $(this),
            id = that.data('key'),
            url = that.data('path') + '/' + id,
            checked = that.is(':checked'),
            name = that.attr('name'),
            data = {},
            value = checked ? 1 : 0;

        if (name.indexOf('.') === -1) {
            data[name] = value;
        } else {
            name = name.split('.');

            data[name[0]] = {};
            data[name[0]][name[1]] = value;
        }
        Dcat.NP.start();

        $.put({
            url: url,
            data: data,
            success: function (d) {
                Dcat.NP.done();
                if (d.status) {
                    Dcat.success(d.message);
                    reload && Dcat.reload()
                } else {
                    Dcat.error(d.message);
                }
            }
        });
    });
</script>

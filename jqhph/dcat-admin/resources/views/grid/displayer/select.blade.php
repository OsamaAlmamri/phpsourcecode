<div class="input-group input-group-sm">
    <select style="width: 100%;" class="grid-column-select" data-url="{{ $url }}" data-name="{{ $column }}">
        @foreach($options as $k => $v)
            @php($selected = Dcat\Admin\Support\Helper::equal($k, $value)  ? 'selected' : '')

            <option value="{{ $k }}" {{ $selected }}>{{ $v }}</option>
        @endforeach

    </select>
</div>

<script require="@select2">
    $('.grid-column-select').off('change').select2().on('change', function(){
        var value = $(this).val(),
            name = $(this).data('name'),
            url = $(this).data('url'),
            data = {},
            reload = '{{ $refresh }}';

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
            success: function (data) {
                Dcat.NP.done();
                Dcat.success(data.message);
                reload && Dcat.reload();
            }
        });
    });
</script>
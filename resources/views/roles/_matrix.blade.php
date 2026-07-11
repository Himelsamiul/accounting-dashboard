@php
    $modules = \App\Models\Role::modules();
    $actions = \App\Models\Role::actions();
    $selected = $selected ?? [];
@endphp
<div class="table-wrap">
    <table class="table perm-matrix">
        <thead>
            <tr>
                <th>Module / Section</th>
                @foreach($actions as $a)
                    <th style="text-align:center; text-transform:capitalize;">{{ $a }}</th>
                @endforeach
                <th style="text-align:center;">All</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modules as $key => $label)
                @php
                    $sel = $selected[$key] ?? [];
                    $allowed = \App\Models\Role::actionsFor($key);
                @endphp
                <tr>
                    <td class="strong">{{ $label }}</td>
                    @foreach($actions as $a)
                        <td style="text-align:center;">
                            @if(in_array($a, $allowed, true))
                                <input type="checkbox" class="perm-cb" data-module="{{ $key }}"
                                       name="permissions[{{ $key }}][]" value="{{ $a }}"
                                       {{ in_array($a, $sel, true) ? 'checked' : '' }}>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                    @endforeach
                    <td style="text-align:center;">
                        <input type="checkbox" class="perm-all" data-module="{{ $key }}"
                               {{ count($sel) === count($allowed) && count($allowed) > 0 ? 'checked' : '' }}>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<span class="hint" style="display:block; margin-top:10px;">Tick the specific actions a role may perform in each section. Leave a whole row empty to hide that section from the role. Dashboard and Fully Paid are view-only.</span>

<script>
    (function () {
        function rowBoxes(module) {
            return Array.from(document.querySelectorAll('.perm-cb[data-module="' + module + '"]'));
        }
        document.querySelectorAll('.perm-all').forEach(function (all) {
            var module = all.getAttribute('data-module');
            all.addEventListener('change', function () {
                rowBoxes(module).forEach(function (cb) { cb.checked = all.checked; });
            });
        });
        document.querySelectorAll('.perm-cb').forEach(function (cb) {
            cb.addEventListener('change', function () {
                var module = cb.getAttribute('data-module');
                var boxes = rowBoxes(module);
                var all = document.querySelector('.perm-all[data-module="' + module + '"]');
                if (all) all.checked = boxes.length > 0 && boxes.every(function (b) { return b.checked; });
            });
        });
    })();
</script>

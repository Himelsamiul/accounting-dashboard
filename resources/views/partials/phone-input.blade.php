@php
    // [name, dial code, flag emoji]
    $countries = [
        ['Bangladesh', '+880', '🇧🇩'],
        ['India', '+91', '🇮🇳'],
        ['Pakistan', '+92', '🇵🇰'],
        ['United States', '+1', '🇺🇸'],
        ['United Kingdom', '+44', '🇬🇧'],
        ['United Arab Emirates', '+971', '🇦🇪'],
        ['Saudi Arabia', '+966', '🇸🇦'],
        ['Qatar', '+974', '🇶🇦'],
        ['Kuwait', '+965', '🇰🇼'],
        ['Oman', '+968', '🇴🇲'],
        ['Malaysia', '+60', '🇲🇾'],
        ['Singapore', '+65', '🇸🇬'],
        ['Nepal', '+977', '🇳🇵'],
        ['Sri Lanka', '+94', '🇱🇰'],
        ['China', '+86', '🇨🇳'],
        ['Japan', '+81', '🇯🇵'],
        ['Australia', '+61', '🇦🇺'],
        ['Canada', '+1', '🇨🇦'],
        ['Germany', '+49', '🇩🇪'],
        ['France', '+33', '🇫🇷'],
        ['Italy', '+39', '🇮🇹'],
        ['Turkey', '+90', '🇹🇷'],
        ['Indonesia', '+62', '🇮🇩'],
        ['Thailand', '+66', '🇹🇭'],
        ['South Africa', '+27', '🇿🇦'],
    ];
    $phoneValue = trim($value ?? '');
@endphp

<div class="phone-field">
    <select class="select phone-code" id="phoneCode" aria-label="Country code">
        @foreach($countries as $ct)
            <option value="{{ $ct[1] }}">{{ $ct[2] }} {{ $ct[1] }}</option>
        @endforeach
    </select>
    <input class="input phone-number" type="tel" id="phoneNumber" inputmode="numeric" pattern="[0-9]{6,15}" placeholder="1712345678" title="Enter 6–15 digits, numbers only" required>
</div>
<input type="hidden" name="phone" id="phoneHidden" value="{{ $phoneValue }}">
<span class="hint">Select country code, then enter the number (digits only).</span>

<script>
    (function () {
        var code = document.getElementById('phoneCode');
        var num = document.getElementById('phoneNumber');
        var hidden = document.getElementById('phoneHidden');

        function sync() {
            hidden.value = num.value ? (code.value + ' ' + num.value) : '';
        }

        // Split an existing stored value ("+880 1712345678") back into code + number.
        var stored = (hidden.value || '').trim();
        if (stored) {
            var codes = Array.from(code.options).map(function (o) { return o.value; })
                .sort(function (a, b) { return b.length - a.length; });
            var matched = false;
            for (var i = 0; i < codes.length; i++) {
                if (stored.indexOf(codes[i]) === 0) {
                    code.value = codes[i];
                    num.value = stored.slice(codes[i].length).replace(/[^0-9]/g, '');
                    matched = true;
                    break;
                }
            }
            if (!matched) { num.value = stored.replace(/[^0-9]/g, ''); }
        }

        code.addEventListener('change', sync);
        num.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            sync();
        });
        sync();
    })();
</script>

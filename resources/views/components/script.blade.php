@once
    <script
        defer
        src="/keypad/keypad.js" 
        event="keypad"
        storage="keypad"
        salt="{{ $keypad->salt() }}"
        data="{{ json_encode($keypad->data) }}"
    ></script>
@endonce
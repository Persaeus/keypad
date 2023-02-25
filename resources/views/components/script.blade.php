@once
    <script
        defer
        src="/cipher/cipher.js" 
        event="cipher"
        storage="cipher"
        salt="{{ $cipher->salt() }}"
        data="{{ json_encode($cipher->data) }}"
    ></script>
@endonce
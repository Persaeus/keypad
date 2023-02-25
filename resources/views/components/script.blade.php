@once
    <script
        defer
        src="/cipher/cipher.js" 
        event="cipher"
        storage="cipher"
        salt="{{ $cipher->salt() }}"
        token="{{ csrf_token() }}"
    ></script>
@endonce
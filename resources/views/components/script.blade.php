@once
    <script
        defer
        src="/cipher/cipher.js" 
        event="cipher"
        storage="cipher"
        salt="{{ $cipher->salt() }}"
    ></script>
@endonce
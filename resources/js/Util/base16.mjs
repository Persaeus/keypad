/**
 * Class responsible for encoding and decoding base16 (hexadecimal).
 */
export default class base16 {
    static encode(data) {
        return Array
            .from(new Uint8Array(data))
            .map((b) => b.toString(16).padStart(2, '0'))
            .join('')
    }

    static decode(string) {
        return new Uint8Array(string.match(/../g).map(h => parseInt(h, 16))).buffer
    }
}
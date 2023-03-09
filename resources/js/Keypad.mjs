import Password from "./Password.mjs"
import Script from "./Script.mjs"

import base16 from "./Util/base16.mjs"

export default class Keypad {
    /**
     * @param {{ privateKey: CryptoKey, publicKey: CryptoKey }}
     */
    constructor({ privateKey, publicKey }) {
        this.privateKey = privateKey

        this.publicKey = publicKey
    }

    /**
     * Encrypt sensitive data using the given password and export the data.
     * 
     * @param {Password} [password]
     * @returns {Promise<{ k: string, p: string, s: string }>}
     */
    async export(password = null) {
        password ??= Password.recall()

        const
            salt = crypto.getRandomValues(new Uint8Array(16)),
            wrappedPrivateKey = password.wrap(this.privateKey, salt),
            exportedPublicKey = crypto.subtle.exportKey(
                'spki',
                this.publicKey
            )

        return {
            k: base16.encode(await wrappedPrivateKey),
            p: base16.encode(await exportedPublicKey),
            s: base16.encode(salt)
        }
    }

    /**
     * Generate a new keypad.
     * 
     * @returns {Promise<Keypad>}
     */
    static async generate() {
        const
            keyPair = await crypto.subtle.generateKey(
                {
                    name: 'RSA-OAEP',
                    modulusLength: 4096,
                    publicExponent: new Uint8Array([1, 0, 1]),
                    hash: 'SHA-256'
                },
                true,
                ['encrypt', 'decrypt', 'wrapKey', 'unwrapKey']
            ),
            privateKey = keyPair.privateKey,
            publicKey = keyPair.publicKey

        return new this({ privateKey, publicKey })
    }

    /**
     * Parse and decrypt previously exported keypad data using the given password.
     * 
     * @param {{ k: string, p: string, s: string }}
     * @param {Password} [password]
     * @returns {Promise<Keypad>}
     */
    static async import({ k, p, s }, password = null) {
        password ??= Password.recall()

        const
            salt = base16.decode(s),
            exportedPublicKey = base16.decode(p),
            wrappedPrivateKey = base16.decode(k)

        const [privateKey, publicKey] = await Promise.all([
            password.unwrap(wrappedPrivateKey, salt),
            crypto.subtle.importKey(
                'spki',
                exportedPublicKey,
                {
                    name: 'RSA-OAEP',
                    hash: 'SHA-256'
                },
                true,
                ['encrypt', 'wrapKey']
            )
        ])

        return new this({
            privateKey,
            publicKey,
        })
    }

    /**
     * Resolve the keypad of currently authenticated user.
     * 
     * @returns {Promise<Keypad> | null}
     */
    static resolve() {
        const json = Script.data

        return json
            ? this.import(JSON.parse(json))
            : null
    }
}
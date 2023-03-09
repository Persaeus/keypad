import Script from "./Script.mjs";

import base16 from "./Util/base16.mjs";
import encode from "./Util/encode.mjs";
import sha256 from "./Util/sha256.mjs";

export default class Password {
    /** @type {Promise<ArrayBuffer>} - The private, client-side password hash. */
    #hash

    /** @type {Promise<ArrayBuffer> | null} - The 'shareable', server-side password hash. */
    hash

    static salt = Script.salt

    static storage = Script.storage

    static token = Script.token

    /**
     * @param {{ clientHash: ArrayBufferLike, serverHash: ArrayBufferLike }} param0 
     */
    constructor({ clientHash, serverHash = null }) {
        this.#hash = clientHash

        this.hash = serverHash
    }

    /**
     * Initialize a password instance by hashing a plaintext password.
     * 
     * @param {string} plaintext 
     * @returns {Password}
     */
    static hash(plaintext) {
        const
            salt = this.salt,
            hash = input => sha256(encode(input)),
            clientHash = hash(plaintext + salt),
            serverHash = hash(salt + plaintext)

        return new this({ clientHash, serverHash })
    }

    /**
     * Recall a previously stored instance from local storage.
     * 
     * @returns {Password}
     */
    static recall() {
        const
            stored = this.retrieve(),
            clientHash = base16.decode(stored.pop())

        return new this({ clientHash })
    }

    /**
     * Store the password hash in local storage, so it may be
     * recalled in a subsequent request post-authentication.
     * 
     * @param {{ append: Boolean }}
     * @returns {Promise<Password>}
     */
    async remember({ append = false } = {}) {
        const
            key = Password.storage,
            stored = append ? Password.retrieve() : [],
            hash = base16.encode(await this.#hash),
            value = stored.filter(value => value != hash).concat(hash)

        localStorage.setItem(
            key,
            value
        )

        return this
    }

    /**
     * @returns {Promise<Boolean>}
     */
    async restore() {
        const
            hash = base16.encode(await this.#hash),
            stored = Password.retrieve(),
            index = stored.indexOf(hash)

        if (index < 1) {
            return false;
        }

        this.#hash = base16.decode(stored[index - 1])

        return true
    }

    /**
     * @returns {Array<string>}
     */
    static retrieve() {
        /** @var {string | null} */
        const stored = localStorage.getItem(this.storage)

        return stored ? stored.split(',') : []
    }

    /**
     * Use the password to unwrap (decrypt) the given wrapped key using the given salt.
     * 
     * @param {ArrayBufferLike} wrappedKey 
     * @param {ArrayBufferLike} salt 
     * @returns 
     */
    async unwrap(wrappedKey, salt, unwrappedKeyAlgorithm = { name: 'RSA-OAEP', hash: 'SHA-256' }) {
        try {
            const
                unwrappingKey = await this.#wrappingKey(salt),
                unwrappedKey = await crypto.subtle.unwrapKey(
                    'pkcs8',
                    wrappedKey,
                    unwrappingKey,
                    { name: 'AES-GCM', iv: salt },
                    unwrappedKeyAlgorithm,
                    true,
                    ['decrypt']
                )

            return unwrappedKey
        } catch (error) {
            if (await this.restore()) {
                return await this.unwrap(wrappedKey, salt, unwrappedKeyAlgorithm)
            }

            throw error
        }
    }

    /**
     * Use the password to wrap (encrypt) the given key using the given salt.
     * 
     * @param {CryptoKey} key 
     * @param {ArrayBufferLike} salt 
     */
    async wrap(key, salt) {
        const
            wrappingKey = await this.#wrappingKey(salt),
            wrappedKey = await crypto.subtle.wrapKey(
                'pkcs8',
                key,
                wrappingKey,
                { name: 'AES-GCM', iv: salt }
            )

        return wrappedKey
    }

    /**
     * Get the wrapping key for the given salt.
     * 
     * @param {ArrayBufferLike} salt 
     * @returns {Promise<CryptoKey>}
     */
    async #wrappingKey(salt) {
        const baseKey = await crypto.subtle.importKey(
            'raw',
            await this.#hash,
            'PBKDF2',
            false,
            ['deriveBits', 'deriveKey'],
        )

        return await crypto.subtle.deriveKey(
            {
                salt,
                name: 'PBKDF2',
                iterations: 100000,
                hash: 'SHA-256',
            },
            baseKey,
            { 'name': 'AES-GCM', 'length': 256 },
            true,
            ['encrypt', 'decrypt', 'wrapKey', 'unwrapKey'],
        )
    }
}
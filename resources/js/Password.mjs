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
     * @returns {Promise<Password>}
     */
    static async recall() {
        const
            stored = localStorage.getItem(this.storage),
            clientHash = base16.decode(stored)

        return new this({ clientHash })
    }

    /**
     * Store the password hash in local storage, so it may be
     * recalled in a subsequent request post-authentication.
     * 
     * @returns {Promise<Password>}
     */
    async remember() {
        localStorage.setItem(
            this.storage,
            base16.encode(await this.#hash)
        )

        return this
    }

    /**
     * Use the password to unwrap (decrypt) the given wrapped key using the given salt.
     * 
     * @param {ArrayBufferLike} wrappedKey 
     * @param {ArrayBufferLike} salt 
     * @returns 
     */
    async unwrap(wrappedKey, salt, unwrappedKeyAlgorithm = { name: 'RSA-OAEP' }) {
        const unwrappingKey = await this.#wrappingKey(salt)

        return await crypto.subtle.unwrapKey(
            'pkcs8',
            wrappedKey,
            unwrappingKey,
            { name: 'AES-GCM', iv: salt },
            unwrappedKeyAlgorithm,
            true,
            ['encrypt', 'decrypt', 'wrapKey', 'unwrapKey']
        )
    }

    /**
     * Use the password to wrap (encrypt) the given key using the given salt.
     * 
     * @param {CryptoKey} key 
     * @param {ArrayBufferLike} salt 
     */
    async wrap(key, salt) {
        const wrappingKey = await this.#wrappingKey(salt)

        return await crypto.subtle.wrapKey(
            'pkcs8',
            key,
            wrappingKey,
            { name: 'AES-GCM', iv: salt }
        )
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
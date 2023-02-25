import Fieldset from "./Fieldset.mjs";

import base16 from "../Util/base16.mjs";
import encode from "../Util/encode.mjs";

export default class Encrypt extends Fieldset {
    constructor(node, { target, key }) {
        super(node)

        this.target = target

        this.publicKey = key

        this.onSubmit(this.encrypt)
    }

    async encrypt() {
        const
            plaintext = this.input(this.target).value,
            publicKey = await crypto.subtle.importKey(
                'spki',
                base16.decode(this.publicKey),
                {
                    name: 'RSA-OAEP',
                    hash: 'SHA-256'
                },
                true,
                ['encrypt']
            ),
            ciphertext = await crypto.subtle.encrypt(
                { name: 'RSA-OAEP' },
                publicKey,
                encode(plaintext)
            )

        this.output(this.target, base16.encode(ciphertext))
    }
}
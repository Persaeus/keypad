import Component from "./Component.mjs";

import base16 from "../Util/base16.mjs";
import Cipher from "../Cipher.mjs";
import decode from "../Util/decode.mjs";

export default class Decrypt extends Component {
    constructor(node) {
        super(node)

        this.decrypt()
    }

    async decrypt() {
        const
            cipher = await Cipher.resolve(),
            privateKey = cipher.privateKey,
            ciphertext = base16.decode(this.node.innerText),
            plaintext = await crypto.subtle.decrypt(
                { name: 'RSA-OAEP' },
                privateKey,
                ciphertext
            )

        this.node.innerText = decode(plaintext)

        this.node.hidden = false
    }
}
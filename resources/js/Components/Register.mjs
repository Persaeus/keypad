import Cipher from "../Cipher.mjs";
import Fieldset from "./Fieldset.mjs";
import Password from "../Password.mjs";

import base16 from "../Util/base16.mjs";

export default class Register extends Fieldset {
    constructor(node, { password, confirmation }) {
        super(node)

        this.password = password
        this.confirmation = confirmation

        this.onSubmit(this.register)
    }

    async register() {
        const
            plaintextPassword = this.input(this.password).value,
            password = await Password.hash(plaintextPassword).remember()

        this.output(this.password, base16.encode(await password.hash))

        if (this.confirmation) {
            const
                passwordConfirmed = this.input(this.confirmation).value,
                confirmationHash = Password.hash(passwordConfirmed).hash

            this.output(
                this.confirmation,
                base16.encode(await confirmationHash)
            )
        }

        const
            cipher = await Cipher.generate(),
            exported = await cipher.export(password),
            json = JSON.stringify(exported)

        this.output('_cipher', json)
    }
}
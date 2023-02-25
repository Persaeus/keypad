import Fieldset from "./Fieldset.mjs";
import Password from "../Password.mjs";

import base16 from "../Util/base16.mjs";

export default class Login extends Fieldset {
    constructor(node, { password }) {
        super(node)

        this.password = password

        this.onSubmit(this.login)
    }

    async login() {
        const
            plaintextPassword = this.input(this.password).value,
            password = await Password.hash(plaintextPassword).remember()

        this.output(this.password, base16.encode(await password.hash))
    }
}
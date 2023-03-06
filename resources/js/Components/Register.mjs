import Keypad from "../Keypad.mjs";
import Fieldset from "./Fieldset.mjs";
import Password from "../Password.mjs";

import base16 from "../Util/base16.mjs";

export default class Register extends Fieldset {
    constructor(node, { password }) {
        super(node)

        this.password = password

        this.onSubmit(this.register)
    }

    async register() {
        const
            plaintextPassword = this.input(this.password).value,
            password = await Password.hash(plaintextPassword).remember()

        const
            keypad = await Keypad.generate(),
            exported = await keypad.export(password),
            json = JSON.stringify(exported)

        this.output('_keypad', json)
    }
}
import Keypad from "../Keypad.mjs";
import Fieldset from "./Fieldset.mjs";
import Password from "../Password.mjs";

export default class Register extends Fieldset {
    constructor(node, { password }) {
        super(node)

        this.password = password

        this.onSubmit(this.register)
    }

    async register() {
        const
            plaintextPassword = this.input(this.password).value,
            password = await Password.hash(plaintextPassword).remember(),
            keypad = await Keypad.generate(),
            exported = await keypad.export(password),
            json = JSON.stringify(exported)

        this.output('_keypad', json)
    }
}
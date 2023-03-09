import Fieldset from "./Fieldset.mjs";
import Keypad from "../Keypad.mjs";
import Password from "../Password.mjs";

export default class ChangePassword extends Fieldset {
    constructor(node, { password }) {
        super(node)

        this.password = password

        this.onSubmit(this.changePassword)
    }

    async changePassword() {
        const
            keypad = await Keypad.resolve(),
            plaintextPassword = this.input(this.password).value

        const
            password = await Password.hash(plaintextPassword).remember({ append: true }),
            exported = await keypad.export(password),
            json = JSON.stringify(exported)

        this.output('_keypad', json)
    }
}
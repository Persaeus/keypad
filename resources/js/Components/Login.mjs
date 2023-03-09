import Fieldset from "./Fieldset.mjs";
import Password from "../Password.mjs";

export default class Login extends Fieldset {
    constructor(node, { password }) {
        super(node)

        this.password = password

        this.onSubmit(this.login)
    }

    async login() {
        const plaintextPassword = this.input(this.password).value

        await Password.hash(plaintextPassword).remember()
    }
}
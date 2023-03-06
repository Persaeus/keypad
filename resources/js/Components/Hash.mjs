import Fieldset from "./Fieldset.mjs";
import Password from "../Password.mjs";

import base16 from "../Util/base16.mjs";

export default class Hash extends Fieldset {
    constructor(node, { target }) {
        super(node)

        this.target = target

        this.onSubmit(this.hash)
    }

    async hash() {
        const
            message = this.input(this.target).value,
            password = Password.hash(message)

        this.output(this.target, base16.encode(await password.hash))
    }
}
import Fieldset from "./Fieldset.mjs";

export default class Encrypt extends Fieldset {
    constructor(node, { input, key }) {
        super(node)

        this.input = input

        this.publicKey = key

        this.onSubmit(this.encrypt)
    }

    encrypt() {
        // 
    }
}
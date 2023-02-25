import Cipher from "../Cipher.mjs";
import Component from "./Component.mjs";

export default class Decrypt extends Component {
    constructor(node, { cipher }) {
        super(node)

        this.decrypt(cipher)
    }

    async decrypt(data) {
        // 
    }
}
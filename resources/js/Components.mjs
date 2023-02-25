import Component from "./Components/Component.mjs"
import Decrypt from "./Components/Decrypt.mjs"
import Encrypt from "./Components/Encrypt.mjs"
import Login from "./Components/Login.mjs"
import Register from "./Components/Register.mjs"

/**
 * Class responsible for mapping and loading the cipher components.
 */
export default class Components {
    /**
     * @type {Object.<string, typeof Component>}
     */
    static types = {
        decrypt: Decrypt,
        encrypt: Encrypt,
        login: Login,
        register: Register,
    }

    /**
     * Load components on the page.
     */
    static load() {
        document.querySelectorAll('[data-cipher-component]').forEach(node => {
            const
                name = node.dataset.cipherComponent,
                type = this.types[name],
                attributes = JSON.parse(node.dataset.cipherAttributes),
                component = new type(node, attributes)

            return component
        })
    }
}
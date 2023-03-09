import ChangePassword from "./Components/ChangePassword.mjs"
import Component from "./Components/Component.mjs"
import Decrypt from "./Components/Decrypt.mjs"
import Encrypt from "./Components/Encrypt.mjs"
import Hash from "./Components/Hash.mjs"
import Login from "./Components/Login.mjs"
import Register from "./Components/Register.mjs"

/**
 * Class responsible for mapping and loading the keypad components.
 */
export default class Components {
    /**
     * @type {Object.<string, typeof Component>}
     */
    static types = {
        'change-password': ChangePassword,
        decrypt: Decrypt,
        encrypt: Encrypt,
        hash: Hash,
        login: Login,
        register: Register,
    }

    /**
     * Load components on the page.
     */
    static load() {
        document.querySelectorAll('[data-keypad-component]').forEach(node => {
            const
                name = node.dataset.keypadComponent,
                type = this.types[name],
                attributes = JSON.parse(node.dataset.keypadAttributes),
                component = new type(node, attributes)

            return component
        })
    }
}
import Component from "./Component.mjs";
import Event from "../Event.mjs";

/**
 * @abstract
 */
export default class FormControl extends Component {
    /** @type {HTMLFormElement} */
    get form() {
        return this.node.closest('form')
    }

    /**
     * Intercept the submit event for the component's form using the given callback.
     * 
     * @param {(event: Event) => any} callback 
     * @returns {void}
     */
    onSubmit(callback) {
        if (!this.form) {
            throw new Error(`Keypad component "${this.constructor.name.toLowerCase()}" must be inside <form> element to intercept submit.`)
        }

        // Wrap the callback so it only runs if the node remains connected when the event is triggered,
        // as the associated component may have been disconnected after the listener was registered.
        const connectedCallback = event => this.node.isConnected && callback.apply(this, event)

        Event.intercept('submit')
            .on(this.form)
            .then(connectedCallback)
            .finally(() => this.form.submit())
    }
}
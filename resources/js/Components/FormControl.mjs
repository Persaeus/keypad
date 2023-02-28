import Component from "./Component.mjs";
import Event from "../Event.mjs";

/**
 * @abstract
 */
export default class FormControl extends Component {
    /** @type {HTMLFormElement} */
    get form() {
        return this.node.form
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

        Event.intercept('submit')
            .on(this.form)
            .then(callback.bind(this))
            .finally(() => this.form.submit())
    }
}
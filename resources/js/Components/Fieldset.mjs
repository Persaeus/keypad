import FormControl from "./FormControl.mjs";

/**
 * @abstract
 * @property {HTMLFieldSetElement} node
 */
export default class Fieldset extends FormControl {
    /**
     * Get the form input element with given name.
     * 
     * @param {string} name 
     * @returns 
     */
    input(name) {
        const
            attribute = 'data-keypad-input',
            items = this.#inputs(name)

        items.forEach(item => item.setAttribute(attribute, name))

        return this.form.querySelector(`[${attribute}='${name}']`)
    }

    /**
     * Get all the form control elements with the given name.
     * 
     * @param {string} name 
     * @returns {HTMLElement[]}
     */
    #inputs(name) {
        const
            item = this.form.elements.namedItem(name),
            items = typeof item[Symbol.iterator] === 'function'
                ? [...item]
                : [item]

        return items.filter(item => !this.node.contains(item))
    }

    /**
     * Append to fieldset 'output' field of given "name" and "value".
     * 
     * @param {string} name 
     * @param {string} value 
     * @param {boolean} overwrite
     * @returns 
     */
    output(name, value, overwrite = true) {
        const output = this.node.elements.namedItem(name) ?? document.createElement('input')

        output.type = 'hidden'
        output.name = name
        output.value = value

        if (!this.node.contains(output)) {
            this.node.appendChild(output)
        }

        if (overwrite) {
            this.#inputs(name).forEach(item => item.removeAttribute('name'))
        }

        return output
    }
}
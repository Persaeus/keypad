export default class Script {
    static #script = document.currentScript

    static data = this.#script.getAttribute('data')

    static event = this.#script.getAttribute('event')

    static salt = this.#script.getAttribute('salt')

    static storage = this.#script.getAttribute('storage')
}
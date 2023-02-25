import Script from "./Script.mjs"

/**
 * Class responsible for managing events.
 */
export default class Event {
    /**
     * The name of the event that will be dispatched at the end of
     * an event interception cycle and, unless stopped, will bubble
     * to the root and trigger the "final" callback.
     */
    static event = Script.event

    /** 
     * The event targets and their respective callback stacks, grouped by event type.
     * 
     * @type {Object<string, WeakMap<EventTarget, {callbacks: ((event: Event) => any)[], final?: (event: Event) => any}>>}
     */
    static targets = {}

    /**
     * Fluently begin constructing a new event interception definition.
     * 
     * @param {string} event 
     * @returns 
     */
    static intercept(event) {
        return new class {
            /**
             * Set the event target.
             * 
             * @param {EventTarget} target 
             * @returns {this}
             */
            on(target) {
                const targets = Event.targets[event] ??= new WeakMap

                if (!targets.has(target)) {
                    targets.set(target, {
                        callbacks: []
                    })

                    Event.#listen(event, target)
                }

                this.target = targets.get(target)

                return this
            }

            /**
             * Add a callback to be run while intercepting event.
             * 
             * @param {(event: Event) => any} callback 
             * @returns 
             */
            then(callback) {
                this.target.callbacks.push(callback)

                return this
            }

            /**
             * Set the callback to be run at the end once.
             * 
             * @param {(event: Event) => any} callback 
             */
            finally(callback) {
                this.target.final = callback
            }
        }
    }

    /**
     * Add the event listener for the given event type and target.
     * 
     * @param {string} type
     * @param {EventTarget} target 
     */
    static #listen(type, target) {
        target.addEventListener(type, async event => {
            event.preventDefault()

            const
                targets = this.targets[type],
                listener = targets.get(target),
                callbacks = listener.callbacks,
                promises = callbacks.map(callback => callback())

            await Promise.all(promises)

            target.dispatchEvent(new CustomEvent(this.event, {
                bubbles: true,
                detail: { type }
            }))
        })

        document.addEventListener(this.event, event => {
            if (
                event.target != target ||
                event.detail.type != type
            ) {
                return
            }

            this.targets[type].get(target).final(event)
        })
    }
}
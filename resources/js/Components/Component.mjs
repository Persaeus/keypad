/**
 * @abstract
 */
export default class Component {
    /**
     * @param {HTMLElement} node 
     * @param {Object} _context 
     */
    constructor(node, _context) {
        /**
         * @property {HTMLElement} node
         */
        this.node = node
    }
}
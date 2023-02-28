import terser from "@rollup/plugin-terser";

export default {
    input: 'resources/js/index.js',
    output: {
        format: 'umd',
        sourcemap: true,
        name: 'Keypad',
        file: 'resources/dist/keypad.js',
    },
    plugins: [terser()]
}

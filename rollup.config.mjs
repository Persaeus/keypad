import terser from "@rollup/plugin-terser";

export default {
    input: 'resources/js/index.js',
    output: {
        format: 'umd',
        sourcemap: true,
        name: 'Cipher',
        file: 'resources/dist/cipher.js',
    },
    plugins: [terser()]
}

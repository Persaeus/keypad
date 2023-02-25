export default function encode(value) {
    return new TextEncoder().encode(value)
}
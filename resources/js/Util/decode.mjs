export default function decode(value) {
    return new TextDecoder().decode(value)
}
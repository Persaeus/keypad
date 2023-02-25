export default function sha256(data) {
    return crypto.subtle.digest(
        'SHA-256',
        data
    )
}
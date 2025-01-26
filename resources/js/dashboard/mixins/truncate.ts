export default function truncate(text: string, length: number, suffix = '...'): string {
    return text.slice(0, length) + (length < text.length ? suffix : '');
}

export function formatTimeToMin(time) {
    if (!time) return '—'
    const [h, m] = time.split(':').map(Number)
    if (h > 0) return m > 0 ? `${h} h ${m}` : `${h} h`
    return `${m} min`
}

export function formatToSqlTime(minutes) {
    const h = Math.floor(minutes / 60), m = minutes % 60
    return [h, m, 0].map(n => String(n).padStart(2, '0')).join(':')
}
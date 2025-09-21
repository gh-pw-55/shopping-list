export const formatPrice = (price: number): string => {
    const priceInPence = price / 100;
    return `£${priceInPence.toFixed(2)}`
}

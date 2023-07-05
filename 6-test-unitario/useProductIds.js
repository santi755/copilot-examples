export function useProductIds() {
  /**
   * @return {String} reference, Example: "102705"
   */
  const getProductId = (product) => {
    const reference = product.reference;

    return reference;
  };

  /**
   * @return {String} reference-colorId, Example: "102705-20"
   */
  const getProductColorId = (product) => {
    const reference = product.reference;
    const [color] = product.colors;

    return `${reference}-${color.id}`;
  };

  /**
   * Get all the reference-color combination for product, if product have 2 colors, the result will have 2 elements
   *
   * @return {Array} of (reference-colorId), Example: ["102705-20", "102705-30"]
   */
  const getProductColorIds = (product) => {
    const reference = product.reference;

    return product.colors.map((color) => `${reference}-${color.id}`);
  };

  return {
    getProductId,
    getProductColorId,
    getProductColorIds,
  };
}

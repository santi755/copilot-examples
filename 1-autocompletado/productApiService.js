import ApiService from "@/plugins/axiosIntegration";

class ProductApiService {
  /**
   * Get a product by its reference
   *
   * @param {String} reference
   * @param {String} priceGroupId
   * @param {Object} params
   *
   * @returns {Promise}
   */
  getProduct(reference, priceGroupId, params = {}) {
    return ApiService.get(
      `/price-groups/${priceGroupId}/products/${reference}`,
      {
        params: params,
      }
    );
  }

  getProductByColorId;
}

export default new ProductApiService();

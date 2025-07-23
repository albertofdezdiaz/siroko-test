Feature:
  I want to make sure that the API allow to remove items from a cart

  Scenario: As an user, i want to remove an item from a cart
    Given an active cart with id "a-cart-id" exists
    Given an item "a-product" of quantity 3 is added to "a-cart-id"
    When I send a PUT request to "/api/carts/a-cart-id/remove-item" with body:
    """
    {
      "productId": "a-product"
    }
    """
    Then the status code should be 200
    And the response JSON should be equal to:
    """
    {
      "cartId": "a-cart-id",
      "productId": "a-product",
      "quantity": 0
    }
    """
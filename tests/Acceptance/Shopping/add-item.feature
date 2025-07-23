Feature:
  I want to make sure that the API allow to add items to a cart

  Scenario: As an user, i want to add item to a cart
    Given an active cart with id "a-cart-id" exists
    When I send a PUT request to "/api/carts/a-cart-id/add-item" with body:
    """
    {
      "productId": "a-product",
      "quantity": 2
    }
    """
    Then the status code should be 200
    And the response JSON should be similar to:
    """
    {
      "cartId": "a-cart-id",
      "productId": "a-product",
      "quantity": 2
    }
    """
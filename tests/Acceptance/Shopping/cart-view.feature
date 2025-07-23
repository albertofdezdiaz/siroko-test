Feature:
  I want to make sure that the API allow to get items from a cart

  Scenario: As an user, i want to view items from a cart
    Given an active cart with id "a-cart-id" exists
    Given an item "a-product" of quantity 3 is added to "a-cart-id"
    When I send a GET request to "/api/carts/a-cart-id"
    Then the status code should be 200
    And the response JSON should be similar to:
    """
    {
      "cartId": "a-cart-id",
      "items": [
        {
          "productId": "a-product",
          "quantity": 3
        }
      ]
    }
    """
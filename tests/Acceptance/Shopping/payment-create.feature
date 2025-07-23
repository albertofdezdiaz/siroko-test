Feature:
  I want to make sure that the API allow to create a payment for an active cart

  Scenario: As an user, i want to pay for my cart
    Given an active cart with id "a-cart-id" exists
    Given an item "a-product" of quantity 3 is added to "a-cart-id"
    When I send a POST request to "/api/payments" with body: 
    """
    {
      "cartId": "a-cart-id"
    }
    """
    Then the status code should be 200
    And the response JSON should be similar to:
    """
    {
      "cartId": "a-cart-id",
      "paymentId": "8de87304-f72e-4a62-bca9-313b27d068f3"
    }
    """
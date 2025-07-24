Feature:
  To simulate external provider payment, i want to "pay" via API

  Scenario: As an user, i want to pay for my pending payment
    Given an active cart with id "a-cart-id" exists
    Given an item "a-product" of quantity 3 is added to "a-cart-id"
    Given a payment for "a-cart-id" with id "a-payment-id" exists
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
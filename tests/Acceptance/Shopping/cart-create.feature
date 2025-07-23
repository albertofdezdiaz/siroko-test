Feature:
  I want to make sure that the API allow to create a cart

  Scenario: As an user, i want to create a new cart
    When I send a POST request to "/api/carts"
    Then the status code should be 200
    And the response JSON should be similar to:
    """
    {
        "cartId": "8de87304-f72e-4a62-bca9-313b27d068f3"
    }
    """
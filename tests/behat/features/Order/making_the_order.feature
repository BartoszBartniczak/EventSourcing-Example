Feature: Making the order
  In order to buy products
  As a customer
  I want to be able to create an order

  Scenario: Making the order from the basket
    Given There is a Basket with ID: "bfaad97d-6042-4186-ba75-692fce3e73ae"
    And Basket has position with Product with ID: "2925d7e0-1266-44fb-b902-80bc7076896e" in an amount "2.0" pieces
    And Basket has position with Product with ID: "f31a23e2-fc1e-456d-8eed-4063f97efb5f" in an amount "1.8" pieces
    When I create the order
    Then Order should have "2" positions
    And Order should have position with Product ID: "2925d7e0-1266-44fb-b902-80bc7076896e" in an amount "2.0" pieces
    And Order should have position with Product ID: "f31a23e2-fc1e-456d-8eed-4063f97efb5f" in an amount "1.8" pieces
    And Basket should be closed
    And Email with the order should be send



Feature: Removing position from the basket
  In order to buy products
  As a customer
  I need to be able to remove position from the basket

  Scenario: Removing position from the basket
    Given Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0"
    And Basket with Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" in an amount of "1.3" pieces
    When I remove position with product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0"
    Then I should have 0 positions in the Basket
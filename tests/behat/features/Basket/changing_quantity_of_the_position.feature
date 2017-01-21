Feature: Changing quantity of the position in the basket
  In order to buy products
  As a customer
  I need to be able to change quantity of the product that I already added to the basket

  Scenario: Changing quantity of the product
    Given Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0"
    And Basket with Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" in an amount of "1.3" pieces
    When I change quantity of the Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" to "0.5"
    Then I should have 1 position in the Basket
    And product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" and in amount of "0.5"

  Scenario: Changing quantity of the product to the zero
    Given Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0"
    And Basket with Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" in an amount of "1.3" pieces
    When I change quantity of the Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" to "0.0"
    Then I should have 0 positions in the Basket
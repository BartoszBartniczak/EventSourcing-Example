Feature: Adding product to the basket
  In order to buy products
  As a customer
  I need to be able to add product to the basket

  Scenario: Adding single product to the empty basket
    Given Empty basket
    And Product with ID: "f0f9db24-5f87-4240-a165-b924b397ae30"
    When I add to the basket, product with ID: "f0f9db24-5f87-4240-a165-b924b397ae30" in an amount of "1.0" piece
    Then I should have "1" position in the Basket
    And product with ID: "f0f9db24-5f87-4240-a165-b924b397ae30" and in amount of "1.0"

  Scenario: Adding two products to the empty basket
    Given Empty basket
    And Product with ID: "f0f9db24-5f87-4240-a165-b924b397ae30"
    And Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0"
    When I add to the basket, product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" in an amount of "2.0" pieces
    And I add to the basket, product with ID: "f0f9db24-5f87-4240-a165-b924b397ae30" in an amount of "1.4" pieces
    Then I should have 2 positions in the Basket
    And product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" and in amount of "2.0"
    And product with ID: "f0f9db24-5f87-4240-a165-b924b397ae30" and in amount of "1.4"


  Scenario: Adding this same product to the basket
    Given Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0"
    And Basket with Product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" in an amount of "1.3" pieces
    When I add to the basket, product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" in an amount of "1.4" pieces
    Then I should have 1 position in the Basket
    And product with ID: "d1e6527b-04a2-4da2-a66f-af22c2aedac0" and in amount of "2.7"


#TODO: Scenario: Adding product to the closed basket

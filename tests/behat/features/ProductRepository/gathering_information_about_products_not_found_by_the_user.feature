Business Need: Gathering information about products not found by the user
  In order to deliver the best product recommendations
  As our company
  We want to store information about product which the user was trying to find, but could not.

  Scenario: Looking for product that does not exist
    Given User identified by email "user@email.com"
    And Empty repository
    When User is trying to find product called "Super Extra Milk"
    Then System should store information about unsuccessful searching



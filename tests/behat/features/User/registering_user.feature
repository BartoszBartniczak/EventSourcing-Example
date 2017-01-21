Feature: Registering user
  In order to register new customer
  As a unregistered client
  I want to be able to register in service

  Scenario: Registering user
    Given User email: "email@user.com"
    And User password "password"
    When I register in service
    Then The account should be registered in system
    And The account should be inactive
    And Registration token should be generated
    And Email with the activation token should be sent


#TODO: Scenario: Register same user
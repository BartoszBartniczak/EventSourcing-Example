Feature: Logging in
  In order to access the system
  As a customer
  I want to be able to log in

  Scenario: Logging in with correct email and password
    Given Active User account with email: "email@test.com" and password: "password"
    When I try to log in with parameters: "email@test.com" and "password"
    Then The fact of the logging in should be registered

  Scenario: Logging in with incorrect password
    Given Active User account with email: "email@test.com" and password: "password"
    When I try to log in with parameters: "email@test.com" and "pass"
    Then The fact of the unsuccessful attempt of logging in should be registered
    And The number of unsuccessful attempts of logging in should be equals "1"

  Scenario: Logging to the inactive account
    Given Inactive User account with email: "email@test.com" and password: "password"
    When I try to log in with parameters: "email@test.com" and "password"
    Then The fact of the unsuccessful attempt of logging in to the inactive account should be registered
    But The number of unsuccessful attempts of logging in should be equals "0"

  Scenario: Logging into a non-existent account
    Given Empty user repository
    When I try to log in with parameters: "email@test.com" and "pass"
    Then None event should be registered


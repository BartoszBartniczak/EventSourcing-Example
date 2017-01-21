Feature: Logging in
  In order to exit the system
  As the customer
  I want to be able to log out

  Scenario: Logging out
    Given Logged in user
    When I will try to log out
    Then The fact of the logging out should be registered
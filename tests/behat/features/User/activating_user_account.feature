Feature: Activating user account
  In order to activate user account
  As a registered user
  I want to be able to activate my account


  Scenario: Activating inactive account with valid token
    Given Inactive account with email: "test@email.com" and token: "ff5260c7-9f3d-4e6d-9a5c-6ed3eb39b4d8"
    When User is trying to activate the account with email: "test@email.com" and token: "ff5260c7-9f3d-4e6d-9a5c-6ed3eb39b4d8"
    Then Account should be activated

  Scenario: Activating inactive account with invalid token
    Given Inactive account with email: "test@email.com" and token: "ff5260c7-9f3d-4e6d-9a5c-6ed3eb39b4d8"
    When User is trying to activate the account with email: "test@email.com" and token: "wrong-token"
    Then Account should not be activated
    And Number of invalid attempts of activating the account should be equal: "1"

  Scenario: Activating already activated account
    Given Active account with email: "test@email.com" and token: "ff5260c7-9f3d-4e6d-9a5c-6ed3eb39b4d8"
    When User is trying to activate the account with email: "test@email.com" and token: "ff5260c7-9f3d-4e6d-9a5c-6ed3eb39b4d8"
    Then Account should be activated
    And Number of invalid attempts of activating the account should be equal: "0"
    But The attempt should be stored in system
Event Sourcing
==============
An example of using EventSourcing pattern in a real project. Implementation of this pattern may contains some flaws. 
-----------------------------------------------------------

This is an example of the online shop. 

### The scenario

1. User registers himself in the shop
2. Email is send to to the user's email
3. User accepts the invitation
4. User's account is activated
5. User logs in
5. User finds a product 'Milk'
6. User adds 2 pieces of milk to the basket
7. User finds a product 'Bread'
8. User adds 1 piece of bread to the basket
9. User finds a product 'Butter'
10. User adds 3 pieces of butter to the basket
11. User changes the quantity of the butter for 1
12. User removes bread from the basket
13. User logs out
14. User logs in
15. User tries to find a non-existent product called 'Cookies'
16. User creates the Order
17. Basket is closed
18. Send email with the order

### System capabilities
1. Command can cause the series of command executes. E.g. CloseOrderCommand calls CloseBasketCommand and SendEmailCommand.
2. We want to know what products users searched for and found none.

### TODO
1. Seperate the modules:
    1. Command Bus
    2. Event Sourcing
    3. JMSSerializer
    4. DBAL
    5. Example
    6. ExceptionTestCase
2. Unit tests
3. Behat tests
4. Integrity tests

### Tests

#### Unit Tests

To run unit tests run command:
```bash
php vendor/phpunit/phpunit/phpunit --configuration tests/unit-tests/configuration.xml
```

### Running the example

Run command in console:

```bash
php -S localhost:8000

```

Open the browser and type:

```
http://localhost:8000/example.php
```


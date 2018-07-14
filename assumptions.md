## ASSUMPTIONS
  1. Customer must register to buy the ticket
  2. The location in createEvent function is predefined. So that only the id will be pass as a parameter
  3. Format of the date is YYYY-MM-DD or YYYYMMDD
  4. User does not need to login to make transaction
  5. Additional function is created to create customer: createCustomer

## Tables:
  1. Table event has:
    - id
    - location_id
    - name
    - age_limit: default value of 0
    - date_start
    - date_finish
  2. Table Location has:
    - id
    - name
    - city
    - country
  3. Table Ticket has:
    - id
    - event_id
    - price
    - quota
  4. Table Transaction has:
    - id
    - customer_id
    - ticket_id
    - quantity
    - uid
  5. Table Customer has:
    - id
    - name
    - birth_date
    - city
    - address
    - phone
    - email

## Functions and Parameters:
  1. createEvent
    - **Parameter:**
      - event_name
      - event_age_limit (optional)
      - location_id
      - event_date_start
      - event_date_finish (optional)
  2. createLocation
    - **Parameter:**
      - location_name
      - location_city
      - location_country
  3. createTicket
    - **Parameter:**
      - event_id
      - ticket_price
      - ticket_quota
  4. getEvent
    - **Parameter:**
      - event_id
    - **Output:**
      - name
      - age_limit
      - date_start
      - date_finish
      - location :
        - name
        - city
        - country
  5. purchaseTicket
    - **Parameter:**
      - customer_id
      - transaction_count: the number of distinct ticket type
      - transaction_ticket_id_(1..transaction_count)
      - transaction_ticket_id_(1..transaction_count)
  6. getTransactionDetail
    - **Parameter:**
      - transaction_id
    - **Output:**
      - [counter]
        - customer_id
        - ticket_id
        - quantity
        - uid
        - ticket :
          - event_id
          - price
        - event :
          - name
          - age_limit
          - date_start
          - date_finish
        - location:
          - name
          - city
          - country
        - subtotal
      -total
  7. createCustomer
    - **Parameter:**
      - customer_name
      - customer_birth_date
      - customer_city
      - customer_address
      - customer_phone
      - customer_email

Tables Assumptions:
  1. Table event has:
    - id
    - location_id
    - schedule_id
    - name
    - age_limit
  2. Table Location has:
    - id
    - name
    - city
    - country
  3. Table Schedule has:
    - id
    - date_start
    - date_end
  4. Table Ticket has:
    - id
    - event_id
    - price
    - quota
  5. Table Transaction has:
    - id
    - customer_id
    - event_id
    - quantity
  6. Table Customer has:
    - name
    - birth_date
    - city
    - address
    - phone
    - email
  7. Table Transaction_ticket_type has:
    - id
    - transaction_id
    - ticket_type_id

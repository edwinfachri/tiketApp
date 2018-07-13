Tables Assumptions:
  1. Table event has:
    - id
    - location_id
    - name
    - age_limit
    - date_start
    - date_end
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
    - name
    - birth_date
    - city
    - address
    - phone
    - email

Controller:
  1. createEvent
    Parameter:
      - event_name
      - event_age_limit (optional)
      - location_name
      - location_city
      - location_country
      - event_date_start
      - event_date_finish (optional)
  2. createLocation
    Parameter:
      - location_name
      - location_city
      - location_country
  3. createTicket
    Parameter:
      - event_id
      - ticket_price
      - ticket_quota
  4. getEvent
    Parameter:
      - event_id

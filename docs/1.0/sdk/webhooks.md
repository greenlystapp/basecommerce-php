# Webhooks / Push Notifications

## Sample Decrypted Push Notifications

### Settlement Batch
```json
{
  "push_notification_http_response_code": 0,
  "push_notification_settlement_batch": {
    "settlement_batch_bank_card_transaction_batch_id": 0,
    "settlement_batch_bank_account_transaction_debit_count": 3,
    "settlement_batch_bank_card_transaction_credit_count": 0,
    "settlement_batch_bank_card_transaction_sale_amount": 0,
    "settlement_batch_bank_account_transaction_credit_amount": 100.25,
    "settlement_batch_bank_account_transaction_credit_count": 2,
    "settlement_batch_bank_account_transaction_debit_amount": 300.5,
    "settlement_batch_bank_account_transaction_ids": [
      1,
      2,
      3,
      4,
      5
    ],
    "settlement_batch_bank_card_transaction_credit_amount": 0,
    "settlement_batch_bank_account_transaction_batch_id": 123,
    "settlement_batch_bank_card_transaction_sale_count": 0
  },
  "push_notification_id": 61896,
  "push_notification_delivery_time": "09/18/2019 05:03:09",
  "merchant_organization_id": 3338,
  "push_notification_delivered_time": "09/18/2019 09:03:09",
  "push_notification_delivery_attempts": "1",
  "push_notification_status": {
    "push_notification_status_name": "NEW",
    "push_notification_status_description": "New Notification"
  },
  "push_notification_delivery_url": "https://enw7bx2pp9a9.x.pipedream.net",
  "push_notification_type": "SETTLEMENT BATCH CHANGE",
  "push_notification_creation_date": "09/18/2019 05:03:09"
}
```

### Incoming Chargeback

```json
{
  "push_notification_http_response_code": 0,
  "push_notification_id": 61900,
  "push_notification_delivery_time": "09/18/2019 05:03:09",
  "merchant_organization_id": 3338,
  "push_notification_delivered_time": "09/18/2019 09:03:09",
  "push_notification_chargeback": {
    "chargeback_common_record1": "common_record_1",
    "chargeback_common_record2": "common_record_2",
    "bank_card_transaction_id": "0",
    "chargeback_received_date": "09/18/2019 17:03:09",
    "chargeback_mid": "00000000001",
    "merchant_organization_id": 3338,
    "chargeback_authorization_code": "A",
    "chargeback_transaction_acquirer_reference_number": "1234",
    "chargeback_transaction_amount": 10,
    "chargeback_card_number": "411111",
    "chargeback_status": {
      "chargeback_status_name": "INCOMING",
      "chargeback_status_description": "Incoming Chargeback"
    },
    "chargeback_reason_code": "001"
  },
  "push_notification_delivery_attempts": "1",
  "push_notification_status": {
    "push_notification_status_name": "NEW",
    "push_notification_status_description": "New Notification"
  },
  "push_notification_delivery_url": "https://enw7bx2pp9a9.x.pipedream.net",
  "push_notification_type": "INCOMING CHARGEBACK",
  "push_notification_creation_date": "09/18/2019 05:03:09"
}
```

### Merlin Chargeback

```json
{
  "push_notification_merlin_chargeback": {
    "merlin_chargeback_resolved_to": "B",
    "merlin_chargeback_case_type": 0,
    "merlin_chargeback_merchant_number": "00000000001",
    "merlin_chargeback_load_date": "2019-09-18",
    "merlin_chargeback_gl_amount_one": 15,
    "merlin_chargeback_bank_comment": "This is the comment sent to the bank.",
    "merlin_chargeback_received_date": "2019-09-18",
    "merlin_chargeback_reason_code": "01234",
    "merlin_chargeback_case_number": "2019001000001",
    "merlin_chargeback_gl_comment_one": "This is the comment sent to the GL account.",
    "bank_card_transaction_id": 0,
    "merlin_chargeback_acquirer_reference_number": "12345678901234567890123",
    "merlin_chargeback_amount": 15,
    "merlin_chargeback_issuer_reference_number": "00000000000000001",
    "merlin_chargeback_member_message_block": "This is the issuer's comment on this chargeback.",
    "merlin_chargeback_authorization_code": "ABCD1234",
    "merlin_chargeback_resolved_date": "2019-09-18",
    "merlin_chargeback_merchant_comment": "This is the comment sent to the merchant.",
    "merlin_chargeback_request_id": "abcd-1234567890",
    "merlin_chargeback_record_type": " ",
    "merlin_chargeback_merchant_category_code": 1111,
    "merlin_chargeback_card_brand": 1,
    "merlin_chargeback_bank_amount": -1,
    "merlin_chargeback_transaction_code": 5,
    "merlin_chargeback_transaction_date": "2019-09-18",
    "merlin_chargeback_processor": 1,
    "merlin_chargeback_document_indicator": "1",
    "merlin_chargeback_family_id": "ABC-DEF-123-456-7890",
    "merlin_chargeback_cardholder_account": "",
    "merlin_chargeback_post_date": "2019-09-18",
    "merlin_chargeback_merchant_amount": 15
  },
  "push_notification_http_response_code": 0,
  "push_notification_id": 61902,
  "push_notification_delivery_time": "09/18/2019 05:03:09",
  "merchant_organization_id": 3338,
  "push_notification_delivered_time": "09/18/2019 09:03:09",
  "push_notification_delivery_attempts": "1",
  "push_notification_status": {
    "push_notification_status_name": "NEW",
    "push_notification_status_description": "New Notification"
  },
  "push_notification_delivery_url": "https://enw7bx2pp9a9.x.pipedream.net",
  "push_notification_type": "INCOMING MERLIN CHARGEBACK",
  "push_notification_creation_date": "09/18/2019 05:03:09"
}
```

### Bank Card Transaction

```json
{
  "push_notification_bank_card_transaction": {
    "bank_card_transaction_name": "Test Name",
    "bank_card_transaction_id": 0,
    "bank_card_transaction_status": {
      "bank_card_transaction_status_name": "CAPTURED",
      "bank_card_transaction_status_description": "Captured Transaction"
    },
    "bank_card_transaction_response_code": "0000",
    "bank_card_transaction_authorization_date": "09/18/2019 17:03:09",
    "bank_card_transaction_merchant_transaction_id": "0",
    "bank_card_transaction_custom_field1": "This is a PushNotification test transaction.",
    "bank_card_transaction_amount": 30.9,
    "bank_card_transaction_tip_amount": 0,
    "bank_card_transaction_card_number": "411111",
    "bank_card_transaction_expiration_month": "01",
    "bank_card_transaction_settlement_date_24hr": "09/18/2019 05:03:09",
    "bank_card_transaction_tax_amount": 0,
    "bank_card_transaction_response_message": "Approved",
    "bank_card_transaction_expiration_year": "2020",
    "bank_card_transaction_type": {
      "bank_card_transaction_type_name": "SALE",
      "bank_card_transaction_type_description": "Authorization & Capture"
    },
    "bank_card_transaction_creation_date_24hr": "09/18/2019 05:03:09"
  },
  "push_notification_http_response_code": 0,
  "push_notification_id": 61906,
  "push_notification_delivery_time": "09/18/2019 05:03:09",
  "merchant_organization_id": 3338,
  "push_notification_delivered_time": "09/18/2019 09:03:09",
  "push_notification_delivery_attempts": "1",
  "push_notification_status": {
    "push_notification_status_name": "NEW",
    "push_notification_status_description": "New Notification"
  },
  "push_notification_delivery_url": "https://enw7bx2pp9a9.x.pipedream.net",
  "push_notification_type": "BANK CARD TRANSACTION",
  "push_notification_creation_date": "09/18/2019 05:03:09"
}
```

### ACH Change

```json
{
  "push_notification_http_response_code": 0,
  "push_notification_id": 61904,
  "push_notification_delivery_time": "09/18/2019 05:03:09",
  "merchant_organization_id": 3338,
  "push_notification_delivered_time": "09/18/2019 09:03:09",
  "push_notification_delivery_attempts": "1",
  "push_notification_status": {
    "push_notification_status_name": "NEW",
    "push_notification_status_description": "New Notification"
  },
  "push_notification_delivery_url": "https://enw7bx2pp9a9.x.pipedream.net",
  "push_notification_bank_account_transaction": {
    "bank_account_transaction_routing_number": "111000025",
    "bank_account_transaction_status": {
      "bank_account_transaction_status_name": "CREATED",
      "bank_account_transaction_status_description": "Created Transaction"
    },
    "bank_account_transaction_check_number": "12344",
    "bank_account_transaction_net_amount": -20,
    "bank_account_transaction_type": {
      "bank_account_transaction_type_name": "DEBIT",
      "bank_account_transaction_type_description": "Debit Transaction"
    },
    "bank_account_transaction_billing_address": {
      "address_line1": "123 Test Lane",
      "address_country": "US",
      "address_zipcode": "12345",
      "address_state": "AZ",
      "address_name": "BILLING",
      "address_city": "City"
    },
    "bank_account_transaction_return_date_24hr": "09/18/2019 00:00:00",
    "bank_account_transaction_settlement_date_24hr": "09/18/2019 00:00:00",
    "bank_account_transaction_account_type": {
      "bank_account_transaction_account_type_name": "CHECKING",
      "bank_account_transaction_account_type_description": "Checking Account"
    },
    "bank_account_transaction_custom_field1": "This is a PushNotification test transaction.",
    "bank_account_transaction_effective_date_24hr": "09/18/2019 00:00:00",
    "bank_account_transaction_settlement_date": "09/18/2019 12:00:00",
    "bank_account_transaction_method": {
      "bank_account_transaction_method_name": "CCD",
      "bank_account_transaction_method_description": "ccd method"
    },
    "bank_account_transaction_account_number": "111222333444",
    "bank_account_transaction_effective_date": "09/18/2019 12:00:00",
    "bank_account_transaction_id": 0,
    "bank_account_transaction_amount": 20,
    "bank_account_transaction_account_name": "Test",
    "bank_account_transaction_merchant_transaction_id": "0",
    "bank_account_transaction_return_code": "R01",
    "bank_account_transaction_return_date": "09/18/2019 12:00:00"
  },
  "push_notification_type": "ACH CHANGE",
  "push_notification_creation_date": "09/18/2019 05:03:09"
}
```

### Bank Card Update

```json
{
  "push_notification_bank_card_update": {
    "bank_card_update_original_number": "4111111111111111",
    "bank_card_update_original_expiration_month": "12",
    "bank_card_update_updated_expiration_month": "11",
    "bank_card_update_updated_expiration_year": "2019",
    "bank_card_update_status": {
      "bank_card_update_status_description": "New",
      "bank_card_update_status_name": "NEW"
    },
    "bank_card_update_original_expiration_year": "2018",
    "bank_card_update_updated_number": "4313076126603673"
  },
  "push_notification_http_response_code": 0,
  "push_notification_id": 61908,
  "push_notification_delivery_time": "09/18/2019 05:03:09",
  "merchant_organization_id": 3338,
  "push_notification_delivered_time": "09/18/2019 09:03:09",
  "push_notification_delivery_attempts": "1",
  "push_notification_status": {
    "push_notification_status_name": "NEW",
    "push_notification_status_description": "New Notification"
  },
  "push_notification_delivery_url": "https://enw7bx2pp9a9.x.pipedream.net",
  "push_notification_type": "BANK CARD UPDATE",
  "push_notification_creation_date": "09/18/2019 05:03:09"
}
```

### Settlement Batch

```json
{
  "push_notification_http_response_code": 0,
  "push_notification_settlement_batch": {
    "settlement_batch_bank_card_transaction_ids": [
      1,
      2,
      3,
      4,
      5
    ],
    "settlement_batch_bank_card_transaction_batch_id": 123,
    "settlement_batch_bank_account_transaction_debit_count": 0,
    "settlement_batch_bank_card_transaction_credit_count": 2,
    "settlement_batch_bank_card_transaction_sale_amount": 234.56,
    "settlement_batch_bank_account_transaction_credit_amount": 0,
    "settlement_batch_bank_account_transaction_credit_count": 0,
    "settlement_batch_bank_account_transaction_debit_amount": 0,
    "settlement_batch_bank_card_transaction_credit_amount": 69.25,
    "settlement_batch_bank_account_transaction_batch_id": 0,
    "settlement_batch_bank_card_transaction_sale_count": 3
  },
  "push_notification_id": 61898,
  "push_notification_delivery_time": "09/18/2019 05:03:09",
  "merchant_organization_id": 3338,
  "push_notification_delivered_time": "09/18/2019 09:03:09",
  "push_notification_delivery_attempts": "1",
  "push_notification_status": {
    "push_notification_status_name": "NEW",
    "push_notification_status_description": "New Notification"
  },
  "push_notification_delivery_url": "https://enw7bx2pp9a9.x.pipedream.net",
  "push_notification_type": "SETTLEMENT BATCH CHANGE",
  "push_notification_creation_date": "09/18/2019 05:03:09"
} 
```
<?php

namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;

class CashFlowRepository extends Repository
{

 public function __construct(BankInvoiceDetail $model)
 {
  $this->model = $model;
 }

 /**
  * get cash flow when periodFrom between periodTo in past
  * @param  string $periodFrom   period from (yyyy-mm-dd)
  * @param  string $periodTo     period to (yyyy-mm-dd)
  * @param  array $bankAccountId ids of bankAccount
  * @return array
  */
 public static function past($periodFrom, $periodTo, $bankAccountId = [], $groupBy = 'date')
 {
  $companyId = \Auth::user()->company_id;
  $sql       = "
            SELECT `date` ,
                   initial_balance,
                   credit,
                   debit,
                   balance
            FROM
              (SELECT `date` ,
                      (balance - (credit + debit)) AS initial_balance ,
                      credit ,
                      debit ,
                      balance
               FROM
                 (SELECT `date` , credit , debit ,
                    (SELECT SUM(credit + debit)
                     FROM
                       (SELECT `date` ,
                               SUM(credit) AS credit ,
                               SUM(debit) AS debit
                        FROM
                          (SELECT due_date AS date ,
                                  SUM(IF(net_value < 0, net_value, 0)) AS debit ,
                                  SUM(IF(net_value >= 0, net_value, 0)) AS credit
                           FROM (

                                 SELECT IFNULL(receive_date, '1970-01-01') as due_date ,
                                        SUM(net_value) AS net_value
                                 FROM bank_invoice_detail BID
                                 JOIN bank_category BC ON BID.bank_category_id = BC.id
                                 WHERE BID.receive_date < '{$periodFrom}'
                                   AND BID.company_id = {$companyId}
                                   AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                   AND BID.deleted_at IS NULL
                                   AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                   AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                                 UNION
                                 SELECT payment_date ,
                                        SUM(net_value) AS net_value
                                 FROM bank_invoice_detail BID
                                 JOIN bank_category BC ON BID.bank_category_id = BC.id
                                 WHERE BID.payment_date < '{$periodFrom}'
                                   AND BID.company_id = {$companyId}
                                   AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                   AND BID.deleted_at IS NULL
                                   AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                   AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . ") initial_balance
                           UNION SELECT payment_date ,
                                        SUM(debit) AS debit ,
                                        0 credit
                           FROM (

                                 SELECT payment_date ,
                                        SUM(BID.net_value) AS debit
                                 FROM bank_invoice_detail BID
                                 JOIN bank_category BC ON BC.id = BID.bank_category_id
                                 WHERE payment_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                   AND BID.company_id = {$companyId}
                                   AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                   AND BID.deleted_at IS NULL
                                   AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                   AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                                 GROUP BY payment_date) bills_to_pay
                           GROUP BY payment_date
                           UNION SELECT receive_date ,
                                        0 debit ,
                                          SUM(credit) AS credit
                           FROM (-- contas a receber real --

                                 SELECT receive_date ,
                                        SUM(BID.net_value) AS credit
                                 FROM bank_invoice_detail BID
                                 JOIN bank_category BC ON BC.id = BID.bank_category_id
                                 WHERE receive_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                   AND BID.company_id = {$companyId}
                                   AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                   AND BID.deleted_at IS NULL
                                   AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                   AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                                 GROUP BY receive_date) bills_to_receive
                           GROUP BY receive_date
                           ORDER BY date) base
                        GROUP BY date
                        ORDER BY date) A
                     WHERE A.date <= total.date) balance
                  FROM
                    (SELECT `date` ,
                            SUM(credit) AS credit ,
                            SUM(debit) AS debit
                     FROM
                       (SELECT due_date AS date ,
                               SUM(IF(net_value < 0, net_value, 0)) AS debit ,
                               SUM(IF(net_value >= 0, net_value, 0)) AS credit
                        FROM (

                              SELECT IFNULL(receive_date, '1970-01-01') as due_date ,
                                     SUM(net_value) AS net_value
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BID.bank_category_id = BC.id
                              WHERE BID.receive_date < '{$periodFrom}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                              UNION
                              SELECT payment_date ,
                                     SUM(net_value) AS net_value
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BID.bank_category_id = BC.id
                              WHERE BID.payment_date < '{$periodFrom}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . ") initial_balance
                        UNION SELECT payment_date ,
                                     SUM(debit) AS debit ,
                                     0 credit
                        FROM (

                              SELECT payment_date ,
                                     SUM(BID.net_value) AS debit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE payment_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                              GROUP BY payment_date
                              ORDER BY payment_date) bills_to_pay
                        GROUP BY payment_date
                        UNION SELECT receive_date ,
                                     0 debit ,
                                       SUM(credit) AS credit
                        FROM (-- contas a receber real --

                              SELECT receive_date ,
                                     SUM(BID.net_value) AS credit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE receive_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . " )
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                              GROUP BY receive_date) bills_to_receive
                        GROUP BY receive_date
                        ORDER BY date) base
                     GROUP BY date
                     ORDER BY date) total) x) y
            WHERE date >= '{$periodFrom}'
        ";

  if ('day' == $groupBy) {
   $sql = "
            SELECT BID.id ,
                   BID.receive_date AS `date` ,
                   BID.net_value,
                   BID.gross_value,
                   tax_value,
                   CASE WHEN note IS NULL OR note = '' THEN BIT.description ELSE note END AS note,
                   parcel_number,
                   total_invoices AS total_parcels,
                   PM.name AS payment_method,
                   CONCAT(U.name, ' ', U.last_name) AS user_name,
                   S.box_sale_code,
                   S.id AS sale_id,
                   BC.name as category,
                   BID.box_id,
                   U.id AS user_id,
                   'user' AS type_user
            FROM bank_invoice_detail BID
            JOIN bank_category BC ON BC.id = BID.bank_category_id
            LEFT JOIN bank_account BA ON BA.id = BID.bank_account_id
            LEFT JOIN bank_invoice_transaction BIT ON BIT.id = BID.bank_invoice_transaction_id
            LEFT JOIN payment_method PM ON PM.id = BID.payment_method_id
            LEFT JOIN user U ON U.id = BID.user_id
            LEFT JOIN sale S ON S.id = BID.sale_id
            WHERE BID.receive_date = '{$periodFrom}'
              AND BID.company_id = '{$companyId}'
              AND BID.bank_account_id IN (" . implode(',', $bankAccountId) . ")
              AND BID.deleted_at IS NULL
              AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
              AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
            UNION
            SELECT BID.id ,
                   payment_date AS `date` ,
                   BID.net_value,
                   BID.gross_value,
                   tax_value,
                   CASE WHEN note IS NULL OR note = '' THEN BIT.description ELSE note END AS note,
                   parcel_number,
                   total_invoices AS total_parcels,
                   PM.name AS payment_method,
                   SP.name AS user_name,
                   '' AS box_sale_code,
                   '' AS sale_id,
                   BC.name as category,
                   BID.box_id,
                   SP.id AS user_id,
                   'supplier' AS type_user
            FROM bank_invoice_detail BID
            JOIN bank_category BC ON BC.id = BID.bank_category_id
            LEFT JOIN bank_invoice_transaction BIT ON BIT.id = BID.bank_invoice_transaction_id
            LEFT JOIN bank_account BA ON BA.id = BID.bank_account_id
            LEFT JOIN payment_method PM ON PM.id = BID.payment_method_id
            LEFT JOIN user U ON U.id = BID.user_id
            LEFT JOIN suppliers SP ON SP.id = BID.supplier_id
            WHERE BID.payment_date = '{$periodFrom}'
              AND BID.company_id = '{$companyId}'
              AND BID.bank_account_id IN (" . implode(',', $bankAccountId) . ")
              AND BID.deleted_at IS NULL
              AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
              AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
            ORDER BY user_name
            ";
  }

  return \DB::select($sql);
 }

 /**
  * get cash flow when periodFrom between periodTo in past and
  * @param  string $periodFrom    period from (yyyy-mm-dd)
  * @param  string $periodTo      period to (yyyy-mm-dd)
  * @param  array $bankAccountId    ids of bankAccount
  * @return array
  */
 public static function pastAndFuture($periodFrom, $periodTo, $bankAccountId = [])
 {
  $companyId = \Auth::user()->company_id;
  $sql       = "
            SELECT `date` ,
                   (balance - (credit + debit)) AS initial_balance ,
                   credit ,
                   debit ,
                   balance
            FROM
              (SELECT `date` , credit , debit ,
                 (SELECT SUM(credit + debit)
                  FROM
                    (SELECT date , SUM(credit) AS credit ,
                                   SUM(debit) AS debit
                     FROM
                       (SELECT due_date AS date ,
                               SUM(IF(net_value < 0, net_value, 0)) AS debit ,
                               SUM(IF(net_value >= 0, net_value, 0)) AS credit
                        FROM (

                              SELECT IFNULL(receive_date, '1970-01-01') as due_date ,
                                     SUM(net_value) AS net_value
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BID.bank_category_id = BC.id
                              WHERE BID.receive_date < '{$periodFrom}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                              UNION
                              SELECT payment_date ,
                                     SUM(net_value) AS net_value
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BID.bank_category_id = BC.id
                              WHERE BID.payment_date < '{$periodFrom}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . ") initial_balance
                        UNION SELECT payment_date ,
                                     SUM(debit) AS debit ,
                                     0 credit
                        FROM (

                              SELECT payment_date ,
                                     SUM(BID.net_value) AS debit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE payment_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                              GROUP BY payment_date
                              UNION -- contas a pagar virtual --

                              SELECT due_date ,
                                     SUM(BID.net_value) AS debit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE due_date BETWEEN DATE(NOW()) AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status NOT IN (" . BankInvoiceDetail::STATUS_CANCELLED . ", " . BankInvoiceDetail::STATUS_PAID . ")
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                              GROUP BY due_date
                              ORDER BY payment_date) bills_to_pay
                        GROUP BY payment_date
                        UNION SELECT receive_date ,
                                     0 debit ,
                                       SUM(credit) AS credit
                        FROM (-- contas a receber real --

                              SELECT receive_date ,
                                     SUM(BID.net_value) AS credit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE receive_date BETWEEN '{$periodFrom}' AND SUBDATE(DATE(NOW()), 1)
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                              GROUP BY receive_date

                            UNION -- contas a receber virtual --

                              SELECT receive_date ,
                                     SUM(BID.net_value) AS credit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE receive_date BETWEEN DATE(NOW()) AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status <> " . BankInvoiceDetail::STATUS_CANCELLED . "
                                AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                              GROUP BY receive_date

                            ) bills_to_receive
                        GROUP BY receive_date
                        ORDER BY date) base
                     GROUP BY date
                     ORDER BY date) A
                  WHERE A.date <= total.date) balance
               FROM
                 (SELECT date , SUM(credit) AS credit ,
                                SUM(debit) AS debit
                  FROM
                    (SELECT `date` AS date ,
                            SUM(IF(net_value < 0, net_value, 0)) AS debit ,
                            SUM(IF(net_value >= 0, net_value, 0)) AS credit
                     FROM (

                           SELECT IFNULL(receive_date, '1970-01-01') as date ,
                                  SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                           WHERE BID.receive_date < '{$periodFrom}'
                             AND BID.company_id = {$companyId}
                             AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                             AND BID.deleted_at IS NULL
                             AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                             AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                           UNION
                           SELECT payment_date ,
                                  SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                           WHERE BID.payment_date < '{$periodFrom}'
                             AND BID.company_id = {$companyId}
                             AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                             AND BID.deleted_at IS NULL
                             AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                             AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . ") initial_balance
                     UNION SELECT payment_date ,
                                  SUM(debit) AS debit ,
                                  0 credit
                     FROM (

                              SELECT payment_date ,
                                     SUM(BID.net_value) AS debit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE payment_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                              GROUP BY payment_date
                              UNION -- contas a pagar virtual --

                              SELECT due_date ,
                                     SUM(BID.net_value) AS debit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE due_date BETWEEN DATE(NOW()) AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status NOT IN (" . BankInvoiceDetail::STATUS_CANCELLED . ", " . BankInvoiceDetail::STATUS_PAID . ")
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                              GROUP BY due_date
                              ORDER BY payment_date) bills_to_pay
                     GROUP BY payment_date
                     UNION SELECT receive_date ,
                                  0 debit ,
                                    SUM(credit) AS credit
                     FROM (

                        -- contas a receber real --

                           SELECT receive_date ,
                                  SUM(BID.net_value) AS credit
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BC.id = BID.bank_category_id
                           WHERE receive_date BETWEEN '{$periodFrom}' AND SUBDATE(DATE(NOW()), 1)
                             AND BID.company_id = {$companyId}
                             AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                             AND BID.deleted_at IS NULL
                             AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                             AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                           GROUP BY receive_date

                           UNION

                           -- contas a receber virtual --

                           SELECT receive_date ,
                                  SUM(BID.net_value) AS credit
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BC.id = BID.bank_category_id
                           WHERE receive_date BETWEEN DATE(NOW()) AND '{$periodTo}'
                             AND BID.company_id = {$companyId}
                             AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                             AND BID.deleted_at IS NULL
                             AND BID.status <> " . BankInvoiceDetail::STATUS_CANCELLED . "
                             AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                           GROUP BY receive_date

                           ORDER BY receive_date) bills_to_receive
                     GROUP BY receive_date
                     ORDER BY date) base
                  GROUP BY date
                  ORDER BY date) total) x
        WHERE date >= '{$periodFrom}'
        ";

  echo '<pre>';
  echo $sql;
  echo '<pre>';

  return \DB::select($sql);
 }

 /**
  * get cash flow when periodFrom > current date
  * @param  string $periodFrom    period from (yyyy-mm-dd)
  * @param  string $periodTo      period to (yyyy-mm-dd)
  * @param  array $bankAccountId  ids of bankAccount
  * @return array
  */
 public static function future($periodFrom, $periodTo, $bankAccountId = [], $groupBy = 'date')
 {
  $companyId = \Auth::user()->company_id;
  $sql       = "
            SELECT `date` ,
                   (balance - (credit + debit)) AS initial_balance ,
                   credit ,
                   debit ,
                   balance
            FROM
              (SELECT `date`, credit , debit ,
                 (SELECT SUM(credit + debit)
                  FROM
                    (SELECT `date` ,
                            SUM(credit) AS credit ,
                            SUM(debit) AS debit
                     FROM
                       (SELECT due_date AS date ,
                               SUM(IF(net_value < 0, net_value, 0)) AS debit ,
                               SUM(IF(net_value >= 0, net_value, 0)) AS credit
                        FROM (
                        -- saldo inicial real --

                        SELECT IFNULL(receive_date, '1970-01-01') as due_date ,
                             SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.receive_date < CurDate()
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                            AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")

                           UNION

                          SELECT IFNULL(receive_date, '1970-01-01') as due_date ,
                                 SUM(net_value) AS net_value
                          FROM bank_invoice_detail BID
                          JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.receive_date >= CurDate() AND receive_date < '{$periodFrom}'
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status IN (" . BankInvoiceDetail::STATUS_ACTIVE . ", " . BankInvoiceDetail::STATUS_PAID . ")
                            AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")

                          UNION

                          SELECT payment_date ,
                                 SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.payment_date < CurDate()
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                            AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "

                            UNION

                          SELECT due_date ,
                                 SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.due_date >= CurDate() AND due_date < '{$periodFrom}'
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status IN (" . BankInvoiceDetail::STATUS_ACTIVE . ", " . BankInvoiceDetail::STATUS_PAID . ")
                            AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "

                    ) initial_balance
                        UNION SELECT due_date ,
                                     SUM(debit) AS debit ,
                                     0 credit
                        FROM (-- contas a pagar virtual --

                              SELECT due_date ,
                                     SUM(BID.net_value) AS debit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE due_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status <> " . BankInvoiceDetail::STATUS_CANCELLED . "
                                AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                              GROUP BY due_date
                              ORDER BY due_date) bills_to_pay
                        GROUP BY due_date
                        UNION SELECT due_date ,
                                     0 debit ,
                                       SUM(credit) AS credit
                        FROM (-- contas a receber virtual --

                              SELECT receive_date as due_date,
                                     SUM(BID.net_value) AS credit
                              FROM bank_invoice_detail BID
                              JOIN bank_category BC ON BC.id = BID.bank_category_id
                              WHERE receive_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                                AND BID.company_id = {$companyId}
                                AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                                AND BID.deleted_at IS NULL
                                AND BID.status <> " . BankInvoiceDetail::STATUS_CANCELLED . "
                                AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                              GROUP BY receive_date
                              ORDER BY receive_date) bills_to_receive
                        GROUP BY due_date
                        ORDER BY date) base
                     GROUP BY date
                     ORDER BY date) A
                  WHERE A.date <= total.date) balance
               FROM
                 (SELECT date , SUM(credit) AS credit ,
                                SUM(debit) AS debit
                  FROM
                    (SELECT due_date AS date ,
                            SUM(IF(net_value < 0, net_value, 0)) AS debit ,
                            SUM(IF(net_value >= 0, net_value, 0)) AS credit
                     FROM (-- saldo inicial real --


                       SELECT IFNULL(receive_date, '1970-01-01') as due_date ,
                             SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.receive_date < CurDate()
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                            AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")

                           UNION

                          SELECT IFNULL(receive_date, '1970-01-01') as due_date ,
                                 SUM(net_value) AS net_value
                          FROM bank_invoice_detail BID
                          JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.receive_date >= CurDate() AND receive_date < '{$periodFrom}'
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status IN (" . BankInvoiceDetail::STATUS_ACTIVE . ", " . BankInvoiceDetail::STATUS_PAID . ")
                            AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")

                          UNION

                          SELECT payment_date ,
                                 SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.payment_date < CurDate()
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "
                            AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "

                            UNION

                          SELECT due_date ,
                                 SUM(net_value) AS net_value
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BID.bank_category_id = BC.id
                          WHERE BID.due_date >= CurDate() AND due_date < '{$periodFrom}'
                            AND BID.company_id = {$companyId}
                            AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                            AND BID.deleted_at IS NULL
                            AND BID.status IN (" . BankInvoiceDetail::STATUS_ACTIVE . ", " . BankInvoiceDetail::STATUS_PAID . ")
                            AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "

                            ) initial_balance
                     UNION SELECT due_date ,
                                  SUM(debit) AS debit ,
                                  0 credit
                     FROM (-- contas a pagar virtual --

                           SELECT due_date ,
                                  SUM(BID.net_value) AS debit
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BC.id = BID.bank_category_id
                           WHERE due_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                             AND BID.company_id = {$companyId}
                             AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                             AND BID.deleted_at IS NULL
                             AND BID.status <> " . BankInvoiceDetail::STATUS_CANCELLED . "
                             AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
                           GROUP BY due_date
                           ORDER BY due_date) bills_to_pay
                     GROUP BY due_date
                     UNION SELECT due_date ,
                                  0 debit ,
                                    SUM(credit) AS credit
                     FROM (-- contas a receber virtual --

                           SELECT receive_date as due_date,
                                  SUM(BID.net_value) AS credit
                           FROM bank_invoice_detail BID
                           JOIN bank_category BC ON BC.id = BID.bank_category_id
                           WHERE receive_date BETWEEN '{$periodFrom}' AND '{$periodTo}'
                             AND BID.company_id = {$companyId}
                             AND BID.bank_account_id IN ( " . implode(',', $bankAccountId) . ")
                             AND BID.deleted_at IS NULL
                             AND BID.status <> " . BankInvoiceDetail::STATUS_CANCELLED . "
                             AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
                           GROUP BY receive_date
                           ORDER BY receive_date) bills_to_receive
                     GROUP BY due_date
                     ORDER BY date) base
                  GROUP BY date
                  ORDER BY date) total) x
              WHERE date >= '{$periodFrom}'
        ";

  // get all records of day
  if ('day' == $groupBy) {
   $sql = "
            SELECT BID.id ,
                   BID.due_date AS `date` ,
                   BID.net_value,
                   BID.gross_value,
                   tax_value,
                   note,
                   parcel_number,
                   total_invoices AS total_parcels,
                   PM.name AS payment_method,
                   CONCAT(U.name, ' ', U.last_name) AS user_name,
                   S.box_sale_code,
                   S.id AS sale_id,
                   BC.name as category,
                   BID.box_id,
                   U.id AS user_id,
                   'user' AS type_user
            FROM bank_invoice_detail BID
            JOIN bank_category BC ON BC.id = BID.bank_category_id
            LEFT JOIN bank_invoice_transaction BIT ON BIT.id = BID.bank_invoice_transaction_id
            LEFT JOIN payment_method PM ON PM.id = BID.payment_method_id
            LEFT JOIN user U ON U.id = BID.user_id
            LEFT JOIN sale S ON S.id = BID.sale_id
            WHERE BID.receive_date = '{$periodFrom}'
              AND BID.company_id = '{$companyId}'
              AND BID.bank_account_id IN (" . implode(',', $bankAccountId) . ")
              AND BID.deleted_at IS NULL
              AND BID.status <> " . BankInvoiceDetail::STATUS_CANCELLED . "
              AND BC.main_parent_category_id IN (" . BankCategory::CATEGORY_TO_RECEIVE . ", " . BankCategory::CATEGORY_INITIAL_BALANCE . ")
            UNION
            SELECT BID.id ,
                   due_date AS `date` ,
                   BID.net_value,
                   BID.gross_value,
                   tax_value,
                   CASE WHEN note IS NULL OR note = '' THEN BIT.description ELSE note END AS note,
                   parcel_number,
                   total_invoices AS total_parcels,
                   PM.name AS payment_method,
                   SP.name AS user_name,
                   '' AS box_sale_code,
                   '' AS sale_id,
                   BC.name as category,
                   BID.box_id,
                   SP.id AS user_id,
                   'supplier' AS type_user
            FROM bank_invoice_detail BID
            JOIN bank_category BC ON BC.id = BID.bank_category_id
            LEFT JOIN bank_invoice_transaction BIT ON BIT.id = BID.bank_invoice_transaction_id
            LEFT JOIN payment_method PM ON PM.id = BID.payment_method_id
            LEFT JOIN user U ON U.id = BID.user_id
            LEFT JOIN suppliers SP ON SP.id = BID.supplier_id
            WHERE ((BID.due_date = '{$periodFrom}' AND BID.status not in (" . BankInvoiceDetail::STATUS_CANCELLED . "," . BankInvoiceDetail::STATUS_PAID . ")) OR (BID.payment_date = '{$periodFrom}' AND BID.status = " . BankInvoiceDetail::STATUS_PAID . "))
              AND BID.company_id = '{$companyId}'
              AND BID.bank_account_id IN (" . implode(',', $bankAccountId) . ")
              AND BID.deleted_at IS NULL
              AND BC.main_parent_category_id = " . BankCategory::CATEGORY_TO_PAY . "
            ORDER BY user_name
            ";
  }

 }

}

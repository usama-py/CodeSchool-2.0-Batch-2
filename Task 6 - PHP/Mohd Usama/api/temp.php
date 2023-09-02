<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'dbconfig.php';

$response = ["status" => false, "data" => ""];
for ($i=1 ; $i<21 ; $i++){
    $query = "SELECT
    SUM(CASE WHEN earndeds_type = 'Earning' THEN amount ELSE 0 END) AS total_earnings,
    SUM(CASE WHEN earndeds_type = 'Deduction' THEN amount ELSE 0 END) AS total_deductions
  FROM
    EarnDeds
  WHERE
    earndeds_employee_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$i]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_earnings = $result["total_earnings"];
$total_deductions = $result["total_deductions"];
$difference = $total_earnings - $total_deductions;
$query = "UPDATE EmployeeBankDetails SET amount_to_be_payed = ? WHERE emp_BankDetails_id = ?";
        $stmt = $pdo->prepare($query);
    $result = $stmt->execute([$difference, $i]);

}
echo json_encode($response);
?>

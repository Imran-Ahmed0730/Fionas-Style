<?php
$host = '127.0.0.1';
$db = 'fionas_style';
$user = 'root';
$pass = '';
$port = '3308';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

$name = "Organic Bamboo Pajama Set";
$stmt = $pdo->prepare("SELECT id, name, attribute_values, color, is_variant FROM products WHERE name LIKE ?");
$stmt->execute(["%$name%"]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found: $name\n";
    exit;
}

echo "ID: " . $product['id'] . "\n";
echo "NAME: " . $product['name'] . "\n";
echo "ATTR_VALUES: " . $product['attribute_values'] . "\n";
echo "COLOR: " . $product['color'] . "\n";
echo "IS_VARIANT: " . $product['is_variant'] . "\n";

if ($product['attribute_values']) {
    $data = json_decode($product['attribute_values'], true);
    echo "DECODED_ATTR: " . print_r($data, true) . "\n";

    if (is_array($data)) {
        $ids = array_keys($data);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $pdo->prepare("SELECT id, name FROM attributes WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $names = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        echo "ATTR_NAMES: " . print_r($names, true) . "\n";
    }
}

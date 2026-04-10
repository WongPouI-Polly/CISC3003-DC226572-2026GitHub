<?php
// book-utilities.inc.php
// Utility functions for book and customer data management

/**
 * Reads customers from the customers.txt file.
 * Returns an associative array keyed by customer id.
 *
 * @param string $filePath Optional custom file path
 * @return array Associative array of customers keyed by id
 */
function getCustomers($filePath = 'data/customers.txt') {
    $customers = [];
    
    // Try multiple possible locations for the data file
    $paths = [
        $filePath,
        'data/customers.txt',
        'customers.txt',
        '../data/customers.txt',
        __DIR__ . '/data/customers.txt',
        __DIR__ . '/customers.txt'
    ];
    
    $file = null;
    foreach ($paths as $path) {
        if (file_exists($path)) {
            $file = fopen($path, 'r');
            break;
        }
    }
    
    if (!$file) {
        return $customers;
    }
    
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        if (empty($line)) continue;
        $data = explode(';', $line);
        if (count($data) >= 12) {
            $customer = [
                'id' => (int)$data[0],
                'first_name' => trim($data[1]),
                'last_name' => trim($data[2]),
                'email' => trim($data[3]),
                'university' => trim($data[4]),
                'address' => trim($data[5]),
                'city' => trim($data[6]),
                'state' => trim($data[7]),
                'country' => trim($data[8]),
                'postal' => trim($data[9]),
                'phone' => trim($data[10]),
                'sales' => trim($data[11])
            ];
            $customers[$customer['id']] = $customer;
        }
    }
    fclose($file);
    return $customers;
}

/**
 * Reads orders from the orders.txt file.
 * Returns an array of orders keyed by customer_id for easy lookup.
 *
 * @param string $filePath Optional custom file path
 * @return array Associative array of orders keyed by customer_id
 */
function getOrders($filePath = 'data/orders.txt') {
    $orders = [];
    
    $paths = [
        $filePath,
        'data/orders.txt',
        'orders.txt',
        '../data/orders.txt',
        __DIR__ . '/data/orders.txt',
        __DIR__ . '/orders.txt'
    ];
    
    $file = null;
    foreach ($paths as $path) {
        if (file_exists($path)) {
            $file = fopen($path, 'r');
            break;
        }
    }
    
    if (!$file) {
        return $orders;
    }
    
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        if (empty($line)) continue;
        $data = explode(',', $line);
        if (count($data) >= 5) {
            $order = [
                'order_id' => (int)$data[0],
                'customer_id' => (int)$data[1],
                'isbn' => trim($data[2]),
                'title' => trim($data[3]),
                'category' => trim($data[4])
            ];
            $cid = $order['customer_id'];
            if (!isset($orders[$cid])) {
                $orders[$cid] = [];
            }
            $orders[$cid][] = $order;
        }
    }
    fclose($file);
    return $orders;
}

/**
 * Gets a specific customer by ID
 *
 * @param int $customerId The customer ID to look up
 * @param array $customers Optional pre-loaded customers array
 * @return array|null Customer data or null if not found
 */
function getCustomerById($customerId, $customers = null) {
    if ($customers === null) {
        $customers = getCustomers();
    }
    
    if (isset($customers[$customerId])) {
        return $customers[$customerId];
    }
    
    return null;
}

/**
 * Gets orders for a specific customer
 *
 * @param int $customerId The customer ID
 * @param array $orders Optional pre-loaded orders array
 * @return array Array of orders for the customer
 */
function getCustomerOrders($customerId, $orders = null) {
    if ($orders === null) {
        $orders = getOrders();
    }
    
    return isset($orders[$customerId]) ? $orders[$customerId] : [];
}

/**
 * Formats sales data for sparkline display
 *
 * @param string $salesString Comma-separated sales numbers
 * @return array Array of integers
 */
function formatSalesData($salesString) {
    $values = explode(',', $salesString);
    $formatted = [];
    foreach ($values as $value) {
        $formatted[] = (int)trim($value);
    }
    return $formatted;
}

/**
 * Generates a full name from first and last name
 *
 * @param array $customer Customer array with 'first_name' and 'last_name'
 * @return string Full name
 */
function getCustomerFullName($customer) {
    return trim($customer['first_name'] . ' ' . $customer['last_name']);
}

/**
 * Displays a summary of customer information
 *
 * @param array $customer Customer data
 * @return string HTML formatted customer details
 */
function displayCustomerDetails($customer) {
    if (!$customer) {
        return '<p>No customer selected.</p>';
    }
    
    $html = '<h4 style="margin-top: 0; color: #ff5722;">' . htmlspecialchars(getCustomerFullName($customer)) . '</h4>';
    $html .= '<div class="customer-detail-text">';
    $html .= '<p><strong>Email:</strong> ' . htmlspecialchars($customer['email']) . '</p>';
    $html .= '<p><strong>University:</strong> ' . htmlspecialchars($customer['university']) . '</p>';
    $html .= '<p><strong>Address:</strong> ' . htmlspecialchars($customer['address']) . '</p>';
    $html .= '<p><strong>City:</strong> ' . htmlspecialchars($customer['city']) . '</p>';
    $html .= '<p><strong>State/Province:</strong> ' . htmlspecialchars($customer['state']) . '</p>';
    $html .= '<p><strong>Country:</strong> ' . htmlspecialchars($customer['country']) . '</p>';
    $html .= '<p><strong>Postal Code:</strong> ' . htmlspecialchars($customer['postal']) . '</p>';
    $html .= '<p><strong>Phone:</strong> ' . htmlspecialchars($customer['phone']) . '</p>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Displays orders in an HTML table
 *
 * @param array $orders Array of orders for a customer
 * @return string HTML formatted orders table
 */
function displayCustomerOrders($orders) {
    if (empty($orders)) {
        return '<div class="no-data-message" style="text-align: left; padding: 0;">
                    <i class="material-icons" style="color: #ff9800;">shopping_cart</i>
                    <p>No order information found for this customer.</p>
                </div>';
    }
    
    $html = '<table class="mdl-data-table mdl-shadow--2dp" style="width: 100%;">
              <thead>
                <tr>
                  <th class="mdl-data-table__cell--non-numeric">Cover</th>
                  <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                  <th class="mdl-data-table__cell--non-numeric">Title</th>
                </tr>
              </thead>
              <tbody>';
    
    foreach ($orders as $order) {
        $html .= '<tr>
                    <td class="mdl-data-table__cell--non-numeric">
                        <i class="material-icons book-icon" style="color: #ff5722;">book</i>
                    </td>
                    <td class="mdl-data-table__cell--non-numeric">
                        ' . htmlspecialchars($order['isbn']) . '
                    </td>
                    <td class="mdl-data-table__cell--non-numeric">
                        ' . htmlspecialchars($order['title']) . '
                    </td>
                  </tr>';
    }
    
    $html .= '</tbody></table>';
    
    return $html;
}

/**
 * Calculates total sales for a customer
 *
 * @param string $salesString Comma-separated sales numbers
 * @return int Total sales
 */
function getTotalSales($salesString) {
    $values = explode(',', $salesString);
    $total = 0;
    foreach ($values as $value) {
        $total += (int)trim($value);
    }
    return $total;
}

/**
 * Gets the top 5 customers by total sales
 *
 * @param array $customers Array of customers
 * @return array Top 5 customers sorted by total sales
 */
function getTopCustomers($customers) {
    // Calculate total sales for each customer
    foreach ($customers as &$customer) {
        $customer['total_sales'] = getTotalSales($customer['sales']);
    }
    
    // Sort by total sales descending
    usort($customers, function($a, $b) {
        return $b['total_sales'] - $a['total_sales'];
    });
        
        // Return top 5
        return array_slice($customers, 0, 5);
}

/**
 * Gets unique book categories from orders
 *
 * @param array $orders Array of all orders
 * @return array Unique categories
 */
function getUniqueCategories($orders) {
    $categories = [];
    foreach ($orders as $customerOrders) {
        foreach ($customerOrders as $order) {
            if (!in_array($order['category'], $categories)) {
                $categories[] = $order['category'];
            }
        }
    }
    sort($categories);
    return $categories;
}

/**
 * Sanitizes output for HTML display
 *
 * @param string $data The data to sanitize
 * @return string Sanitized data
 */
function sanitizeOutput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>
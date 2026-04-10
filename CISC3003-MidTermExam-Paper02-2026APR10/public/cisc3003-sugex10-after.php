<?php
// cisc3003-sugex10-after.php

// --- Data Loading Functions (defined here since book-utilities.inc.php is empty) ---

/**
 * Reads customers from the customers.txt file.
 * Returns an associative array keyed by customer id.
 */
function getCustomers() {
    $customers = [];
    // Try multiple possible paths for the data file
    $possiblePaths = [
        'data/customers.txt',
        'customers.txt',
        '../data/customers.txt',
        __DIR__ . '/data/customers.txt',
        __DIR__ . '/customers.txt'
    ];
    
    $file = null;
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            $file = fopen($path, 'r');
            break;
        }
    }
    
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if (empty($line)) continue;
            $data = explode(';', $line);
            if (count($data) >= 12) {
                $customer = [
                    'id' => (int)$data[0],
                    'first_name' => $data[1],
                    'last_name' => $data[2],
                    'email' => $data[3],
                    'university' => $data[4],
                    'address' => $data[5],
                    'city' => $data[6],
                    'state' => $data[7],
                    'country' => $data[8],
                    'postal' => $data[9],
                    'phone' => $data[10],
                    'sales' => $data[11]
                ];
                $customers[$customer['id']] = $customer;
            }
        }
        fclose($file);
    }
    return $customers;
}

/**
 * Reads orders from the orders.txt file.
 * Returns an array of orders, keyed by customer_id for easy lookup.
 */
function getOrders() {
    $orders = [];
    $possiblePaths = [
        'data/orders.txt',
        'orders.txt',
        '../data/orders.txt',
        __DIR__ . '/data/orders.txt',
        __DIR__ . '/orders.txt'
    ];
    
    $file = null;
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            $file = fopen($path, 'r');
            break;
        }
    }
    
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if (empty($line)) continue;
            $data = explode(',', $line);
            if (count($data) >= 5) {
                $order = [
                    'order_id' => (int)$data[0],
                    'customer_id' => (int)$data[1],
                    'isbn' => $data[2],
                    'title' => $data[3],
                    'category' => $data[4]
                ];
                $cid = $order['customer_id'];
                if (!isset($orders[$cid])) {
                    $orders[$cid] = [];
                }
                $orders[$cid][] = $order;
            }
        }
        fclose($file);
    }
    return $orders;
}

// --- Main Logic ---
$customers = getCustomers();
$orders = getOrders();

// Determine selected customer (from query string 'id')
$selectedCustomerId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$selectedCustomer = null;
if ($selectedCustomerId && isset($customers[$selectedCustomerId])) {
    $selectedCustomer = $customers[$selectedCustomerId];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>DC226572 Wong Pou I- CISC3003 Suggested Exercise 10</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- USE LOCAL FILES instead of CDN to avoid 403 errors -->
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/demo-styles.css">
    
    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <!-- USE LOCAL FILE instead of CDN -->
    <script src="js/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>
    
    <script type="text/javascript">
        // Initialize sparklines after document is ready
        $(document).ready(function() {
            $('.inlinesparkline').each(function() {
                var $this = $(this);
                var values = $this.text().split(',');
                // Clean up any whitespace in values
                var cleanValues = [];
                for (var i = 0; i < values.length; i++) {
                    var val = $.trim(values[i]);
                    if (val !== '') {
                        cleanValues.push(parseInt(val) || 0);
                    }
                }
                $this.sparkline(cleanValues, {
                    type: 'bar',
                    barColor: '#ff5722',
                    height: '30px',
                    barWidth: 5,
                    barSpacing: 2
                });
            });
        });
    </script>
</head>

<body>
    
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
            
    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>
    
    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">

            <div class="mdl-grid">

              <!-- mdl-cell + mdl-card for Customers Table -->
              <div class="mdl-cell mdl-cell--7-col card-lesson mdl-card  mdl-shadow--2dp">
                <div class="mdl-card__title mdl-color--orange">
                  <h2 class="mdl-card__title-text">Customers</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <table class="mdl-data-table mdl-shadow--2dp">
                      <thead>
                        <tr>
                          <th class="mdl-data-table__cell--non-numeric">Name</th>
                          <th class="mdl-data-table__cell--non-numeric">University</th>
                          <th class="mdl-data-table__cell--non-numeric">City</th>
                          <th>Sales (Monthly)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (empty($customers)): ?>
                            <tr>
                                <td colspan="4" class="mdl-data-table__cell--non-numeric">
                                    No customer data found. Please check that customers.txt exists.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $customer): ?>
                            <tr>
                              <td class="mdl-data-table__cell--non-numeric">
                                <a href="?id=<?php echo $customer['id']; ?>">
                                  <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                </a>
                              </td>
                              <td class="mdl-data-table__cell--non-numeric">
                                <?php echo htmlspecialchars($customer['university']); ?>
                              </td>
                              <td class="mdl-data-table__cell--non-numeric">
                                <?php echo htmlspecialchars($customer['city']); ?>
                              </td>
                              <td>
                                <span class="inlinesparkline"><?php echo $customer['sales']; ?></span>
                              </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                </div>
              </div>  <!-- / mdl-cell + mdl-card -->
              
              
            <div class="mdl-grid mdl-cell--5-col">
    
                <!-- Customer Details Card -->
                <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Customer Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <?php if ($selectedCustomer): ?>
                            <h4><?php echo htmlspecialchars($selectedCustomer['first_name'] . ' ' . $selectedCustomer['last_name']); ?></h4>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($selectedCustomer['email']); ?><br>
                            <strong>University:</strong> <?php echo htmlspecialchars($selectedCustomer['university']); ?><br>
                            <strong>Address:</strong> <?php echo htmlspecialchars($selectedCustomer['address']); ?><br>
                            <strong>City:</strong> <?php echo htmlspecialchars($selectedCustomer['city']); ?><br>
                            <strong>State/Province:</strong> <?php echo htmlspecialchars($selectedCustomer['state']); ?><br>
                            <strong>Country:</strong> <?php echo htmlspecialchars($selectedCustomer['country']); ?><br>
                            <strong>Postal Code:</strong> <?php echo htmlspecialchars($selectedCustomer['postal']); ?><br>
                            <strong>Phone:</strong> <?php echo htmlspecialchars($selectedCustomer['phone']); ?></p>
                        <?php else: ?>
                            <h4>No customer selected</h4>
                            <p>Click on a customer name from the table to view their details and order history.</p>
                        <?php endif; ?>
                    </div>    
                </div>  <!-- / Customer Details Card -->

                <!-- Order Details Card -->
                <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Order Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">       
                        <?php if ($selectedCustomer): ?>
                            <?php
                            $customerOrders = isset($orders[$selectedCustomer['id']]) ? $orders[$selectedCustomer['id']] : [];
                            if (count($customerOrders) > 0):
                            ?>
                            <table class="mdl-data-table mdl-shadow--2dp">
                              <thead>
                                <tr>
                                  <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                  <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                  <th class="mdl-data-table__cell--non-numeric">Title</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($customerOrders as $order): ?>
                                <tr>
                                  <td class="mdl-data-table__cell--non-numeric">
                                    <i class="material-icons" style="color:#ff5722;">book</i>
                                  </td>
                                  <td class="mdl-data-table__cell--non-numeric">
                                    <?php echo htmlspecialchars($order['isbn']); ?>
                                  </td>
                                  <td class="mdl-data-table__cell--non-numeric">
                                    <?php echo htmlspecialchars($order['title']); ?>
                                  </td>
                                </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                            <?php else: ?>
                                <p><em>No order information found for this customer.</em></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p>Select a customer from the table to view their order history.</p>
                        <?php endif; ?>
                    </div>    
                </div>  <!-- / Order Details Card -->

            </div>   
           
            </div>  <!-- / mdl-grid -->    
            
            <footer>
            	CISC3003 Web Programming: DC226572 Wong Pou I 2026
            </footer>

        </section>
    </main>    
</div>    <!-- / mdl-layout --> 
          
</body>
</html>
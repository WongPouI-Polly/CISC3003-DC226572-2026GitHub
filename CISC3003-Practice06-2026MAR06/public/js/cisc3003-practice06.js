/* add loop and other code here ... in this simple exercise we are not
   going to concern ourselves with minimizing globals, etc */

// Variables for calculations
var subtotal = 0;
var tax = 0;
var shipping = 0;
var grandTotal = 0;

// Loop through the arrays to generate cart rows and calculate subtotal
for (var i = 0; i < filenames.length; i++) {
    var total = calculateTotal(quantities[i], prices[i]);
    outputCartRow(filenames[i], titles[i], quantities[i], prices[i], total);
    subtotal += total;
}

// Calculate tax (10% of subtotal)
tax = subtotal * 0.10;

// Calculate shipping ($40 if subtotal <= $1000, $0 if > $1000)
shipping = (subtotal > 1000) ? 0 : 40;

// Calculate grand total
grandTotal = subtotal + tax + shipping;

// Output the totals rows
document.write('<tr class="totals">');
document.write('<td colspan="4">Subtotal</td>');
document.write('<td>$' + subtotal.toFixed(2) + '</td>');
document.write('</tr>');

document.write('<tr class="totals">');
document.write('<td colspan="4">Tax</td>');
document.write('<td>$' + tax.toFixed(2) + '</td>');
document.write('</tr>');

document.write('<tr class="totals">');
document.write('<td colspan="4">Shipping</td>');
document.write('<td>$' + shipping.toFixed(2) + '</td>');
document.write('</tr>');

document.write('<tr class="totals focus">');
document.write('<td colspan="4">Grand Total</td>');
document.write('<td>$' + grandTotal.toFixed(2) + '</td>');
document.write('</tr>');
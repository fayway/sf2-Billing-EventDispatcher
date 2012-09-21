<?php $title = 'Call '.$call['id'].' Billing' ?>
<style type="text/css">
td
{
    border: 1px solid #bbbbbb;
    padding: 5px;
}
</style>
<?php ob_start() ?>
    <h1>To: <?php echo $call['recipient'] ?></h1>

    <?php 
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $call['time']);
    $call_day = $date->format('l m/Y');
    ?>
    <div class="time">When: <strong><?php echo $call_day ?></strong></div>
    <div class="duration">
        Duration: <strong><?php echo $call['duration'] ?></strong> seconds
    </div>
    <div>
    <hr>
    <h1>Bill details</h1>
    <h2>Customer: <?php echo $call['customer']; ?></h2>
    <table>
        <tr>
            <td>Billed Quantity</td><td><?php echo $bill->getQuantity(); ?></td>
        </tr>
        <tr>
            <td>Billed Unit Price</td><td><?php echo $bill->getUnitPrice(); ?></td>
        </tr>
        <tr>
            <td>Cost</td><td><?php echo $bill->getUnitPrice() * $bill->getQuantity(); ?> <?php echo $bill->getCurrency(); ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Event Remarks:</strong>
                <ul>
                    <?php foreach ($bill->getRemarks() as $remark): ?>
                    <li>
                        <?php echo $remark; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <strong>Original values before dispatching events</strong> <br />
            Quantity: <?php echo $original_bill->getQuantity(); ?> <br />
            Unit price: <?php echo $original_bill->getUnitPrice(); ?> <br />
            Cost: <?php echo ($original_bill->getUnitPrice() * $original_bill->getQuantity()) . ' '.$original_bill->getCurrency(); ?> <br />
            
            </td>
        </tr>
    </table>
    </div>
<?php $content = ob_get_clean() ?>

<?php include 'layout.php' ?>
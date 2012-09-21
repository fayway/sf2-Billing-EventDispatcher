<?php $title = 'Calls log' ?>

<?php ob_start() ?>
    <h1>List of Calls</h1>
    <ul>
        <?php foreach ($calls as $call): ?>
        <li>
            <a href="index.php/bill?id=<?php echo $call['id'] ?>">
                <?php echo $call['recipient'] ?> (<?php echo $call['duration'] ?>s)
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
<?php $content = ob_get_clean() ?>

<?php include 'layout.php' ?>
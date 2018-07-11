<?php if (count($this->messages)): ?>
    <div class="messages">
        <div class="container">
            <?php foreach ($this->messages as $message): ?>
                <div><?php echo $message ?></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
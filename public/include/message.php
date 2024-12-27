<div class="alert-overlay message-hidden" id="message-box">
    <div class="alert-content">
        <p id="message"><?php echo $message ?></p>
        <div class="alert-btn">
            <a href="<?php echo $lien ?>">
                <button class="alert-btn__btn-confirm btn-message" id="message-exit">
                    OK
                </button>
            </a>
        </div>
    </div>
    <?php
        $message = "" 
    ?>
</div>
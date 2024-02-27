<?php 


curl -s --user 'api:key-786abbc5caa2a87cf920bac2411bbc3b' \
    https://api.mailgun.net/v3/sandbox66184d7e8f614ecc9a7675c9f7c61c8b.mailgun.org/messages \
        -F from='Mailgun Sandbox <postmaster@sandbox66184d7e8f614ecc9a7675c9f7c61c8b.mailgun.org>' \
        -F to='ajay <ajay@brsoftech.com>' \
        -F subject='Hello ajay' \
        -F text='Congratulations ajay, you just sent an email with Mailgun!  You are truly awesome!'


?>
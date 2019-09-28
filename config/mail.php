<?php

return [
  "driver" => "smtp",
  "host" => "smtp.mailtrap.io",
  "port" => 2525,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "1343a785e48b5d",
  "password" => "fa1260823b99b9",
  "sendmail" => "/usr/sbin/sendmail -bs"
];

# Miscellaneous Kaltura tools

The configuration for these scripts should be in ```config.php```, a sample config is provided in ```config.php.sample``` and should contain something along the lines of

```php
<?php

define("API_URL", "https://api.example.com");
define("ADMIN_SECRET", "00000000000000000000000000000000");
define("USER_ID", "user@example.com");
define("PARTNER_ID", "10");

define("ND_API_URL", "https://api.example.net");
define("ND_ADMIN_SECRET", "00000000000000000000000000000000");
define("ND_USER_ID", "user@example.net");
define("ND_PARTNER_ID", "20");
```

Where the ND_ settings is for NORDUnet instance, and the other settings is for a source system.

# License

Everything within the external/kaltura-api folder is licensed according to the
contents of the individual files in that folder and its subfolders.

Everything else is licensed according to the NORDUnet License (3-clause BSD).
See [LICENSE.md](LICENSE.md) for more details.

# Copyright

Unless otherwise noted in the individual files the copyright holder is:
[NORDUnet](http://www.nordu.net) (2018)




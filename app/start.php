<?php
require   'vendor/autoload.php';


define("SITE_URL" , "http://localhost/paypal-api");
//Sesuaikan dengan URL anda...

$apiContext  = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AcQBXbMMhpt_CQnOew9JmKni3N1CORAeNrSYKyXOa-5fQCZACc913DlAGfvPtwBHm7qcw3MolhNh5Xbl', // ClientID
        'EFzA7oO4qXXOkdAAZm3iQbpUeqPycmbF16pWL5CxuDHFOkNkOUHKuOFc1dH8bRtI1vje4-Rl1cdWZw-u'  

         )
);


//Siempre
//'AeSnl9oZxsp6peIzUO2vR75B1ohpBmD0pYOSRwXkx6LvtjUZhzQxJXZNMTNmUpaf64CBMb3IpPy8bsaw', // ClientID
       // 'EPDUgMmf0oPeZALsoHWvsrQpbCU_u0wz5MeWArxacDUy0zLGDRIiaUPyAZPOg6cDtfMHaZnLI6aBbtKU'  // ClientSecret

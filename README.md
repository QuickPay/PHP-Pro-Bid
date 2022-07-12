# PHP-Pro-Bid

Tutorial for installing Quickpay payment gateway in PHP Pro bid installation
      

      • Unzip the file downloaded from github
      • Upload the content of the mods folder by FTP, to the root of your PHP Pro bid installation (Choose to overwrite if prompted)
      • Access your database through PhpMyAdmin or another MySQL tool (PhpMyAdmin is typically available in your webhost control panel).
      • Open the file /SQL/mod.quickpay.sql and alter the ppb_ prefix so that it matches the prefix in your installation.
      • Run both sql queries on your database.
      • The Quickpay gateway is now available in the admin part of PHP Pro bid. You will have to enter Quickpay Merchant ID, Quickpay Merchant Private Key and Quickpay API Key
      • The IPN/Call-back URL for QuickPay is https://www.your-site-here.com/payment/ipn/coinbase      

      The IPN/Call-back URL for QuickPay is
https://www.your-site-here.com/payment/ipn/coinbase

Do you need help with the installationen, then feel free to contact https://ableit.dk

_____________________________________________________________________________________________________________________________________________
Dansk version

Vejledning til installation af Quickpay betalingsgateway i PHP Pro bid
      

      • Udpak indholdet af zip filen
      • Upload indholdet af mods mappen, via FTP,  til roden af din PHP Pro bid installation (Vælg at overskrive filer, hvis der spørges op,m dette)
      • Tilgå din database ved at anvende PhpMyAdmin eller et andet MySQL værktøj (PhpMyAdmin er typisk tilgængelig i kontrolpanelet hos din webudbyder).
      • Åben filen /SQL/mod.quickpay.sql og ret tabelpræfikset ppb_ til det samme som for din installation.
      • Kør nu begge sql forespørgsler på din database.
      • Quickpay gatewayen er nu tilgængelig i admin af PHP Pro bid. Du skal angive Quickpay Merchant ID, Quickpay Merchant Private Key og Quickpay API Key
      • IPN/Call-back URL til QuickPay er https://www.your-site-here.com/payment/ipn/coinbase


Har du brug for hjælp til installationen, så kan du kontakte https://ableit.dk

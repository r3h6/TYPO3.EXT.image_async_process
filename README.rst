
.. image:: https://travis-ci.org/r3h6/TYPO3.EXT.image_async_process.svg?branch=master
    :target: https://travis-ci.org/r3h6/TYPO3.EXT.image_async_process

*************
Documentation
*************

Speeds up rendering time for a page with many pictures.

TYPO3 CMS renders all the pictures on a page during the first request.
On a page with many pictures this can lead to a long waiting time or even a server timeout.

This extension allows TYPO3 CMS to render pictures asynchronous.

.. tip::

   Use some lazy loading JavaScript library as well.


Installation
============

Step 1
""""""

Install extension.

Step 2
""""""

Add following code to your .htaccess file:

.. code-block:: php

   <IfModule mod_rewrite.c>
      RewriteEngine On
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteCond %{REQUEST_FILENAME} /_processed_/
      RewriteRule ^.*$ %{ENV:CWD}index.php?eID=image_async_process [QSA,L]
   </IfModule>


FAQ
===

How it works?
   This extensions uses a file processing slot for caching image processing information and returning a dummy processed file instance.
   When a browser requests a not existing image, TYPO3 CMS will render it.



Contributing
============

Bug reports and pull requests on develop-branch are welcome through `GitHub <https://github.com/r3h6/TYPO3.EXT.image_async_process/>`_.
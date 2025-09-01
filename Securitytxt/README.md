# Magento_Securitytxt module

> When security vulnerabilities are discovered by researchers, proper reporting channels are often lacking. As a result, vulnerabilities may be left unreported. This document defines a format ("security.txt") to help organizations describe their vulnerability disclosure practices to make it easier for researchers to report vulnerabilities.

_Source: <https://datatracker.ietf.org/doc/html/draft-foudil-securitytxt-09>_

The Magento_Securitytxt module provides the following functionality:

* allows to save the security configurations in the admin panel
* contains a router to match application action class for requests to the `.well-known/security.txt` and `.well-known/security.txt.sig` files
* serves the content of the `.well-known/security.txt` and `.well-known/security.txt.sig` files

A valid _security.txt_ file could look like the following example:

```txt
Contact: mailto:security@example.com
Contact: tel:+1-201-555-0123
Encryption: https://example.com/pgp.asc
Acknowledgement: https://example.com/security/hall-of-fame
Policy: https://example.com/security-policy.html
Signature: https://example.com/.well-known/security.txt.sig
```

_Security.txt_ can be accessed at the location of the following format:
`https://example.com/.well-known/security.txt`

To create _security.txt_ signature (_security.txt.sig_) file, run the following command:

```bash
gpg -u KEYID --output security.txt.sig --armor --detach-sig security.txt
```

To verify the _security.txt_ file's signature, run the following command:

```bash
gpg --verify security.txt.sig security.txt
```

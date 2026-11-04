#!/usr/bin/env python3
# SPDX-License-Identifier: GPL-2.0-or-later
"""Guard against Podman prompting for short-name registries."""
from pathlib import Path

compose = Path('docker-compose.yml').read_text()
example = Path('.env.example').read_text()
for variable, image in (
    ('BOOKORA_DB_IMAGE', 'docker.io/library/mysql:8.0.44'),
    ('BOOKORA_WORDPRESS_IMAGE', 'docker.io/library/wordpress:6.8.3-php8.2-apache'),
):
    assert '${' + variable + ':-' + image + '}' in compose
    assert variable + '=' + image in example
print('Compose image references are fully qualified.')

moodle-tool_imagepicker
=======================

Changes
-------

### v5.3-r2 (fork)

* 2026-11-11 - New element option cropaspectratios: the crop dialog offers a choice of aspect ratios, starts on the recommended one (cropaspectratio) and marks it, and reopens a cropped image on the ratio of its stored region. The demo page has a field which uses it.

### v5.3-r1 (fork)

* 2026-11-11 - Support Moodle 5.1, 5.2 and 5.3 (`$plugin->supported = [500, 503]`).
* 2026-11-11 - New element options cropmaxwidth and cropmaxheight: a cropped image is scaled down to fit inside them, never up, and the crop dialog says so. The demo page has a field which uses them.
* 2026-11-11 - Brazilian Portuguese language pack (in git only; sites get translations through AMOS).
* 2026-11-11 - Fleet standards: CI on Moodle 5.1 to 5.3 through uaiblaine/moodle-workflows, LICENSE beside COPYING.txt, a release package without development files (.gitattributes), PHPUnit metadata as attributes, and a test which holds the plugins directory's package rules.

### v5.0-r1

* 2026-09-10 - Initial version.

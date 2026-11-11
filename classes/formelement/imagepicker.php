<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin tool "Image Picker" - Image picker form element.
 *
 * @package    tool_imagepicker
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_imagepicker\formelement;

use MoodleQuickForm_filemanager;
use renderer_base;
use tool_imagepicker\local\originals;

defined('MOODLE_INTERNAL') || die();

global $CFG;

// The parent element is a legacy (non-autoloaded) QuickForm class, so it has to be required explicitly before this class
// is declared. We import $CFG into scope here as this file may be included from a scope which does not provide it.
require_once($CFG->dirroot . '/lib/form/filemanager.php');

/**
 * Image picker form element.
 *
 * This is a subclass of the core file manager element which enhances the rendered file manager with additional toolbar
 * buttons (for now: an image cropper). Storage-wise it behaves exactly like a plain file manager, so consuming plugins can
 * keep using the usual draft area handling (file_prepare_draft_area() / file_save_draft_area_files()).
 *
 * As this element handles exactly one web image, some of the inherited file manager options are enforced and cannot be
 * overridden by the consuming plugin: 'maxfiles' is always 1, 'subdirs' is always 0 and 'accepted_types' is reduced to the
 * file types which are covered by the 'web_image' file type group.
 *
 * In addition to the inherited file manager options, this element accepts the following options:
 * - 'enablecrop'      (bool)       Whether the crop button is shown. Defaults to true.
 * - 'cropaspectratio' (float|null) The aspect ratio to enforce when cropping. Defaults to null (free cropping).
 * - 'cropmaxwidth'    (int|null)   The largest width, in pixels, of a cropped image. Defaults to null (no limit).
 * - 'cropmaxheight'   (int|null)   The largest height, in pixels, of a cropped image. Defaults to null (no limit).
 * - 'cropaspectratios' (string[])  The aspect ratios the user may choose from in the crop dialog, each 'W:H' (such as
 *                                  '16:9') or 'free'. Defaults to none, which leaves the choice out: the selection keeps
 *                                  'cropaspectratio', or is free without one. With choices, 'cropaspectratio' is the
 *                                  recommended one, preselected and marked as such ('free' when it is null).
 *
 * The two size limits are the size the image is displayed at, so that a crop comes out at its ideal size rather than at the
 * resolution of the photo it was taken from. A crop is scaled down to fit inside both, never up. They are a convenience for
 * the user, not a limit the server enforces: an upload which is not cropped keeps its size.
 *
 * @package    tool_imagepicker
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class imagepicker extends MoodleQuickForm_filemanager {
    /** @var int The encoding quality (in percent) which is used if the site setting is missing or unusable. */
    const DEFAULT_ENCODING_QUALITY = 92;

    /** @var string The size limit strategy which scales a too large cropped image down. */
    const STRATEGY_SCALEDOWN = 'scaledown';

    /** @var string The size limit strategy which lowers the encoding quality of a too large cropped image first. */
    const STRATEGY_LOWERQUALITY = 'lowerquality';

    /** @var string[] The size limit strategies which the client knows. */
    const STRATEGIES = [self::STRATEGY_SCALEDOWN, self::STRATEGY_LOWERQUALITY];

    /** @var array Image picker specific options (in addition to the inherited file manager options). */
    protected $imagepickeroptions = [
        'enablecrop' => true,
        'cropaspectratio' => null,
        'cropmaxwidth' => null,
        'cropmaxheight' => null,
        'cropaspectratios' => [],
    ];

    /** @var string The entry of 'cropaspectratios' which stands for free cropping. */
    const RATIO_FREE = 'free';

    /** @var float How far two aspect ratios may differ and still be the same one (a ratio given as 1200 / 630 vs '40:21'). */
    const RATIO_TOLERANCE = 0.005;

    /**
     * Constructor.
     *
     * @param string $elementname (optional) Name of the image picker.
     * @param string $elementlabel (optional) Image picker label.
     * @param mixed $attributes (optional) Either a typical HTML attribute string or an associative array.
     * @param mixed $options (optional) Set of options to initialize the image picker (see the class docblock).
     */
    public function __construct($elementname = null, $elementlabel = null, $attributes = null, $options = null) {
        $options = (array) $options;

        // Extract the image picker specific options before handing the remaining options to the file manager element.
        // The file manager element would silently drop unknown options anyway.
        foreach ($this->imagepickeroptions as $key => $default) {
            if (array_key_exists($key, $options)) {
                $this->imagepickeroptions[$key] = $options[$key];
                unset($options[$key]);
            }
        }

        // The image picker is meant to handle exactly one image which is stored directly in the file area. We therefore
        // enforce the corresponding file manager options, regardless of what the caller has requested.
        $options['maxfiles'] = 1;
        $options['subdirs'] = 0;

        // The image picker is meant to handle web images only. We therefore reduce the accepted file types to the file
        // types which are covered by the 'web_image' file type group.
        $options['accepted_types'] = $this->restrict_accepted_types($options['accepted_types'] ?? '*');

        parent::__construct($elementname, $elementlabel, $attributes, $options);
    }

    /**
     * Reduce the given accepted file types to those which are covered by the 'web_image' file type group.
     *
     * File types which are not web images (as well as the 'all file types' wildcard) are dropped. If no accepted file type
     * remains afterwards, the whole 'web_image' group is accepted.
     *
     * @param string|array $acceptedtypes The accepted file types as given by the caller.
     * @return array The accepted file types, expanded into plain file extensions.
     */
    protected function restrict_accepted_types($acceptedtypes): array {
        // Get file types utility class.
        $typesutil = new \core_form\filetypes_util();

        // Expand both the given file types and the 'web_image' group into plain file extensions. This way, they can be
        // compared regardless of whether the caller has given extensions, mimetypes or file type groups.
        $giventypes = $typesutil->expand($acceptedtypes);
        $webimagetypes = $typesutil->expand('web_image');

        // Pick the given file types which are web images.
        $restrictedtypes = array_values(array_intersect($giventypes, $webimagetypes));

        // If the caller has not given any web image file type at all (which is especially the case if he has requested all
        // file types with the '*' wildcard), fall back to the whole 'web_image' group.
        if (empty($restrictedtypes)) {
            return $webimagetypes;
        }

        return $restrictedtypes;
    }

    /**
     * Export for template.
     *
     * @param renderer_base $output The renderer.
     * @return array The template context.
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE;

        // Let the file manager element build its context (including the rendered file manager HTML).
        $context = parent::export_for_template($output);

        // The crop button is the only enhancement so far, so without it there is nothing for the JavaScript to do and the
        // module is not loaded at all. The element is a plain (restricted) file manager then.
        if (!$this->imagepickeroptions['enablecrop']) {
            return $context;
        }

        // Initialize the image picker behaviour (extra toolbar buttons) for this element instance.
        //
        // The accepted file types are handed over so that the crop button can hide itself for a file which it cannot usefully
        // crop, and so that the server can refuse to store a cropped image of a file type which this element does not accept.
        // They are handed over expanded into plain file extensions, because the client has no equivalent of filetypes_util
        // with which it could resolve a file type group (such as 'web_image') on its own.
        //
        // Whether the site preserves original images is handed over as well, so that the crop modal can tell the user that
        // the uncropped image is kept on the server. Cropping away sensitive content is a plausible thing to attempt, and it
        // does not achieve what it looks like it achieves while originals are preserved.
        //
        // The encoding quality of cropped images and the strategy for a cropped image which exceeds the file size limit are
        // site settings (see settings.php). The quality is handed over as a fraction, which is what canvas.toBlob() expects.
        //
        // Note that nothing about the form or its context is handed over. The web services work on the draft area of the
        // current user only, and where the image lives is read from the draft file itself on the server (see
        // tool_imagepicker\local\originals::resolve_place()), so there is nothing the client would have to tell - and
        // nothing it could tamper with.
        $typesutil = new \core_form\filetypes_util();
        $PAGE->requires->js_call_amd('tool_imagepicker/imagepicker', 'init', [
            $context['id'],
            [
                'enablecrop' => (bool) $this->imagepickeroptions['enablecrop'],
                'cropaspectratio' => $this->imagepickeroptions['cropaspectratio'],
                'cropmaxwidth' => self::positive_or_null($this->imagepickeroptions['cropmaxwidth']),
                'cropmaxheight' => self::positive_or_null($this->imagepickeroptions['cropmaxheight']),
                'aspectratios' => self::aspect_ratio_choices(
                    $this->imagepickeroptions['cropaspectratios'],
                    $this->imagepickeroptions['cropaspectratio']
                ),
                'acceptedtypes' => $typesutil->expand($this->_options['accepted_types']),
                'preserveoriginals' => originals::is_enabled(),
                'encodingquality' => self::get_encoding_quality() / 100,
                'sizelimitstrategy' => self::get_size_limit_strategy(),
            ],
        ]);

        return $context;
    }

    /**
     * The aspect ratio choices as the crop dialog takes them: a label, the ratio (null for free) and whether it is the
     * recommended one.
     *
     * An entry which is neither 'free' nor two positive numbers around a colon is dropped, and so is a repeated ratio. The
     * recommended choice is the one equal to the element's own aspect ratio (within RATIO_TOLERANCE), or 'free' when the
     * element has none; one that is missing from the list is put first, labelled with the ratio to two decimals. Fewer than
     * two choices are no choice at all, so the list is then empty and the dialog shows no buttons.
     *
     * @param mixed $choices The 'cropaspectratios' option as the caller gave it.
     * @param mixed $recommended The 'cropaspectratio' option: the ideal ratio of the field, or null.
     * @return array List of ['label' => string, 'value' => float|null, 'recommended' => bool].
     */
    public static function aspect_ratio_choices($choices, $recommended): array {
        $recommended = is_numeric($recommended) && (float) $recommended > 0 ? (float) $recommended : null;
        $entries = [];
        foreach (is_array($choices) ? $choices : [] as $choice) {
            $value = self::parse_ratio($choice);
            if ($value === false) {
                continue;
            }
            foreach ($entries as $entry) {
                if (self::same_ratio($entry['value'], $value)) {
                    continue 2;
                }
            }
            $label = $value === null ? get_string('ratio_free', 'tool_imagepicker') : trim((string) $choice);
            $entries[] = ['label' => $label, 'value' => $value, 'recommended' => self::same_ratio($value, $recommended)];
        }
        if (!$entries) {
            return [];
        }
        if (!in_array(true, array_column($entries, 'recommended'), true)) {
            $label = $recommended === null
                ? get_string('ratio_free', 'tool_imagepicker')
                : format_float($recommended, 2) . ':1';
            array_unshift($entries, ['label' => $label, 'value' => $recommended, 'recommended' => true]);
        }

        return count($entries) > 1 ? $entries : [];
    }

    /**
     * Read one aspect ratio choice.
     *
     * @param mixed $choice 'free', or 'W:H' with two positive numbers.
     * @return float|null|false The ratio, null for free, or false for an entry which is neither.
     */
    protected static function parse_ratio($choice) {
        if (!is_string($choice)) {
            return false;
        }
        $choice = trim($choice);
        if ($choice === self::RATIO_FREE) {
            return null;
        }
        if (!preg_match('/^(\d+(?:\.\d+)?):(\d+(?:\.\d+)?)$/', $choice, $matches) || (float) $matches[2] <= 0) {
            return false;
        }
        $ratio = (float) $matches[1] / (float) $matches[2];
        return $ratio > 0 ? $ratio : false;
    }

    /**
     * Whether two aspect ratios are the same one: both free, or within RATIO_TOLERANCE of each other.
     *
     * @param float|null $a A ratio, or null for free.
     * @param float|null $b A ratio, or null for free.
     * @return bool
     */
    protected static function same_ratio(?float $a, ?float $b): bool {
        if ($a === null || $b === null) {
            return $a === $b;
        }
        return abs($a - $b) <= self::RATIO_TOLERANCE * max($a, $b);
    }

    /**
     * A size limit as the client takes it: a positive whole number of pixels, or null for no limit.
     *
     * Anything which is not a number of at least one pixel (null, zero, a negative number, a string which is not numeric) is
     * no limit, so that a careless caller gets the element's behaviour without a limit rather than an image of no size.
     *
     * @param mixed $value The option as the caller gave it.
     * @return int|null The limit in pixels, or null.
     */
    protected static function positive_or_null($value): ?int {
        if (!is_numeric($value) || (int) $value < 1) {
            return null;
        }

        return (int) $value;
    }

    /**
     * Get the encoding quality (in percent) with which cropped images are encoded.
     *
     * This is the site setting, kept within the range which makes sense for a quality: a value which is not a number at all
     * (which includes a setting which has never been saved) falls back to the default, a number outside of 1 to 100 is
     * clamped to that range. The admin UI only offers valid values, but the setting can be written by other means as well
     * (the CLI or a config file, for example), and the client must not be handed a quality it cannot work with.
     *
     * @return int The quality, between 1 and 100.
     */
    public static function get_encoding_quality(): int {
        $setting = get_config('tool_imagepicker', 'encodingquality');
        if ($setting === false || !is_numeric($setting)) {
            return self::DEFAULT_ENCODING_QUALITY;
        }

        return max(1, min(100, (int) $setting));
    }

    /**
     * Get the strategy with which a cropped image that exceeds the file size limit is reduced.
     *
     * This is the site setting, as long as it names a strategy which the client knows; anything else (including a setting
     * which has never been saved) falls back to scaling the image down.
     *
     * @return string One of the STRATEGIES.
     */
    public static function get_size_limit_strategy(): string {
        $setting = get_config('tool_imagepicker', 'sizelimitstrategy');
        if (!is_string($setting) || !in_array($setting, self::STRATEGIES, true)) {
            return self::STRATEGY_SCALEDOWN;
        }

        return $setting;
    }
}

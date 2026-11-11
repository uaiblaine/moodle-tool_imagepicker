# Claude instructions for `tool_imagepicker`

This file is auto-loaded as context whenever Claude works in this plugin's
directory tree. **Fleet-wide standards live in `~/dev/CLAUDE.md`** (coding
style, CI gates, lang-string rules, the `mdl` environment, git rules) — do not
repeat them here. This file keeps only what is true for this plugin, as rules
with a one-line reason each.

Plugin context: a Moodle **admin tool** ("Image picker") that provides a form element, a
subclass of core's file manager for exactly one web image, with a Cropper.js crop dialog in its
toolbar. It owns one table, `tool_imagepicker_original` (bookkeeping of preserved uncropped
originals, no user reference), a file area for those originals, two AJAX web services and a
scheduled task. **A fork** of `moodle-an-hochschulen/moodle-tool_imagepicker` (remote
`upstream`); supports Moodle **5.0 through 5.3** (`$plugin->requires = 2025041400`,
`$plugin->supported = [500, 503]`). CI is `uaiblaine/moodle-workflows`, one job per branch in
`.github/workflows/ci.yml` (5.3 full matrix, 5.2 and 5.1 one DB; 5.0 has no fleet stack) —
**update those jobs when `supported` changes**. Mounted into m503 and m503b at
`admin/tool/imagepicker` by an explicit line in `~/dev/moodle-dev/plugins.conf`.

## Agent orchestration budget (fleet rule, repeated here on purpose)

Section 6 of `~/dev/CLAUDE.md` (`moodle-dev/CLAUDE.fleet.md`) is the authority and says why.
This short copy reaches sessions that do not load that file: cloud sessions and checkouts
outside `~/dev`. Every subagent gets the model and effort of its role from its agent
definition, and none runs on the session model.

| Role | model | effort | agent |
|---|---|---|---|
| Mechanical sweeps, greps, renames, counts, log reading | `haiku` | `medium` | `fleet-sweeper` |
| Checklists against evidence (handoff counts, spec lines against a sweep log, lang lockstep) | `haiku` | `high` | `fleet-checker` |
| Readers, measurers, graders | `sonnet` | `medium` | `fleet-reader` |
| Refuters and verifiers of a blocking finding | `sonnet` | `high` | `fleet-verifier` |
| Well-scoped implementation (established cause, settled design, written recipe) | `sonnet` | `medium` | `fleet-fixer` |
| Non-trivial implementation (open design, several files, long tasks) | `opus` | `high` | `fleet-implementer` |
| Consolidators, critics, estimators, ADR and documentation drafters | `opus` | `high` | `fleet-synthesist` |

- Launch the `Agent` tool with `subagent_type: "fleet-*"`; it has no `effort` parameter, so
  the role's effort comes from that definition (`mdl claude-setup` installs them). Where they
  are not installed, pass `model`. Aliases only; never `fable`; `xhigh` only for a long-horizon
  implementer whose prompt says why; never `xhigh`/`max` on Sonnet or Haiku.
- No long command inside a subagent: `mdl ci --matrix`, `mdl mutate` and Behat run from the
  main session in a background Bash command; a subagent runs the fast gate its prompt names and
  reports the command with its counts.
- Workflows only on the user's opt-in, every `agent()` with `agentType: 'fleet-*'`, under 10
  agents. Advisor off by default.

## Commands

```sh
mdl ci moodle-tool_imagepicker --branch MOODLE_503_STABLE     # one 5.3 leg
mdl ci moodle-tool_imagepicker --matrix --behat               # tier-3 gate
mdl phpunit m503 tool_imagepicker
mdl behat m503 @tool_imagepicker
mdl grunt m503 admin/tool/imagepicker                         # rebuild amd/build (commit with src)
```

The demo page (`/admin/tool/imagepicker/demo.php`, admins only) holds one field per way of
using the element; the Behat features drive it, by field name (`demo_form::FIELDS`).

## Rules for this fork

- **Keep the upstream's voice and layout.** Its files carry the upstream copyright
  (`Alexander Bias, ssystems GmbH`) and that stays; files this fork adds carry
  `2026 Anderson Blaine`. The change log is the upstream's `CHANGES.md`, not a `CHANGELOG.md`,
  so a later upstream sync merges instead of conflicting; fork entries are marked `(fork)`.
- **`grunt-max-lint-warnings: 1` is Cropper.js, not slack.** The vendored `amd/src/cropper.js`
  is on core's eslint ignore list (thirdpartylibs.xml) and grunt still names it, which prints
  one "File ignored" warning. Raise the cap only for another declared vendored file.
- **`cropmaxwidth`/`cropmaxheight` are a client convenience, never a server limit.** The crop is
  scaled down in the browser (`getOutputSize()` in `amd/src/imagepicker.js`); an upload that is
  not cropped keeps its size, and the web services cannot know which element they serve
  (README "File size limit" says why). Do not document them as an enforced size.
- **The web services work on the current user's draft area only**, and where an image lives is
  read from the draft file's `source` field on the server (`originals::resolve_place()`). Never
  add a parameter that lets the browser name a context, a form or an element.
- **The JavaScript drives core's YUI file manager through its DOM** (`refreshFileManager()`):
  check it against `lib/form/filemanager.js` on every new Moodle branch, as `UPGRADE.md` says.
- PHPUnit metadata is in attributes (`#[CoversClass]`, `#[DataProvider]`): no supported branch
  runs the 4.5 moodle-cs that cannot read them.

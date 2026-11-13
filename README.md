# Results Slider – Loop Digital module

PHP / ACF / CSS build of the "Real Results. Real Growth." case study module.

## What's included

```
acf-json/group_loop_results_slider.json   ACF field group (auto-syncs via Local JSON)
blocks/results-slider/block.json          ACF block registration (ACF Pro 6+)
blocks/results-slider/render.php          Block template
assets/css/results-slider.css             Styles (mobile-first, desktop from 1024px)
assets/js/results-slider.js               Tiny vanilla JS slider (no dependencies)
functions-snippet.php                     Registers assets, block, category, ACF JSON path
demo/index.html                           Static preview with sample content (open in a browser)
```

## Installation

1. Copy the folders into the active theme.
2. Paste / include `functions-snippet.php` in the theme's `functions.php`.
3. In **ACF → Field Groups**, sync "Block: Results Slider" (or it loads from `acf-json` automatically).
4. Add the **Results Slider** block (category "Loop Modules") to a page and fill in the slides.

## ACF fields

| Field | Type | Notes |
|---|---|---|
| `heading` | Textarea | Large faint background heading; each line becomes its own row |
| `slides` | Repeater | One row per case study |
| ↳ `image` | Image (ID) | Rendered with `wp_get_attachment_image()` for srcset + lazy loading |
| ↳ `summary` | Textarea | Bold intro text under the image |
| ↳ `tags` | Repeater → `label` | Outlined pills (Website, SEO…) |
| ↳ `stats` | Repeater (max 3) → `value`, `suffix`, `label` | Orange card; suffix (e.g. `%`) renders smaller |
| ↳ `link` | Link | Optional; shows the orange arrow link to the case study |

## Build notes

- **Notched image corner** – done with a CSS `mask` built from gradient layers, so the real background
  shows through the cut-out (works over both the dark section and whatever sits above it).
  Size and rounding are controlled by `--rs-notch` and `--rs-notch-r`.
- **Image overlapping the section above** – the section background starts `--rs-overlap` from the top, so the
  module handles the overlap itself with no negative margins on neighbouring blocks.
- **Theming** – colours, radius and font are CSS custom properties at the top of the stylesheet.
- **Accessibility** – semantic `<section>`/`<article>`, carousel ARIA roles, labelled buttons, stats as a
  `<dl>`, visible focus states, keyboard arrows, `prefers-reduced-motion` respected.
- **Security** – all output escaped (`esc_html`, `esc_attr`, `esc_url`).
- **Performance** – no slider library; first image eager, others lazy; assets only load where the block is used.
- Arrows are hidden automatically when there's only one slide.

## Assumptions

- Arrows cycle through case studies (heading stays fixed).
- The orange up-arrow is a per-slide link to the full case study.
- Font approximated with Inter – swap `--rs-font` for the brand font.
- If the site uses a Flexible Content page builder instead of blocks, the same template works by
  swapping `get_field()` for `get_sub_field()`.

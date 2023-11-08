# plg_system_slscrolltotopghsvs

Joomla system plugin. Adds a scroll to top button in frontend and/or backend.

An adaption of plugin `System - Skyline Scroll To Top` by `Skyline Software` (http://extstore.com , Pham Minh Tuan) for Joomla 4.3+ and Joomla 5. By ghsvs.de.

```
/**
 * @copyright	Copyright (c) 2013 Skyline Software (http://extstore.com). All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 * @copyright	Edited by GHSVS 2023-11. Modifications by ghsvs.de will no longer reflect the original work of its authors.
 */
```

## Deutsche Beschreibung/Deutsches Manual
https://ghsvs.de/programmierer-schnipsel/joomla/372-scroll-to-top-button-in-backend-und-oder-frontend

----------------------

# My personal build procedure (WSL 1, Debian, Win 10)
- Prepare/adapt `./package.json`.
- `cd /mnt/z/git-kram/plg_system_slscrolltotopghsvs`

## node/npm updates/installation
- `npm run updateCheck` or (faster) `npm outdated`
- `npm run update` (if needed) or (faster) `npm update --save-dev`
- `npm install` (if needed)

## Build installable ZIP package
- `node build.js`
- New, installable ZIP is in `./dist` afterwards.
- All packed files for this ZIP can be seen in `./package`. **But only if you disable deletion of this folder at the end of `build.js`**.s

#### For Joomla update server
- Use/See `dist/release.txt` as basic release text.
- Create new release with new tag.
- See extracts(!) of the update and changelog XML for update and changelog servers are in `./dist` as well. Check for necessary additions! Then copy/paste.

## Why

Closes [issue number] or explain what this newly provides or fixes

## Checklist

- [ ] Cloned the repository, set `$ALFRED_PKGMAN_WORKFLOW_DIR`, and ran `make` to use your cloned code as the active Workflow in Alfred
- [ ] Optionally, include a large icon in the `src/icon-src/` folder that is square.
- [ ] Optionally, include a cached icon in the `src/icon-cache/` folder, sized to 256x256 pixels. Alfred creates these when you insert an image into a workflow. You can get this from inside the `.alfredworkflow`. Right-click the workflow in Alfred, click `Show in Finder`. The `--hash-value--.png` file will be in that folder. Copy to `src/icon-cache` and rename.
- [ ] Optionally, add a screenshot. Use ⌘ (command) + ⇧ (shift) + 4, press ␣ (space), then click on the Alfred window to create a clean screen shot. Place in the `screenshots` folder
- [ ] Update README.md with any necessary or changed documentation, and any new screenshots.
- [ ] Increment the version number of the workflow following (Semantic Versioning)[https://semver.org]
- [ ] Run `make release` to package up your changes & copy the Workflow & its `info.plist` to the root of the repository, and commit those two changes on their own, e.g., `Bump to 5.1.1`

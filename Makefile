.PHONY: dist clean updateInfoPlist copyIcons buildWorkflow linkSourceFoldersToWorkflow

distDir=./dist

dist: | composer copySources copyIcons
clean:
	rm -rf $(distDir)/*


composer:
	rm -rf vendor
	composer install -v -o --no-dev

copySources:
	$(shell [ ! -d "$(distDir)" ] && mkdir -p "$(distDir)")
	cp -frv bin $(distDir)
	cp -frv src $(distDir)
	cp -fv composer.* $(distDir)
	cp -frv icon-cache $(distDir)
	cp -frv vendor $(distDir)

updateInfoPlist:
	-[[ "${ALFRED_PKGMAN_WORKFLOW_DIR}" == "" ]] && { echo "ALFRED_PKGMAN_WORKFLOW_DIR is not set! Aborting!"; exit 1; }
	cp -fv "${ALFRED_PKGMAN_WORKFLOW_DIR}"/info.plist .

copyIcons:
	-[[ "${ALFRED_PKGMAN_WORKFLOW_DIR}" == "" ]] && { echo "ALFRED_PKGMAN_WORKFLOW_DIR is not set! Aborting!"; exit 1; }
	find "${ALFRED_PKGMAN_WORKFLOW_DIR}" -type f -name "*.png" -depth 1 -exec cp -fv {} $(distDir) \;

buildWorkflow: | dist
	mkdir -p $(distDir)/"Package Managers.alfredworkflow"
	find $(distDir) -type d ! -name "Package Managers.alfredworkflow" -depth 1 -exec cp -rvf {} $(distDir)/"Package Managers.alfredworkflow" \;
	find $(distDir) -type f ! -name "Package Managers.alfredworkflow" -depth 1 -exec cp -vf {} $(distDir)/"Package Managers.alfredworkflow" \;
	cp -vf info.plist $(distDir)/"Package Managers.alfredworkflow"

linkSourceFoldersToWorkflow:
	-[[ "${ALFRED_PKGMAN_WORKFLOW_DIR}" == "" ]] && { echo "ALFRED_PKGMAN_WORKFLOW_DIR is not set! Aborting!"; exit 1; }

	-[ -d "${ALFRED_PKGMAN_WORKFLOW_DIR}/bin" ] && mv -v "${ALFRED_PKGMAN_WORKFLOW_DIR}/bin" "${ALFRED_PKGMAN_WORKFLOW_DIR}/bin.bak"
	ln -vsfF $(PWD)/bin "${ALFRED_PKGMAN_WORKFLOW_DIR}"

	-[ -d "${ALFRED_PKGMAN_WORKFLOW_DIR}/src" ] && mv -v "${ALFRED_PKGMAN_WORKFLOW_DIR}/src" "${ALFRED_PKGMAN_WORKFLOW_DIR}/src.bak"
	ln -vsfF $(PWD)/src "${ALFRED_PKGMAN_WORKFLOW_DIR}"

	-[ -d "${ALFRED_PKGMAN_WORKFLOW_DIR}/vendor" ] && mv -v "${ALFRED_PKGMAN_WORKFLOW_DIR}/vendor" "${ALFRED_PKGMAN_WORKFLOW_DIR}/vendor.bak"
	ln -vsfF $(PWD)/vendor "${ALFRED_PKGMAN_WORKFLOW_DIR}"
